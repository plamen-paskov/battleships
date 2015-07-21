<?php
namespace Battleships\Model\Storage;

interface Storage
{
    public function set($key, $value);

    public function get($key);
}