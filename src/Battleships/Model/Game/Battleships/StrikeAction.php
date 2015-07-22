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
        $shipId = $this->board->get($this->col, $this->row);

        if (is_numeric($shipId)) {
            $success = true;
            $message = 'Hit';
            $value = 'X';
        } else {
            $success = false;
            $value = '-';
            $message = 'Miss';
        }

        $data = array(
            'shipId' => $shipId,
            'message' => $message
        );

        $this->board->set($this->col, $this->row, $value);

        $result = new ActionResult($success, $data);
        return $result;
    }
}