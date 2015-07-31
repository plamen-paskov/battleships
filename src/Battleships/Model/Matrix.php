<?php
namespace Battleships\Model;

/**
 * Encapsulate the logic of the matrix and adds dimension validations
 * Class Matrix
 * @package Battleships\Model
 */
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

    /**
     * Set a value into the given matrix entry.
     * @param $row
     * @param $col
     * @param $value
     * @throws \Exception
     */
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

    /**
     * Get the value of the specified entry
     * @param $row
     * @param $col
     * @return mixed
     * @throws \Exception
     */
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

    /**
     * Execute the callback for every matrix entry. If the callback return boolean false the traversal of
     * the matrix will terminate. The method returns the modified matrix as an array.
     * @param $callback
     * @return array
     */
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

    /**
     * Returns an array representation of the matrix
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Get the number of rows
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Get the number of columns that every row contains
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    public function __sleep()
    {
        return ['data', 'rows', 'cols'];
    }
}