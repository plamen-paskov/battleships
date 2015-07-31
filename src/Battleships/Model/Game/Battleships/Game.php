<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Game\Action;
use Battleships\Model\Game\Game as GameInterface,
    Battleships\Model\Template\Template,
    Battleships\Model\Storage\Storage,
    Battleships\Model\Storage\SessionStorage,
    Battleships\Model\Game\Battleships\BoardGenerator;

/**
 * An entry point for the game. The class is responsible for starting the game,
 * cleaning session and other resources in order to start new game and executing game specific
 * game actions. The class acts like a facade hiding complex logic that is behind the game.
 * Class Game
 * @package Battleships\Model\Game\Battleships
 */
class Game implements GameInterface
{
    private $boardGenerator;
    private $template;
    private $storage;
    private $board;

    const STORAGE_KEY = 'board';

    public function __construct(BoardGenerator $boardGenerator, Template $template, Storage $storage = null)
    {
        $this->boardGenerator = $boardGenerator;
        $this->template = $template;
        $this->storage = $storage;
    }

    /**
     * Start a game if it's not already started or resume the last started one
     * @return Template
     */
    public function start()
    {
        $board = $this->createBoard();
        return $this->draw($board);
    }

    private function createBoard()
    {
        if (!is_null($this->board)) {
            return $this->board;
        }

        $this->board = $this->createStorage()->get(static::STORAGE_KEY);
        if (is_null($this->board)) {
            $this->board = $this->boardGenerator->generate();
            $this->persist($this->board);
        }

        return $this->board;
    }

    private function createStorage()
    {
        if (is_null($this->storage)) {
            $this->storage = new SessionStorage();
        }

        return $this->storage;
    }

    private function persist($board)
    {
        $this->createStorage()->set(static::STORAGE_KEY, $board);
    }

    private function draw($board)
    {
        $this->template->setVariable('board', $board);
        return $this->template;
    }

    /**
     * Execute game specific action. In the case with this game the action is just of one type - trying to
     * guess what's hidden below certain cell
     * @param Action $action
     * @return void
     */
    public function execute(Action $action)
    {
        $action->setBoard($this->createBoard());
        $result = $action->perform();

        $message = 'Miss';
        if ($result->success()) {
            $data = $result->getValue();
            $message = !$this->board->shipExists($data['shipId']) ? 'Sunk' : 'Hit';
        }

        if ($this->board->shipsLeft() == 0) {
            $message = 'Finish';
        }

        $this->template->setVariable('message', $message);
    }

    /**
     * Clear the data associated with last started game so the next call to start a game will create
     * a new game session.
     * @return $this
     */
    public function newGame()
    {
        $this->createStorage()->delete(static::STORAGE_KEY);
        return $this;
    }
}