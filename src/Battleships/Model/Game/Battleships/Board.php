<?php
namespace Battleships\Model\Game\Battleships;

class Board
{
    private $size;
    private $data = array();

    const SIGN_CELL_NOT_SHOWN = '*';

    public function __construct($size)
    {
        $this->size = $size;
        $this->initialize($size);
    }

    private function initialize($size)
    {
        $this->traverse(
            function ($row, $col) {
                $this->set($row, $col, null);
            },
            $size
        );
    }

    public function set($row, $col, $value)
    {
        if ($row < 1 || $row > $this->size()) {
            throw new \Exception("Index out of range for row {$row}");
        }

        if ($col < 1 || $col > $this->size()) {
            throw new \Exception("Index out of range for col {$col}");
        }

        $this->data[$row][$col] = $value;
    }

    public function get($row, $col)
    {
        if (!array_key_exists($row, $this->data)) {
            throw new \Exception("Row {$row} not found");
        }

        if (!array_key_exists($col, $this->data[$row])) {
            throw new \Exception("Col {$col} not found at row {$row}");
        }

        return $this->data[$row][$col];
    }

    public function size()
    {
        return $this->size;
    }

    public function getBoardAndHideShips()
    {
        $data = $this->data;
        $this->traverse(
            function ($row, $col) use (&$data) {
                if ($this->isShip($row, $col) || $this->isEmptyCell($row, $col)) {
                    $this->hideCell($data, $row, $col);
                }
            }
        );

        return $data;
    }

    public function setShip($row, $col, $id)
    {
        if (!$this->validateShipValue($id)) {
            throw new \Exception("Supplied value is not a ship");
        }

        $this->set($row, $col, $id);
    }

    public function isShip($row, $col)
    {
        $value = $this->get($row, $col);
        return $this->validateShipValue($value);
    }

    private function validateShipValue($value)
    {
        return (string)intval($value) == $value && $value >= 0;
    }

    private function isEmptyCell($row, $col)
    {
        $value = $this->get($row, $col);
        return is_null($value);
    }

    private function hideCell(&$data, $row, $col)
    {
        $data[$row][$col] = static::SIGN_CELL_NOT_SHOWN;
    }

    public function shipExists($id)
    {
        $exists = false;
        $this->traverse(
            function ($row, $col) use ($id, &$exists) {
                if ($this->isShip($row, $col) && $this->get($row, $col) == $id) {
                    $exists = true;
                    return true;
                }
            }
        );

        return $exists;
    }

    public function shipsLeft()
    {
        $totalShips = 0;
        $lastShip = -1;

        $this->traverse(
            function ($row, $col) use (&$totalShips, &$lastShip) {
                if ($this->isShip($row, $col) && $this->get($row, $col) != $lastShip) {
                    $totalShips++;
                    $lastShip = $this->get($row, $col);
                }
            }
        );

        return $totalShips;
    }

    private function traverse($callback, $size = null)
    {
        if (is_null($size)) {
            $size = $this->size();
        }

        for ($row = 1; $row <= $size; $row++) {
            for ($col = 1; $col <= $size; $col++) {
                $terminate = call_user_func($callback, $row, $col);
                if ($terminate) {
                    return;
                }
            }
        }
    }

    public function __sleep()
    {
        return array('data', 'size');
    }
}