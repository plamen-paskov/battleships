<?php
namespace Battleships\Model\Storage;

class MemoryStorage implements Storage
{
    private $data = array();

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }
}