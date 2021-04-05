<?php

namespace App\Core\Stubs\Replacements;

use App\Core\Contracts\Stubs\StubReplacement;
use App\Core\Helpers\IO\IO;

class BooleanReplacement extends StubReplacement
{

    protected function askQuestion(): bool
    {
        return IO::confirm(
            $this->getQuestionText(),
            $this->getDefault(true)
        );
    }

    public function validateType($value): bool
    {
        return is_bool($value);
    }

}
