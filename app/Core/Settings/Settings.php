<?php

namespace App\Core\Settings;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \App\Core\Contracts\Settings\SettingRepository::class;
    }

}
