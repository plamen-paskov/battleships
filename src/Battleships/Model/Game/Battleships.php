<?php
namespace Battleships\Model\Game;

use Battleships\Model\Template\Template,
    Battleships\Model\Storage\Storage,
    Battleships\Model\Storage\SessionStorage,
    Battleships\Model\Board\Board;

class Battleships implements Game
{
    private $boardSize = 10;
    private $board;
    private $storage;
    private $template;
    private static $storageKey = 'board';
    private static $ships = array(
        0 => 2,
        1 => 3,
        2 => 3,
        3 => 4,
        4 => 5
    );

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

    private function createStorage()
    {
        if (is_null($this->storage)) {
            $this->storage = new SessionStorage();
        }

        return $this->storage;
    }

    private function createBoard()
    {
        if (!is_null($this->board)) {
            return $this->board;
        }

        $this->board = $this->createStorage()->get(static::$storageKey);
        if (is_null($this->board)) {
            $this->board = new Board($this->boardSize);
            $this->addShips($this->board);
            $this->persistBoard();
        }

        return $this->board;
    }

    private function addShips()
    {
        for ($i = 0, $c = count(static::$ships); $i < $c; $i++) {
            $this->addShip($i, static::$ships[$i]);
        }
    }

    private function addShip($id, $size)
    {
        while (true) {
            $direction = rand(1, 2);
            $col = rand(1, $this->board->size());
            $row = rand(1, $this->board->size());

            if ($direction == 1) {
                if ($this->validateVerticalPosition($col, $row, $size)) {
                    $this->putVerticalShip($id, $col, $row, $size);
                    break;
                }
            } elseif ($direction == 2) {
                if ($this->validateHorizontalPosition($col, $row, $size)) {
                    $this->putHorizontalShip($id, $col, $row, $size);
                    break;
                }
            }
        }
    }

    private function persistBoard()
    {
        $this->createStorage()->set(static::$storageKey, $this->board);
    }

    private function putVerticalShip($id, $col, $row, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            $this->board->set($col + $offset, $row, $id);
        }
    }

    private function putHorizontalShip($id, $col, $row, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            $this->board->set($col, $row + $offset, $id);
        }
    }

    private function validateVerticalPosition($col, $row, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            try {
                $currentCol = $col + $offset;
                $value = $this->board->get($currentCol, $row);
                if (!is_null($value)) {
                    throw new \Exception("Validation failed at col {$currentCol} row {$row}");
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    private function validateHorizontalPosition($col, $row, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            try {
                $currentRow = $row + $offset;
                $value = $this->board->get($col, $currentRow);
                if (!is_null($value)) {
                    throw new \Exception("Validation failed at col {$col} row {$currentRow}");
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    private function draw($board)
    {
        $this->template->setVariable('size', $board->size());
        $this->template->setVariable('data', $board->getArray());
        return $this->template;
    }
}