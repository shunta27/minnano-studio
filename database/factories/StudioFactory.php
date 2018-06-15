<?php

use App\Repositories\Eloquent\Models\Studio;
use App\Facades\GoogleMap;
use Faker\Generator as Faker;

$factory->define(Studio::class, function (Faker $faker) {
    $studio = [
        'name' => $faker->name,
        'tel' => '09091973796',
        'url' => 'https://www.google.co.jp/',
        'zip' => '1360074',
        'prefecture' => '東京',
        'city_1' => '江東区東砂',
        'city_2' => null,
        'location' => null,
        'studio_count' => 1,
        'open_dt' => '10:00:00',
        'end_dt' => '24:00:00',
        'cheapest_price' => $faker->numberBetween(1000, 3000),
        'is_web_reservation' => $faker->numberBetween(0, 1),
    ];

    $studio['location'] = GoogleMap::getAddressToLocation(
        $studio['prefecture'],
        sprintf("%s%s", $studio['city_1'], $studio['city_2'])
    );

    return $studio;
});
