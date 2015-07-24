<?php
namespace Battleships\Model\Template;

interface Template
{
    public function setVariable($key, $value);
    public function render();
}