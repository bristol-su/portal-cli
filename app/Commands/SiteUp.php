<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Instance\InstanceFactory;
use App\Core\Contracts\Instance\InstanceRepository;
use App\Core\Contracts\Site\SiteRepository;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Terminal\Executor;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Instance\Instance;
use App\Core\Pipeline\PipelineManager;
use App\Core\Site\Site;

class SiteUp extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'site:up
                            {--S|site= : The id of the site}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Turn on the given site';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PipelineManager $installManager, SiteRepository $siteRepository, InstanceRepository $instanceRepository)
    {
        $site = $this->getSite(
            'Which site would you like to turn on?',
            fn(Site $site) => $site->instance()->getStatus() === Instance::STATUS_DOWN
        );

        $workingDirectory = WorkingDirectory::fromSite($site);

        IO::info('Turning on site.');

        Executor::cd($workingDirectory)->execute('./vendor/bin/sail up -d');

        IO::success('Turned on site.');

    }

}
