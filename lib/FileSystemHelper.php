<?php

namespace Lib;

class FileSystemHelper
{
    public static function move(string $from, string $to): bool
    {
        if ($_ENV['APP_ENV'] === 'test') {
            return rename($from, $to);
        } else {
            return move_uploaded_file($from, $to);
        }
    }
}
