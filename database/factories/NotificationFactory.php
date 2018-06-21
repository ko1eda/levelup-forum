<?php

use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;
use App\User;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
        // Generates the same type of ID that laravel uses for its notifications before storage
        'id' => $faker->uuid,

        'type' => \App\Notifications\ThreadUpdated::class,

        'notifiable_type' => 'App\User',

        'notifiable_id' => function () {
            return \Auth::user() ? \Auth::user()->id : factory(User::class)->create()->id;
        },

        'data' => ['foo' => 'bar']
    ];
});