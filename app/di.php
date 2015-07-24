<?php
use Battleships\Model\Game\Battleships\Game as GameBattleships,
    Battleships\Model\Template\TableTemplate as Template;

$app['game.battleships'] = $app->share(
    function ($app) {
        $game = new GameBattleships(new Template($app['twig']));
        return $game;
    }
);