<?php
namespace Battleships\Model;

class Matrix
{
    private $rows;
    private $cols;
    private $data = [];

    public function __construct($rows, $cols)
    {
        if (!$this->isInteger($rows)) {
            throw new \InvalidArgumentException("Rows argument is not an integer greater than 1");
        }

        if (!$this->isInteger($cols)) {
            throw new \InvalidArgumentException("Cols argument is not an integer greater than 1");
        }

        $this->rows = (int)$rows;
        $this->cols = (int)$cols;
        $this->initialize();
    }

    private function initialize()
    {
        $this->walk(
            function ($row, $col) {
                $this->set($row, $col, null);
            }
        );
    }

    private function isInteger($value)
    {
        return (string)intval($value) == $value && $value >= 1;
    }

    public function set($row, $col, $value)
    {
        if ($row < 1 || $row > $this->rows) {
            throw new \Exception("Index out of range for row {$row}");
        }

        if ($col < 1 || $col > $this->cols) {
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

    public function walk($callback)
    {
        for ($row = 1; $row <= $this->rows; $row++) {
            for ($col = 1; $col <= $this->cols; $col++) {
                $terminate = call_user_func($callback, $row, $col);
                if ($terminate) {
                    return $this->data;
                }
            }
        }

        return $this->data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getCols()
    {
        return $this->cols;
    }

    public function __sleep()
    {
        return ['data', 'rows', 'cols'];
    }
}