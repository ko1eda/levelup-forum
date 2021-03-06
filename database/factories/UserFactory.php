<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => preg_replace('/\'/', '', $faker->name), // this is to make sure tests don't fail on names like O'dell or st
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'confirmed' => true,
        'role_id' => 3
    ];
});


// This is a factory state we can use this with our factories in the format
// factory(class)->state('unconfirmed')->create() and it will merge in whatever
// properties we put in this array to the normal factory state
$factory->state(App\User::class, 'unconfirmed', function () {
    return [
        'confirmed' => false
    ];
});


$factory->state(App\User::class, 'moderator', function () {
    return [
        'role_id' => 2
    ];
});


$factory->state(App\User::class, 'admin', function () {
    return [
        'role_id' => 1
    ];
});
