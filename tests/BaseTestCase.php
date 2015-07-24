<?php
require_once __DIR__ . '/../vendor/autoload.php';

abstract class BaseTestCase extends \Silex\WebTestCase
{
    public function createApplication()
    {
        return require_once __DIR__ . '/../app/app.php';
    }
}