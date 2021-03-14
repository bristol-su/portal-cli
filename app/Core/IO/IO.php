<?php

namespace App\Core\IO;

use Illuminate\Support\Facades\Facade;

/**
 * @see Proxy
 */
class IO extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Proxy::class;
    }

}
