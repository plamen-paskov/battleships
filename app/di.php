<?php
use Battleships\Model\Game\Battleships\Game as GameBattleships,
    Battleships\Model\Game\Battleships\BoardTemplate,
    Battleships\Model\Game\Battleships\BoardGenerator,
    Battleships\Model\Game\Battleships\Board,
    Battleships\Model\Matrix;

$app['game.battleships'] = $app->share(
    function ($app) {
        $game = new GameBattleships($app['game.battleships.boardGenerator'], new BoardTemplate($app['twig']));
        return $game;
    }
);

$app['game.battleships.boardGenerator'] = function () {
    return new BoardGenerator(new Board(new Matrix(10, 10)), [
        0 => 2,
        1 => 3,
        2 => 3,
        3 => 4,
        4 => 5
    ]);
};