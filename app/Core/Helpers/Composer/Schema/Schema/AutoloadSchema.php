<?php

namespace App\Core\Helpers\Composer\Schema\Schema;

class AutoloadSchema
{

    private array $psr4;

    private array $psr0;

    /**
     * @var array|string[]
     */
    private array $classmap;

    /**
     * @var array|string[]
     */
    private array $files;

    /**
     * @var array|string[]
     */
    private array $excludeFromClassmap;

    /**
     * @return array
     */
    public function getPsr4(): array
    {
        return $this->psr4;
    }

    /**
     * @param array $psr4
     */
    public function setPsr4(array $psr4): void
    {
        $this->psr4 = $psr4;
    }

    /**
     * @return array
     */
    public function getPsr0(): array
    {
        return $this->psr0;
    }

    /**
     * @param array $psr0
     */
    public function setPsr0(array $psr0): void
    {
        $this->psr0 = $psr0;
    }

    /**
     * @return array|string[]
     */
    public function getClassmap(): array
    {
        return $this->classmap;
    }

    /**
     * @param array|string[] $classmap
     */
    public function setClassmap(array $classmap): void
    {
        $this->classmap = $classmap;
    }

    /**
     * @return array|string[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array|string[] $files
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * @return array|string[]
     */
    public function getExcludeFromClassmap(): array
    {
        return $this->excludeFromClassmap;
    }

    /**
     * @param array|string[] $excludeFromClassmap
     */
    public function setExcludeFromClassmap(array $excludeFromClassmap): void
    {
        $this->excludeFromClassmap = $excludeFromClassmap;
    }

}
