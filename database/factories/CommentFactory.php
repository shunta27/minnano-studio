<?php

use App\Repositories\Eloquent\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'studio_id' => null,
        'body' => 'comments...',
    ];
});
