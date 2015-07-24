<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Matrix;

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

    public function get($row, $col)
    {
        return $this->data->get($row, $col);
    }

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

    public function setShip($row, $col, $id)
    {
        if (!$this->validateShipValue($id)) {
            throw new \Exception("Supplied value is not a ship");
        }

        $this->data->set($row, $col, $id);
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

    private function hideCell($data, $row, $col)
    {
        $data->set($row, $col, static::SIGN_CELL_NOT_SHOWN);
    }

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

    public function mark($row, $col)
    {
        if ($this->isShip($row, $col)) {
            $this->data->set($row, $col, static::SIGN_STRIKE_SUCCESSFUL);
        } else {
            $this->data->set($row, $col, static::SIGN_STRIKE_UNSUCCESSFUL);
        }
    }

    public function size()
    {
        return $this->data->getRows();
    }

    public function __sleep()
    {
        return ['data'];
    }
}