<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../src/app.php';

$framework = new Simplex\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$framework->handle($request)->send();