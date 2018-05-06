<?php

use Faker\Generator as Faker;

$factory->define(App\Channel::class, function (Faker $faker) {
    $name = $faker->unique()->word;
    return [
        'name' => $name,
        'slug' => $name
    ];
});
