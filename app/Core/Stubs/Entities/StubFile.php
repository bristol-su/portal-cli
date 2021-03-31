<?php

namespace App\Core\Stubs\Entities;

class StubFile
{

    /**
     * The absolute path to the stub file
     *
     * @var string
     */
    private string $stubPath;

    /**
     * The location to publish the stubs
     *
     * @var string
     */
    private string $location;

    /**
     * The replacements to carry out on the stub file.
     *
     * @var StubReplacement[]
     */
    private array $stubReplacements;

    public function if()
    {
        // TODO Something about including this stub file only sometimes
    }

    /**
     * @return string
     */
    public function getStubPath(): string
    {
        return $this->stubPath;
    }

    /**
     * @param string $stubPath
     * @return StubFile
     */
    public function setStubPath(string $stubPath): StubFile
    {
        $this->stubPath = $stubPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return StubFile
     */
    public function setLocation(string $location): StubFile
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return StubReplacement[]
     */
    public function getStubReplacements(): array
    {
        return $this->stubReplacements;
    }

    /**
     * @param StubReplacement[] $stubReplacements
     * @return StubFile
     */
    public function setStubReplacements(array $stubReplacements): StubFile
    {
        $this->stubReplacements = $stubReplacements;
        return $this;
    }


}
