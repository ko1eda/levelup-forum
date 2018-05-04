<?php

use Faker\Generator as Faker;
use App\User;
use App\Thread;

$factory->define(App\Reply::class, function (Faker $faker) {

    // Create a user and thread to associate with each reply
    // it is important that this is a callback
    // so that the parameters can be overriden if necessary

    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'thread_id' => function () {
            return factory(App\Thread::class)->create()->id;
        },
        'body' => $faker->paragraph,
    ];
});
