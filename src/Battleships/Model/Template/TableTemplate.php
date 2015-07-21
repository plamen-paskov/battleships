<?php
namespace Battleships\Model\Template;

class TableTemplate implements Template
{
    private $twig;
    private $data = array();

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function setVariable($key, $value)
    {
        $this->data[$key] = $value;
    }

    private function getVariable($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }

    public function render()
    {
        return $this->twig->render(
            'index.twig',
            array(
                'size' => $this->getVariable('size'),
                'data' => $this->getVariable('data')
            )
        );
    }
}