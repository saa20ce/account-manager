<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

// Load environment variables from .env file
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);

// Set trusted proxies for reverse proxies and load balancers
$proxies = $_SERVER['TRUSTED_PROXIES'] ?? false;
if ($proxies) {
    Request::setTrustedProxies(explode(',', $proxies), Request::HEADER_FORWARDED ^ Request::HEADER_X_FORWARDED_HOST);
}

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
