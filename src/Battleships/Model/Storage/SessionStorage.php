<?php
namespace Battleships\Model\Storage;

/**
 * This class will hide the logic of dealing with the sessions
 * Class SessionStorage
 * @package Battleships\Model\Storage
 */
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

    /*
     * Set a value in the session
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a value from the session
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    /**
     * Delete a session value by key
     * @param $key
     */
    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
}