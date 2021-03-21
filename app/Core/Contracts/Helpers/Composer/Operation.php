<?php

namespace App\Core\Contracts\Helpers\Composer;

use App\Core\Helpers\Composer\Schema\Schema\ComposerSchema;
use PHPUnit\Framework\MockObject\BadMethodCallException;

interface Operation
{

    public function perform(ComposerSchema $composerSchema): ComposerSchema;

}
