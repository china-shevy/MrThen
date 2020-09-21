<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Simplex\ContentLengthListener;
use Simplex\GoogleListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addListener('response', GoogleListener::getSubscribedEvents());
$dispatcher->addListener('response', ContentLengthListener::getSubscribedEvents());

$framework = new Simplex\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();