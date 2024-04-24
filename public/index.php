<?php

use Bootstrap\App;

require_once '../src/app/Bootstrap/app.php';

require_once '../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env');

$dotenv->load();

return App::make();
