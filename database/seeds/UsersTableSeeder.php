<?php

use App\Defines\User\Role;
use App\Repositories\Eloquent\Models\User;
use App\Repositories\Eloquent\Models\UserInformation;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Password Grant Client
        Artisan::call('passport:client', ['--password' => null, '--no-interaction' => true]);

        factory(User::class, 1)->create([
            'role' => Role::SYSTEM(),
            'email' => 'hoge1@gmail.com',
        ])->each(function ($user) {
            factory(UserInformation::class, 1)->create([
                'user_id' => $user->id,
                'name' => 'system_user',
            ]);
        });

        factory(User::class, 1)->create([
            'role' => Role::ADMIN(),
            'email' => 'hoge2@gmail.com',
        ])->each(function ($user) {
            factory(UserInformation::class, 1)->create([
                'user_id' => $user->id,
                'name' => 'admin_user',
            ]);
        });

        factory(User::class, 1)->create([
            'role' => Role::USER(),
            'email' => 'hoge3@gmail.com',
        ])->each(function ($user) {
            factory(UserInformation::class, 1)->create([
                'user_id' => $user->id,
                'name' => 'user',
            ]);
        });
    }
}
