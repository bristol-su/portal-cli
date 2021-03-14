<?php

namespace App\Core\Settings;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class SettingRepository implements \App\Core\Contracts\Settings\SettingRepository
{

    public function set(string $key, $value): void
    {
        SettingModel::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function get(string $key, $default = null)
    {
        try {
            return SettingModel::where('key', $key)->firstOrFail()->value;
        } catch (ModelNotFoundException $e) {
            return $default;
        }
    }

    public function has(string $key): bool
    {
        return SettingModel::where('key', $key)->count() > 0;
    }

}
