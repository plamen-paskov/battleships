<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Matrix;

/**
 * The class implements the board logic. Internally it's working with a square matrix
 * Class Board
 * @package Battleships\Model\Game\Battleships
 */
class Board
{
    private $data;

    const SIGN_CELL_NOT_SHOWN = '*';
    const SIGN_STRIKE_SUCCESSFUL = 'X';
    const SIGN_STRIKE_UNSUCCESSFUL = '-';

    public function __construct(Matrix $data)
    {
        $this->data = $data;
    }

    /**
     * Get the value of a specific cell
     * @param $row
     * @param $col
     * @return mixed
     */
    public function get($row, $col)
    {
        return $this->data->get($row, $col);
    }

    /**
     * Get the board as an array and replace all ships with the special symbol that stands for "unknown"
     * @return array
     */
    public function getBoardAndHideShips()
    {
        $data = clone $this->data;
        $this->data->walk(
            function ($row, $col) use ($data) {
                if ($this->isShip($row, $col) || $this->isEmptyCell($row, $col)) {
                    $this->hideCell($data, $row, $col);
                }
            }
        );

        return $data->toArray();
    }

    /**
     * Put the ship with the specified id into the specified cell.
     * The id of the ship needs to be integer greater than 0 otherwise exception is thrown
     * @param $row
     * @param $col
     * @param $id
     * @throws \Exception
     */
    public function setShip($row, $col, $id)
    {
        if (!$this->validateShipValue($id)) {
            throw new \Exception("Supplied value is not a ship");
        }

        $this->data->set($row, $col, $id);
    }

    /**
     * Check if it's a ship on the specified cell
     * @param $row
     * @param $col
     * @return bool
     */
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

    private function hideCell($data, $row, $col)
    {
        $data->set($row, $col, static::SIGN_CELL_NOT_SHOWN);
    }

    /**
     * Check if a ship with the given id still exists on the board
     * @param $id
     * @return bool
     */
    public function shipExists($id)
    {
        $exists = false;
        $this->data->walk(
            function ($row, $col) use ($id, &$exists) {
                if ($this->isShip($row, $col) && $this->get($row, $col) == $id) {
                    $exists = true;
                    return true;
                }
            }
        );

        return $exists;
    }

    /**
     * Get the number of distinct ships still left on the board
     * @return int
     */
    public function shipsLeft()
    {
        $totalShips = 0;
        $lastShip = -1;

        $this->data->walk(
            function ($row, $col) use (&$totalShips, &$lastShip) {
                if ($this->isShip($row, $col) && $this->get($row, $col) != $lastShip) {
                    $totalShips++;
                    $lastShip = $this->get($row, $col);
                }
            }
        );

        return $totalShips;
    }

    /**
     * Try to guess if it's a ship or not on the given cell. Basically if it's a ship the cell value
     * is changed with the symbol that stands for successful strike, otherwise the value is set to another symbol
     * indicating that it's not a ship on the cell
     * @param $row
     * @param $col
     */
    public function mark($row, $col)
    {
        if ($this->isShip($row, $col)) {
            $this->data->set($row, $col, static::SIGN_STRIKE_SUCCESSFUL);
        } else {
            $this->data->set($row, $col, static::SIGN_STRIKE_UNSUCCESSFUL);
        }
    }

    /**
     * As the board is a square matrix the width equals the height so the size is the
     * number of rows that the matrix contains
     * @return int
     */
    public function size()
    {
        return $this->data->getRows();
    }

    /**
     * A shortcut to walk the matrix. The callback is executed for every single cell of the matrix.
     * If the callback returns boolean false the process of traversing the matrix will terminate
     * @param $callback
     * @return array
     */
    public function walk($callback)
    {
        return $this->data->walk($callback);
    }

    /**
     * Serialize the matrix
     * @return array
     */
    public function __sleep()
    {
        return ['data'];
    }
}