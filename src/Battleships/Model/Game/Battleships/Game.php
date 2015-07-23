<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Game\Action;
use Battleships\Model\Game\Game as GameInterface,
    Battleships\Model\Template\Template,
    Battleships\Model\Storage\Storage,
    Battleships\Model\Storage\SessionStorage;

class Game implements GameInterface
{
    private $template;
    private $storage;
    private $board;

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
//        $debug = $this->board->getHumanReadable();
        if (is_null($this->board)) {
            $boardGenerator = new BoardGenerator();
            $this->board = $boardGenerator->generate();
//            $this->board = $this->generateFakeBoard();
            $this->persist($this->board);
        }

        return $this->board;
    }

    private function generateFakeBoard()
    {
        $b = new Board(10);
        $b->set(1,1,0);
        $b->set(1,2,0);
        $b->set(2,1,1);
        $b->set(2,2,1);
        $b->set(2,3,1);
        $b->set(3,1,2);
        $b->set(3,2,2);
        $b->set(3,3,2);
        $b->set(3,4,2);
        return $b;
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
}