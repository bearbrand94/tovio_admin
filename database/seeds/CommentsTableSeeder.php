<?php

use Illuminate\Database\Seeder;
use App\Comment;
use App\User;
use App\Post;
use App\Notifications\CommentCreated;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Comment::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        // for ($i = 0; $i < 80; $i++) {
        //     Comment::create([
        //     	'post_id' => $faker->numberBetween(1, 50),
        //         'content' => $faker->sentence,
        //         'commented_by' => $faker->numberBetween(1, 11),
        //         'comment_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
        //     ]);
        // }
        
        for ($i = 1; $i < 500; $i++) {
            for ($j = 1; $j < $faker->numberBetween(0, 10); $j++) {
                //create main comment
                $comment = Comment::create([
                    'post_id' => $i,
                    'content' => $faker->sentence,
                    'commented_by' => $faker->numberBetween(1, 300),
                    'comment_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
                    'parent_id' => 0,
                ]);
                $last_id = $comment->id;

                $post_data = Post::get_post_by_id($comment->post_id);
                $user_notif = User::find($post_data[0]->posted_by);
                Notification::send($user_notif, new CommentCreated($comment));

                //create child comment with chance of 0 to 10.
                for ($j = 0; $j < $faker->numberBetween(0, 10); $j++) {
                    $comment_child = Comment::create([
                        'post_id' => $i,
                        'content' => $faker->sentence,
                        'commented_by' => $faker->numberBetween(1, 300),
                        'comment_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
                        'parent_id' => $last_id,
                    ]);
                    Notification::send($user_notif, new CommentCreated($comment_child));
                }
            }
        }
    }
}
