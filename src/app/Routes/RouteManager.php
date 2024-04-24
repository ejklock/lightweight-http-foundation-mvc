<?php

namespace App\Routes;

class RouteManager
{
    public static function getRoutes()
    {
        return require_once __DIR__ . '/../Routes/routes.php';
    }
}
