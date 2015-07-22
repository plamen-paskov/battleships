<?php
namespace Battleships\Controller;

use Silex\Application,
    Battleships\Model\Game\Battleships\Game,
    Battleships\Model\Template\TableTemplate,
    Battleships\Model\Game\Battleships\Action;

class BattleshipsController
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function index()
    {
        $game = new Game(new TableTemplate($this->app['twig']));
        return $game
            ->start()
            ->render();
    }

    public function play()
    {
        $col = $this->app['request']->query->get('col');
        $row = $this->app['request']->query->get('row');

        $game = new Game(new TableTemplate($this->app['twig']));
        $response = $game->start();
        $game->execute(new Action\Guess($col, $row));
        return $response->render();
    }
}