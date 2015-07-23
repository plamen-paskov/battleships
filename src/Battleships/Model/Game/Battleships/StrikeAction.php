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