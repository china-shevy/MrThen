<?php

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year'        => null,
    '_controller' => 'Calendar\\Controller\\LeapYearController::indexAction',
]));

$routes->add('exception', new Routing\Route('/exception', [
    'year'        => null,
    '_controller' => 'Calendar\\Controller\\ErrorController::exceptionAction',
]));

return $routes;