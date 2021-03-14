<?php

namespace App\Core\Contracts;

use App\Core\Helpers\IO\Proxy;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends \LaravelZero\Framework\Commands\Command
{

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        if(! $output instanceof OutputStyle) {
            throw new \Exception(sprintf('The output interface must be of type %s, %s given.', OutputStyle::class, get_class($output)));
        }
        $this->app->instance(Proxy::class, new Proxy($output));
    }

}
