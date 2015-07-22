<?php
namespace Battleships\Model\Game\Battleships;

class ActionResult
{
    private $success;
    private $resultValue;

    public function __construct($success, $resultValue)
    {
        $this->success = $success;
        $this->resultValue = $resultValue;
    }

    public function success()
    {
        return $this->success;
    }

    public function getValue()
    {
        return $this->resultValue;
    }
}