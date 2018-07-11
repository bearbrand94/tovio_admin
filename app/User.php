<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'username', 'password', 'first_name', 'last_name', 'telephone', 'address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function  getUser($page, $show, $keyword, $sort_type, $key_sort){
        $data = User::select('*');
        // if ($category != 'All') {
        //     $data = $data->where('area_of_expertise', $category);
        // }
        // $data = $data->where('lbod',$lbod);

        if($keyword){
            if (env('DB_CONNECTION')=='mysql') {
                /* CONNECTION MYSQL */
                $temp = Searchy::user('first_name','last_name','username','email')->query($keyword)
                                ->getQuery();
                if(!$key_sort){
                    $temp = $temp->skip($page*$show)->take($show)->get();
                }else{
                    $temp = $temp->get();
                }
                $t = [];
                foreach ($temp as $key => $value) {
                    $t[] = $value->id;
                }
                if (count($t)<1) {
                    return null;
                }
                if($key_sort){
                    $data = $data->whereIn('user.id',$t)
                                ->orderBy($key_sort,$sort_type);
                }else{
                    $data = $data->whereIn('user.id',$t)
                        ->orderByRaw("field(user.id," . implode(',', $t) . ")");
                    }
            }else if (env('DB_CONNECTION')=='pgsql') {
                /* CONNECTION PGSQL */
                $temp2 = Searchy::user('first_name','last_name','username','email')->query($keyword)
                                    ->getQuery()->toSql();
                $temp = DB::table( DB::raw("(". $temp2 .") as qq") )->select('id','relevance')
                            ->where('qq.relevance','>',0);
                if(!$key_sort){
                    $temp = $temp->skip($page*$show)->take($show)->get();
                }else{
                    $temp = $temp->get();
                }
                $t = [];
                foreach ($temp as $key => $value) {
                    $t[] = $value->id;
                }
                if (count($t)<1) {
                    return null;
                }
                if($key_sort){
                    $data = $data->whereIn('user.id',$t)
                                ->orderBy($key_sort,$sort_type);
                }else{
                    $data = $data->whereIn('user.id',$t)
                    ->orderByRaw("array_position(array[" . implode(',', $t) ."],user.id)");
                }
            }

        }
        $count = $data;
        $count = count($data->get());
        $data = $data->skip($page*$show)
                    ->take($show);

        if($key_sort){
            $data = $data->orderBy($key_sort,$sort_type);
        }else{
            $data = $data->orderBy('created_at','desc');
        }
        $data = $data->get();

        $output['data']=$data;
        $output['count']=$count;
        return $output;
    }

    public function followers()
    {
        return $this->belongsToMany(self::class, 'networks', 'following_id', 'follower_id')->withTimestamps();
    }

    // Get all users we are following
    public function following()
    {
        return $this->belongsToMany(self::class, 'networks', 'follower_id', 'following_id')->withTimestamps();
    }
}
