<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Template\BaseTemplate;

class BoardTemplate extends BaseTemplate
{
    public function render()
    {
        return $this->twig->render(
            'battleships/board.html.twig',
            $this->data
        );
    }
}