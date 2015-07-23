<?php
namespace Battleships\Model\Game;

interface Game
{
    public function start();

    public function execute(Action $action);

    public function newGame();
}