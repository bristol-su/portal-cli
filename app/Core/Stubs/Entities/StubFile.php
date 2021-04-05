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
    private string $location = '';

    private string $fileName;

    /**
     * @var StubReplacement[]
     */
    private array $replacements = [];

    /**
     * @return StubReplacement[]
     */
    public function getReplacements(): array
    {
        return $this->replacements;
    }

    /**
     * @param StubReplacement[] $replacements
     * @return StubFile
     */
    public function setReplacements(array $replacements): StubFile
    {
        $this->replacements = $replacements;
        return $this;
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
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    // TODO Convert setFilename to take a callback that is passed the data?

    /**
     * @param string $fileName
     * @return StubFile
     */
    public function setFileName(string $fileName): StubFile
    {
        $this->fileName = $fileName;
        return $this;
    }

}
