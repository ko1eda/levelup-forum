<?php

use Faker\Generator as Faker;
use App\User;

$factory->define(App\Thread::class, function (Faker $faker) {
    
    // Create a user to be associated with the thread
    // it is important that this is a callback
    // so that the parameters can be overriden if necessary

    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'channel_id' => function () {
            return factory(App\Channel::class)->create()->id;
        },
        'title' => $faker->text(80),
        'body' => implode($faker->paragraphs(2)),
    ];
});
