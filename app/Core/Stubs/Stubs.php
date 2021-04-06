<?php

namespace App\Core\Stubs;

use App\Core\Contracts\Stubs\StubReplacement;
use App\Core\Stubs\Registrar\StubFileRegistrar;
use App\Core\Stubs\Registrar\StubRegistrar;
use App\Core\Stubs\Replacements\ArrayReplacement;
use App\Core\Stubs\Replacements\BooleanReplacement;
use App\Core\Stubs\Replacements\SectionReplacement;
use App\Core\Stubs\Replacements\StringReplacement;

class Stubs
{

    public function newStub(string $name, string $description, string $defaultLocation = null): StubRegistrar
    {
        return StubRegistrar::registerStub($name, $description, $defaultLocation);
    }

    public function newStubFile(string $stubPath, string $fileName, ?string $relativeLocation = null, ?\Closure $showIf = null): StubFileRegistrar
    {
        return StubFileRegistrar::registerStubFile(
            $stubPath, $fileName, $relativeLocation, $showIf
        );
    }

    public function newSectionReplacement(string $variableName, string $questionText, $default = null, ?\Closure $validator = null, array $replacements = []): SectionReplacement
    {
        $replacement = SectionReplacement::new($variableName, $questionText, $default, $validator);
        $replacement->setReplacements($replacements);
        return $replacement;
    }

    public function newArrayReplacement(string $variableName, string $questionText, $default = null, ?\Closure $validator = null, StubReplacement $replacement): ArrayReplacement
    {
        $arrayReplacement = ArrayReplacement::new($variableName, $questionText, $default, $validator);
        $arrayReplacement->setReplacement($replacement);
        return $arrayReplacement;
    }

    public function newStringReplacement(string $variableName, string $questionText, $default = null, ?\Closure $validator = null): StubReplacement
    {
        return StringReplacement::new($variableName, $questionText, $default, $validator);
    }

    public function newBooleanReplacement(string $variableName, string $questionText, $default = null, ?\Closure $validator = null): StubReplacement
    {
        return BooleanReplacement::new($variableName, $questionText, $default, $validator);
    }

}
