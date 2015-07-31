<?php
namespace Battleships\Model\Template;

/**
 * The purpose of this interface is to hide the real template engine that is in use
 * Interface Template
 * @package Battleships\Model\Template
 */
interface Template
{
    /**
     * Set a variable that will be passed to the template
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setVariable($key, $value);

    /**
     * Pass all the variables to the template, render it and return it
     * @return mixed
     */
    public function render();
}