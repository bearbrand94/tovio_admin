<?php

use Illuminate\Database\Seeder;
use App\PostTemplate;
class PostTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PostTemplate::truncate();

        $post = PostTemplate::create([
            'type' => 'managing_money',
            'value' => url('storage/app/public/posts/templates/managing_money.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'meeting',
            'value' => url('storage/app/public/posts/templates/meeting.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'events',
            'value' => url('storage/app/public/posts/templates/events.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'travelling',
            'value' => url('storage/app/public/posts/templates/travelling.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'working_at_project',
            'value' => url('storage/app/public/posts/templates/working_at_project.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'home_activity',
            'value' => url('storage/app/public/posts/templates/home_activity.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'learning',
            'value' => url('storage/app/public/posts/templates/learning.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'study',
            'value' => url('storage/app/public/posts/templates/study.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'preparing_meals',
            'value' => url('storage/app/public/posts/templates/preparing_meals.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'marketing_goals',
            'value' => url('storage/app/public/posts/templates/marketing_goals.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'fitness',
            'value' => url('storage/app/public/posts/templates/fitness.png')
        ]);
        $post = PostTemplate::create([
            'type' => 'work_in_group',
            'value' => url('storage/app/public/posts/templates/work_in_group.png')
        ]);
    }
}
