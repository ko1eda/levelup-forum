<?php


return [
   /*
    |--------------------------------------------------------------------------
    | General config settings
    |--------------------------------------------------------------------------
    | General customization settings will go here.
    |
    |
    */
    // Default admin account info
    'admin' => [
        'name' => '1upteam',
        'username' => '1upteam',
        'email' => 'admin@oneupforum.com',
        'password' => password_hash(env('DEFAULT_ADMIN_PASSWORD') ?? 'secret', PASSWORD_BCRYPT),
        'role_id' => 1,
        'confirmed' => 1,
    ],


    
    // Settings to control the activity feed
    'activityfeed' => []

];
