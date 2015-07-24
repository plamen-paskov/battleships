<?php
namespace Battleships\Controller;

use Battleships\Model\Game\Battleships\Game,
    Symfony\Component\HttpFoundation\Request,
    Battleships\Model\Game\Battleships\StrikeAction;

class BattleshipsController
{
    private $game;
    private $request;

    public function __construct(Game $game, Request $request)
    {
        $this->game = $game;
        $this->request = $request;
    }

    public function index()
    {
        return $this->game
            ->start()
            ->render();
    }

    public function play()
    {
        $col = $this->request->query->get('col');
        $row = $this->request->query->get('row');

        $response = $this->game->start();
        $this->game->execute(new StrikeAction($col, $row));
        return $response->render();
    }

    public function newGame()
    {
        return $this->game
            ->newGame()
            ->start()
            ->render();
    }
}