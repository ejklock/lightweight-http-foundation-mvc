<?php

namespace App\Handlers;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class HttpHandlers
{

    public static function notFound()
    {
        return new Response('Not Found', 404);
    }

    public static function badRequest()
    {
        return new Response('Bad Request', 400);
    }

    public static function internalServerError()
    {
        return new Response('Internal Server Error', 500);
    }

    public static function notAllowed()
    {
        return new Response('Method Not Allowed', 405);
    }
}
