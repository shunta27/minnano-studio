<?php

use App\Repositories\Eloquent\Models\UserInformation;
use Faker\Generator as Faker;

$factory->define(UserInformation::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'enabled' => 1,
    ];
});
