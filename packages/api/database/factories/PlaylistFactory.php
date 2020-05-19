<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Playlist;
use Faker\Generator as Faker;

$factory->define(Playlist::class, function (Faker $faker) {
    return [
        'name' => $faker->realText(50),
        'user_id' => $faker->numberBetween(1, 2),
    ];
});
