<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Song;
use Faker\Generator as Faker;

$factory->define(Song::class, function (Faker $faker) {
    return [
        'title' => $faker->realText(50),
        'thumbnail' => $faker->imageUrl,
        'genre' => 'Rock',
        'rating' => 0,
        'path' => '/songs',
        'artist_id' => $faker->numberBetween(1, 10),
        'user_id' => $faker->numberBetween(1, 2),
    ];
});
