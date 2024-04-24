<?php
return
    [
        [
            'path' => '/users',
            'method' => 'GET',
            'handler' => 'App\Domains\User\Controllers\UserController::index'
        ],
        [
            'path' => '/users',
            'method' => 'POST',
            'handler' => 'App\Domains\User\Controllers\UserController::create'
        ]
    ];
