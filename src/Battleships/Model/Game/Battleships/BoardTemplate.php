<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Template\Template;

class BoardTemplate implements Template
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
            'battleships/board.html.twig',
            $this->data
        );
    }
}