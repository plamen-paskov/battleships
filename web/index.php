<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Battleships\Model\Game\Battleships\Game,
    Battleships\Model\Template\TableTemplate;


$app = new Silex\Application();

$app['debug'] = true;

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => __DIR__ . '/../resources/views',
        'twig.options' => array(
            'cache' => __DIR__ . '/../resources/twig_cache'
        )
    )
);

$app->get(
    '/',
    function () use ($app) {
        $game = new Game(new TableTemplate($app['twig']));
        return $game
            ->start()
            ->render();
    }
);

$app->get(
    '/move',
    function () use ($app) {
        $game = new Game(new TableTemplate($app['twig']));
        return $game
            ->start()
            ->render();
    }
);

$app->run();