<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Spam Manager config settings
    |--------------------------------------------------------------------------
    | This file is for specifying the specific settings the spam filter will use.
    | The blacklist will determine any additonal keywords you would like to run
    | against the spam detection algorithm.
    | The threshold is the maximum number of allowed spam phareses before the detctor
    | throws an exception.
    |
    */

    'blacklist' => [
       'Yahoo Customer Support',
    ],

    'threshold' => 1,

    // Throttle the number of times a user or ip can hit certain routes
    // for a given controller
    'throttle' => [

        'threads' => [
            'routes' => ['show', 'store'],
            'frequency' => '20,1'
        ],

        'replies' => [
            'routes' => ['store', 'update'],
            'frequency' => '5,1'
        ],

    ]
];
