<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Instance\InstanceRepository;
use App\Core\Contracts\Site\SiteRepository;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Pipeline\PipelineManager;

class FeatureDelete extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:delete
                            {instance? : The id of the feature}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete the given instance';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PipelineManager $installManager, SiteRepository $siteRepository, InstanceRepository $instanceRepository)
    {
        // Get the feature and site to delete
        // Check there are no changes
        // Make all remote branches local
        // Checkout develop
        $this->error('Not yet implemented');

//        if(($instanceId = $this->argument('instance')) === null) {
//            if($siteRepository->count() === 0) {
//                IO::error('No instances are installed.');
//                return;
//            }
//            $instanceId = $this->choice(
//                'Which instance would you like to delete?',
//                $siteRepository->all()->map(fn($site) => $site->getInstanceId())->toArray()
//            );
//        }
//
//        if(!$instanceRepository->exists($instanceId)) {
//            IO::warning('The instance was not found on the filesystem');
//        } else {
//            $installManager->driver('cms')->uninstall(
//                WorkingDirectory::fromInstanceId($instanceId)
//            );
//            IO::success('Removed the project from your filesystem');
//        }
//
//        if($siteRepository->exists()) {
//            $siteRepository->delete($instanceId);
//            IO::success('Pruned remaining feature data.');
//        }
    }

}
