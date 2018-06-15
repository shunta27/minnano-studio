<?php

use App\Repositories\Eloquent\Models\User;
use App\Repositories\Eloquent\Models\Studio;
use App\Repositories\Eloquent\Models\Comment;
use Illuminate\Database\Seeder;

class StudiosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // コメントあり
        factory(Studio::class, 5)
            ->create()
            ->each(function (Studio $studio) {
                $users = User::get();
                foreach($users->random(3) as $user)
                {
                    factory(Comment::class, 1)->create([
                        'user_id' => $user->id,
                        'studio_id' => $studio->id,
                        'body' => str_random('100'),
                    ]);
                }
            });

        // コメントなし
        factory(Studio::class, 20)->create();
    }
}
