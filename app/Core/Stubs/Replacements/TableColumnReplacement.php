<?php

namespace App\Core\Stubs\Replacements;

use App\Core\Contracts\Stubs\StubReplacement;
use App\Core\Helpers\IO\IO;

class TableColumnReplacement extends StubReplacement
{

    protected function askQuestion()
    {
        return [
            'name' => IO::ask('What is the column name?'),
            'type' => IO::choice('What is the column type?', [
                'string',
                'bool'
            ]),
            'nullable' => IO::confirm('Is the column nullable?')
        ];
    }

    protected function validateType($value): bool
    {
        return is_array($value) && array_key_exists('name', $value)
            && array_key_exists('type', $value) && array_key_exists('nullable', $value);
    }
}
