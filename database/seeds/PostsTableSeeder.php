<?php

use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    private $date;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->date = new Carbon\Carbon();
        $users = User::all();
        $categories = \App\Category::all();
        factory(Post::class, 200)
            ->make()
            ->each(function ($post) use ($users, $categories) {
                $post->user_id = $users->random()->id;
                $post->save();

                $post->created_at = $this->date->format('Y-m-d');
                $post->save();
                $this->date = $this->date->addMinutes(20);

                $post->categories()->attach($categories->random(5)->pluck('id'));
                $comments = factory(\App\Comment::class, 5)->create([
                    'user_id' => $users->random()->id,
                    'post_id' => $post->id
                ]);
                $date = new Carbon\Carbon();
                foreach ($comments as $comment) {
                    $comment->created_at = $date->format('Y-m-d');
                    $comment->save();
                    $date = $date->addMinutes(20);
                }
            });
    }
}
