<?php
namespace Battleships\Model\Game\Battleships;

class BoardGenerator
{
    private static $boardSize = 10;
    private static $ships = array(
        0 => 2,
        1 => 3,
        2 => 3,
        3 => 4,
        4 => 5
    );

    const DIRECTION_VERTICAL = 1;
    const DIRECTION_HORIZONTAL = 2;

    public function generate()
    {
        $board = new Board(static::$boardSize);
        $this->addShips($board);
        return $board;
    }

    private function addShips($board)
    {
        for ($i = 0, $c = count(static::$ships); $i < $c; $i++) {
            $this->addShip($board, $i, static::$ships[$i]);
        }
    }

    private function addShip($board, $id, $size)
    {
        while (true) {
            $direction = rand(static::DIRECTION_VERTICAL, static::DIRECTION_HORIZONTAL);
            $col = rand(1, $board->size());
            $row = rand(1, $board->size());

            if ($direction == static::DIRECTION_VERTICAL) {
                if ($this->fitVertical($board, $col, $row, $size)) {
                    $this->addVerticalShip($board, $id, $col, $row, $size);
                    break;
                }
            } elseif ($direction == static::DIRECTION_HORIZONTAL) {
                if ($this->fitHorizontal($board, $col, $row, $size)) {
                    $this->addHorizontalShip($board, $id, $col, $row, $size);
                    break;
                }
            }
        }
    }

    private function fitVertical($board, $col, $row, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            try {
                $currentCol = $col + $offset;
                $value = $board->get($currentCol, $row);
                if (!is_null($value)) {
                    throw new \Exception("Validation failed at col {$currentCol} row {$row}");
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    private function fitHorizontal($board, $col, $row, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            try {
                $currentRow = $row + $offset;
                $value = $board->get($col, $currentRow);
                if (!is_null($value)) {
                    throw new \Exception("Validation failed at col {$col} row {$currentRow}");
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    private function addVerticalShip($board, $id, $col, $row, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            $board->set($col + $offset, $row, $id);
        }
    }

    private function addHorizontalShip($board, $id, $col, $row, $size)
    {
        for ($offset = 0; $offset < $size; $offset++) {
            $board->set($col, $row + $offset, $id);
        }
    }
}