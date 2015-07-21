<?php
require_once __DIR__ . '/../vendor/autoload.php';

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
        return $app['twig']->render(
            'index.twig',
            array()
        );
    }
);

$app->run();