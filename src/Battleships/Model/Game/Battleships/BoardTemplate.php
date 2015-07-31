<?php
namespace Battleships\Model\Game\Battleships;

use Battleships\Model\Template\BaseTemplate;

/**
 * This class is an adapter for working with templates. It's existence will give us
 * possibilities to exchange the template engine if we need to do that
 * Class BoardTemplate
 * @package Battleships\Model\Game\Battleships
 */
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