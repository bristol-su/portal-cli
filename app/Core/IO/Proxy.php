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

    public function progressStart(int $count)
    {
        $this->output->progressStart($count);
    }

    public function progressStep(int $step = 1)
    {
        $this->output->progressAdvance($step);
    }

    public function progressFinish()
    {
        $this->output->progressFinish();
    }

}
