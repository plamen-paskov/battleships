<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Battleships\Model\Game\Battleships\Game,
    Battleships\Model\Template\TableTemplate,
    Symfony\Component\HttpFoundation\Request,
    Battleships\Model\Game\Battleships\Action;


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
    '/play',
    function (Request $request) use ($app) {
        $col = $request->query->get('col');
        $row = $request->query->get('row');

        $game = new Game(new TableTemplate($app['twig']));
        $response = $game->start();
        $game->execute(new Action\Guess($col, $row));
        return $response->render();
    }
);

$app->run();