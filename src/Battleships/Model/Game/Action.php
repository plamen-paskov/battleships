<?php
namespace Battleships\Model\Game;

interface Action
{
    /**
     * Execute a game action
     * @return mixed
     */
    public function perform();
}