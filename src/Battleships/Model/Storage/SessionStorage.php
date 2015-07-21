<?php
namespace Battleships\Model\Storage;

class SessionStorage implements Storage
{
    public function __construct()
    {
        $this->startSession();
    }

    private function startSession()
    {
        if (session_id() == '') {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
}