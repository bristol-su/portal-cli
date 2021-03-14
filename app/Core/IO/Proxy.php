<?php

namespace App\Core\IO;

use Illuminate\Console\OutputStyle;

class Proxy
{

    /**
     * @var OutputStyle
     */
    private OutputStyle $output;

    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
    }

    public function error(string $line)
    {
        $this->output->error($line);
    }

    public function errors(array $line)
    {
        $this->output->error($line);
    }

    public function info(string $line)
    {
        $this->output->info($line);
    }

    public function infos(array $line)
    {
        $this->output->info($line);
    }

    public function ask(string $question, $default = null, \Closure $validator = null)
    {
        return $this->output->ask($question, $default, $validator);
    }

    public function __call($name, $arguments)
    {
        return $this->output->{$name}(...$arguments);
    }

}
