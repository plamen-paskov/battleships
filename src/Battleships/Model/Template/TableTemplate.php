<?php
namespace Battleships\Model\Template;

class TableTemplate implements Template
{
    private $twig;
    private $data = [];

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function setVariable($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function render()
    {
        return $this->twig->render(
            'index.twig',
            $this->data
        );
    }
}