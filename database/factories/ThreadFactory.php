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
        'title' => $faker->sentence,
        'body' => implode($faker->paragraphs(2)),
    ];
});
