<?php

namespace App\Core\Helpers\Port;

class PortChecker
{

    public static function isFree(int $port): bool
    {
        return true;
    }

    public static function isTaken(int $port): bool
    {
        return !static::isFree($port);
    }

}
