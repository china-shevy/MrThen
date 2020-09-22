<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;



$sc = include __DIR__ . '/../src/container.php';
$sc->register('listener.string_response', 'Simplex\StringResponseListener');
$sc->getDefinition('dispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.string_response')));
$sc->setParameter('charset', 'UTF-8');
$sc->setParameter('debug', true);
$sc->setParameter('routes', include __DIR__ . '/../src/app.php');

$request = Request::createFromGlobals();

$response = $sc->get('framework')->handle($request);
$response->send();