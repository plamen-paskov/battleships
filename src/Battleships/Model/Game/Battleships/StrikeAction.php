<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Game\Action;

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

    public function perform()
    {
        if ($this->board->isShip($this->row, $this->col)) {
            $data = array(
                'shipId' => $this->board->get($this->row, $this->col),
                'message' => 'Hit'
            );
            $success = true;
            $value = 'X';
        } else {
            $data = array(
                'message' => 'Miss'
            );
            $success = false;
            $value = '-';
        }

        $this->board->set($this->row, $this->col, $value);

        $result = new ActionResult($success, $data);
        return $result;
    }
}