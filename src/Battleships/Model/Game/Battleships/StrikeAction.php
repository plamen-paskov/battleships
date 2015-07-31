<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Game\Action;

/**
 * Encapsulate the logic of trying to guess what's below the given cell
 * Class StrikeAction
 * @package Battleships\Model\Game\Battleships
 */
class StrikeAction implements Action
{
    private $col;
    private $row;
    private $board;

    public function __construct($col, $row)
    {
        $this->col = $col;
        $this->row = $row;
    }

    public function setBoard(Board $board)
    {
        $this->board = $board;
    }

    /**
     * Replace the value of the specified cell and return the id of the ship if it was a ship in this cell
     * @return ActionResult|mixed
     */
    public function perform()
    {
        $success = false;
        $data = [
            'message' => 'Miss'
        ];

        if ($this->board->isShip($this->row, $this->col)) {
            $success = true;
            $data = [
                'shipId' => $this->board->get($this->row, $this->col),
                'message' => 'Hit'
            ];
        }

        $this->board->mark($this->row, $this->col);

        $result = new ActionResult($success, $data);
        return $result;
    }
}