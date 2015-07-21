<?php
namespace Battleships\Model\Board;

class Board
{
    private $size;
    private $data = array();

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

    public function __sleep()
    {
        return array('data', 'size');
    }
}