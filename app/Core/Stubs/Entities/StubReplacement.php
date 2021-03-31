<?php

namespace App\Core\Stubs\Entities;

class StubReplacement
{

    private string $variableName;

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return $this->variableName;
    }

    /**
     * @param string $variableName
     * @return StubReplacement
     */
    public function setVariableName(string $variableName): StubReplacement
    {
        $this->variableName = $variableName;
        return $this;
    }


}
