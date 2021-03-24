<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Instance\InstanceRepository;
use App\Core\Contracts\Site\SiteRepository;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Pipeline\PipelineManager;

class SiteDelete extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'site:delete
                            {--S|site= : The id of the site}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete the given site';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PipelineManager $installManager, SiteRepository $siteRepository, InstanceRepository $instanceRepository)
    {
        $site = $this->getSite('Which sites would you like to delete?');

        if(!$instanceRepository->exists($site->getInstanceId())) {
            IO::warning('The site was not found on the filesystem');
        } else {
            $installManager->driver($site->getInstaller())->uninstall(
                WorkingDirectory::fromSite($site)
            );
            IO::success('Removed the site from your filesystem');
        }

        $siteRepository->delete($site->getId());
        IO::success('Pruned remaining site data.');
    }

}
