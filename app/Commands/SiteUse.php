<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Instance\InstanceFactory;
use App\Core\Contracts\Instance\InstanceRepository;
use App\Core\Contracts\Site\SiteRepository;
use App\Core\Contracts\Site\SiteResolver;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Terminal\Executor;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Instance\Instance;
use App\Core\Pipeline\PipelineManager;
use App\Core\Site\Site;

class SiteUse extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'site:use
                            {--S|site= : The id of the site}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Use the given site by default.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SiteResolver $siteResolver)
    {
        $site = $this->getSite('Which site would you like to use by default?', null, true);

        IO::info('Switching default site to ' . $site->getName() . '.');

        $siteResolver->setSite($site);

    }

}
