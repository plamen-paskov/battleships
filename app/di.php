<?php
use Battleships\Model\Game\Battleships\Game as GameBattleships,
    Battleships\Model\Game\Battleships\BoardTemplate;

$app['game.battleships'] = $app->share(
    function ($app) {
        $game = new GameBattleships(new BoardTemplate($app['twig']));
        return $game;
    }
);