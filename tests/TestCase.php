<?php

namespace Tests;

use Core\Database\Database;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    public function setUp(): void
    {
        $_ENV['APP_ENV'] = 'test';
        Database::drop();
        Database::create();
        Database::migrate();
    }

    public function tearDown(): void
    {
        Database::drop();
        $_ENV['APP_ENV'] = 'development';
    }

    protected function getOutput(callable $callable): string
    {
        ob_start();
        $callable();
        return ob_get_clean();
    }
}
