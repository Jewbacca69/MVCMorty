<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use RickAndMorty\Controllers\EpisodeController;

require_once 'vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/app/Views');
$twig = new Environment($loader);

$episodeController = new EpisodeController($twig);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($episodeController) {
    $r->addRoute('GET', '/episodes', [$episodeController, 'index']);
    $r->addRoute('GET', '/episode/{id:\d+}', [$episodeController, 'displayEpisode']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $vars = $routeInfo[2];

        [$controller, $method] = $routeInfo[1];

        echo $controller->{$method}($vars);
        break;
}
