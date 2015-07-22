<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Game\Action;
use Battleships\Model\Game\Game as GameInterface,
    Battleships\Model\Template\Template,
    Battleships\Model\Storage\Storage,
    Battleships\Model\Storage\SessionStorage;

class Game implements GameInterface
{
    private $board;
    private $template;
    private $storage;

    const STORAGE_KEY = 'board';

    public function __construct(Template $template, Storage $storage = null)
    {
        $this->template = $template;
        $this->storage = $storage;
    }

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
            $boardGenerator = new BoardGenerator();
            $this->board = $boardGenerator->generate();
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

    public function execute(Action $action)
    {
        $action->setBoard($this->createBoard());
        $success = $action->perform();
    }
}