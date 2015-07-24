<?php
namespace Battleships\Model\Template;

abstract class BaseTemplate implements Template
{
    protected $twig;
    protected $data = [];

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function setVariable($key, $value)
    {
        $this->data[$key] = $value;
    }

    abstract function render();
}