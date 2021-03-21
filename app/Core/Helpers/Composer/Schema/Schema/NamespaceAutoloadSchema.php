<?php

namespace App\Core\Helpers\Composer\Schema\Schema;

use Illuminate\Support\Arr;

class NamespaceAutoloadSchema
{

    /**
     * @var string
     */
    private string $namespace;

    /**
     * @var array|string
     */
    private $paths;

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return array|string
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param array|string $paths
     */
    public function setPaths($paths): void
    {
        $this->paths = $paths;
    }

}
