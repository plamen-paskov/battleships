<?php
namespace Battleships\Provider;

use Silex\Application,
    Silex\ServiceProviderInterface,
    Battleships\Controller\BattleshipsController;

class ControllerProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['battleships.controller'] = $app->share(
            function () use ($app) {
                return new BattleshipsController($app['game.battleships'], $app['request']);
            }
        );
    }

    public function boot(Application $app)
    {
    }
}