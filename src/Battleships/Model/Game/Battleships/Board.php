<?php
namespace Battleships\Model\Game\Battleships;

class Board
{
    private $size;
    private $data = array();

    const SIGN_CELL_SHOWN = '-';
    const SIGN_CELL_NOT_SHOWN = '*';
    const SIGN_SHIP_CELL_DISCOVERED = '';

    public function __construct($size)
    {
        $this->size = $size;
        $this->initialize($size);
    }

    private function initialize($size)
    {
        for ($col = 1; $col <= $size; $col++) {
            for ($row = 1; $row <= $size; $row++) {
                $this->set($col, $row, null);
            }
        }
    }

    public function set($col, $row, $value)
    {
        if ($col < 1 || $col > $this->size()) {
            throw new \Exception("Index out of range for col {$col}");
        }

        if ($row < 1 || $row > $this->size()) {
            throw new \Exception("Index out of range for row {$row}");
        }

        $this->data[$col][$row] = $value;
    }

    public function get($col, $row)
    {
        if (!array_key_exists($col, $this->data)) {
            throw new \Exception("Col {$col} not found");
        }

        if (!array_key_exists($row, $this->data[$col])) {
            throw new \Exception("Row {$row} not found");
        }

        return $this->data[$col][$row];
    }

    public function size()
    {
        return $this->size;
    }

    public function getArray()
    {
        return $this->data;
    }

    public function mask()
    {
        $data = $this->data;
        for ($col = 1, $size = $this->size(); $col <= $size; $col++) {
            for ($row = 1; $row <= $size; $row++) {
                if (is_numeric($data[$col][$row]) || empty($data[$col][$row])) {
                    $data[$col][$row] = static::SIGN_CELL_NOT_SHOWN;
                }
            }
        }

        return $data;
    }

    public function setShip($col, $row, $id)
    {
        if (!$this->isShip($id)) {
            throw new \Exception("Supplied value is not a ship");
        }

        $this->set($col, $row, $id);
    }

    private function isShip($value)
    {
        return (int) $value == $value && $value > 0;
    }

    public function shipExists($id)
    {
        for ($col = 1, $size = $this->size(); $col <= $size; $col++) {
            for ($row = 1; $row <= $size; $row++) {
                if ($this->data[$col][$row] == $id) {
                    return false;
                }
            }
        }

        return true;
    }

    public function shipsLeft()
    {
        $totalShips = 0;
        $lastShip = null;
        for ($col = 1, $size = $this->size(); $col <= $size; $col++) {
            for ($row = 1; $row <= $size; $row++) {
                if ($this->isShip($this->data[$col][$row]) && $this->data[$col][$row] != $lastShip) {
                    $totalShips++;
                    $lastShip = $this->data[$col][$row];
                }
            }
        }

        return $totalShips;
    }

    public function __sleep()
    {
        return array('data', 'size');
    }
}