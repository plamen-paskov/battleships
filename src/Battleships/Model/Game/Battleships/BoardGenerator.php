<?php
namespace Battleships\Model\Game\Battleships;

class BoardGenerator
{
    private $board;
    private $ships = [];

    const DIRECTION_VERTICAL = 1;
    const DIRECTION_HORIZONTAL = 2;

    public function __construct(Board $board, array $ships)
    {
        $this->board = $board;
        $this->ships = $ships;
    }

    public function generate()
    {
        $this->addShips($this->board);
        return $this->board;
    }

    private function addShips($board)
    {
        for ($i = 0, $c = count($this->ships); $i < $c; $i++) {
            $this->addShip($board, $i, $this->ships[$i]);
        }
    }

    private function addShip($board, $id, $size)
    {
        while (true) {
            $direction = rand(static::DIRECTION_VERTICAL, static::DIRECTION_HORIZONTAL);
            $col = rand(1, $board->size());
            $row = rand(1, $board->size());

            if ($direction == static::DIRECTION_VERTICAL) {
                if ($this->fitVertical($board, $row, $col, $size)) {
                    $this->addVerticalShip($board, $id, $row, $col, $size);
                    break;
                }
            } elseif ($direction == static::DIRECTION_HORIZONTAL) {
                if ($this->fitHorizontal($board, $row, $col, $size)) {
                    $this->addHorizontalShip($board, $id, $row, $col, $size);
                    break;
                }
            }
        }
    }

    private function fitVertical($board, $row, $col, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            try {
                $currentRow = $row + $offset;
                $value = $board->get($currentRow, $col);
                if (!is_null($value)) {
                    throw new \Exception("Validation failed at row {$currentRow} col {$col}");
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    private function fitHorizontal($board, $row, $col, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            try {
                $currentCol = $col + $offset;
                $value = $board->get($row, $currentCol);
                if (!is_null($value)) {
                    throw new \Exception("Validation failed at row {$row} col {$currentCol}");
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    private function addVerticalShip($board, $id, $row, $col, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            $board->setShip($row + $offset, $col, $id);
        }
    }

    private function addHorizontalShip($board, $id, $row, $col, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            $board->setShip($row, $col + $offset, $id);
        }
    }
}