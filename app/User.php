<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Searchy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'username', 'password', 'first_name', 'last_name', 'telephone', 'address', 'gender', 'birthday', 'company', 'description', 'website', 'original_image_url', 'medium_image_url', 'thumbnail_image_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table = "users";

    public static function testSearchy($keyword){
        $user_data = DB::table('users')
            ->where('username', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->get();
        return $user_data;
    }

    public static function search_user($keyword, $paginate=10, $page=1){
        $user_data = DB::table('users')
            ->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name','users.telephone', 'users.address', 'users.website', 'users.company', 'users.gender', 'users.birthday', 'users.description', 'users.original_image_url', 'users.medium_image_url', 'users.thumbnail_image_url',
                DB::raw('(select count(*) from posts where posted_by = users.id) as events_created'),
                DB::raw('(select count(*) from posts where posted_by = users.id and is_completed=true) as events_completed'),
                DB::raw('(select count(*) from networks where follower_id = users.id) as following_count'),
                DB::raw('(select count(*) from networks where following_id = users.id) as follower_count')
            )
            ->where('username', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->orWhere('first_name', 'like', '%' . $keyword . '%')
            ->orWhere('last_name', 'like', '%' . $keyword . '%')
            ->get();

        $slice = array_slice($user_data->toArray(), $paginate * ($page - 1), $paginate);
        $result = new LengthAwarePaginator($slice, count($user_data), $paginate);
        return $result;
    }

    public static function get_user_list($show=0)
    {
        // return Datatables::of(User::query())->make(true);

        $user = DB::table('users')
                ->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name','users.telephone', 'users.website', 'users.company', 'users.gender', 'users.birthday', 'users.description', 'users.original_image_url', 'users.medium_image_url', 'users.thumbnail_image_url',
                    DB::raw('(select count(*) from posts where posted_by = users.id) as events_created'),
                    DB::raw('(select count(*) from posts where posted_by = users.id and is_completed=true) as events_completed'),
                    DB::raw('(select count(*) from networks where follower_id = users.id) as following_count'),
                    DB::raw('(select count(*) from networks where following_id = users.id) as follower_count')
                )
                ->groupBy('users.id');

        if($show>0){
            $user = $user->paginate($show);
        }
        else{
            $user = $user->get();
        }

        return $user;
    }

    public static function getUserDetail($user_id){
        $user_data = DB::table('users')
            ->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name','users.telephone', 'users.website', 'users.company', 'users.gender', 'users.birthday', 'users.description', 'users.original_image_url', 'users.medium_image_url', 'users.thumbnail_image_url',
                DB::raw('(select count(*) from posts where posted_by = users.id) as events_created'),
                DB::raw('(select count(*) from posts where posted_by = users.id and is_completed=true) as events_completed'),
                DB::raw('(select count(*) from networks where follower_id = users.id) as following_count'),
                DB::raw('(select count(*) from networks where following_id = users.id) as follower_count')
            )
            ->where('users.id', $user_id)
            ->groupBy('users.id')
            ->get();
        return $user_data;
    }

    public static function getFollowData($user_id){
        $follow_data['followed_by_me'] = DB::table('networks')
        ->where('follower_id', Auth::id())
        ->where('following_id', $user_id)
        ->count();

        $follow_data['following_me'] = DB::table('networks')
        ->where('follower_id', $user_id)
        ->where('following_id', Auth::id())
        ->count();

        return $follow_data;
    }

    public static function  getUser_Searchy($page, $show, $keyword, $sort_type, $key_sort){
        $data = User::select('*');

        if($keyword){
            if (env('DB_CONNECTION')=='mysql') {
                /* CONNECTION MYSQL */
                $temp = Searchy::users('first_name','last_name','username','email')->query($keyword)
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
                    $data = $data->whereIn('users.id',$t)
                                ->orderBy($key_sort,$sort_type);
                }else{
                    $data = $data->whereIn('users.id',$t)
                        ->orderByRaw("field(users.id," . implode(',', $t) . ")");
                    }
            }else if (env('DB_CONNECTION')=='pgsql') {
                /* CONNECTION PGSQL */
                $temp2 = Searchy::users('first_name','last_name','username','email')->query($keyword)
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
                    $data = $data->whereIn('users.id',$t)
                                ->orderBy($key_sort,$sort_type);
                }else{
                    $data = $data->whereIn('users.id',$t)
                    ->orderByRaw("array_position(array[" . implode(',', $t) ."],users.id)");
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

    public static function get_network($user_id){
        $results = DB::select( DB::raw("
            SELECT follower_id
            FROM networks 
            WHERE following_id = :user_id1 AND follower_id IN(
                SELECT following_id
                FROM networks
                WHERE follower_id = :user_id2
            );
        "), array(
           'user_id1' => $user_id,
           'user_id2' => $user_id,
        ));

        return $results;
    }
}
