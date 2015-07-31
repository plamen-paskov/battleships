<?php
namespace Battleships\Model\Game;

interface Game
{
    /**
     * Start/resume a game
     * @return mixed
     */
    public function start();

    /**
     * Execute a game specific action
     * @param Action $action
     * @return mixed
     */
    public function execute(Action $action);

    /**
     * Prepare launching a new game. Cleaning sessions, updating stats, etc.
     * @return mixed
     */
    public function newGame();
}