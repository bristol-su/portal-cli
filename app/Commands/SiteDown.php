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

class SiteDown extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'site:down
                            {--S|site= : The id of the site}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Turn off the given site';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PipelineManager $installManager, SiteRepository $siteRepository, InstanceRepository $instanceRepository)
    {
        $site = $this->getSite(
            'Which site would you like to turn off?',
            fn(Site $site) => $site->instance()->getStatus() === Instance::STATUS_READY
        );

        $workingDirectory = WorkingDirectory::fromSite($site);

        IO::info('Turning off site.');

        Executor::cd($workingDirectory)->execute('./vendor/bin/sail down');

        IO::success('Turned off site.');

    }

}
