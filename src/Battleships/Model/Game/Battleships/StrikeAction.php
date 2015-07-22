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
        $value = $this->board->get($this->col, $this->row);

        $success = false;
        if (is_numeric($value)) {
            $success = true;
            $value = 'X';
        } else {
            $value = '-';
        }

        $this->board->set($this->col, $this->row, $value);
        return $success;
    }
}