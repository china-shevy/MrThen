<?php

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('hello', new Routing\Route('/hello/{name}', [
    'name' => 'World',
    '_controller' => function ($request) {
        $request->attributes->set('foo', 'bar');

        $response = render_template($request);

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
]));
$routes->add('bye', new Routing\Route('/bye'));

return $routes;