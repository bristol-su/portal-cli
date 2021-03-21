<?php


namespace App\Core\Helpers\Composer\Schema\Schema;


class ScriptSchema
{

    private string $name;

    /**
     * @var array|string
     */
    private $commands;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array|string
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param array|string $commands
     */
    public function setCommands($commands): void
    {
        $this->commands = $commands;
    }


}
