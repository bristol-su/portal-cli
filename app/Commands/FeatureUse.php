<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Contracts\Feature\FeatureResolver;
use App\Core\Contracts\Site\SiteResolver;
use App\Core\Helpers\IO\IO;
use App\Core\Packages\LocalPackage;
use Cz\Git\GitException;
use Cz\Git\GitRepository;

class FeatureUse extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:use
                            {--S|feature= : The id of the feature}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Use the given feature by default.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(FeatureResolver $featureResolver, SiteResolver $siteResolver)
    {
        $feature = $this->getFeature('Which feature would you like to use by default?', null, true);

        IO::info('Switching default feature to ' . $feature->getName() . '.');

        $this->task('Resetting the site',
            fn() => $this->call(SiteReset::class, ['--site' => $feature->getSite()->getId()]));

        // TODO Base branch stored in site definition
        $this->task('Checkout the base branch', function() use ($feature) {
            $git = new GitRepository($feature->getSite()->getWorkingDirectory()->path());
            try {
                $git->checkout($feature->getBranch());
            } catch (GitException $e) {
                $git->createBranch($feature->getBranch(), true);
            }
        });

        $this->task('Install local packages', function() use ($feature) {
            /** @var LocalPackage[] $packages */
            $packages = $feature->getLocalPackages();

            IO::progressStart(count($packages));
            foreach($packages as $package) {
                $this->call(DepLocal::class, [
                    '--feature' => $feature->getId(),
                    '--package' => $package->getName(),
                    '--branch' => $package->getBranch(),
                    '--repository-url' => $package->getUrl(),
                ]);
                IO::progressStep(1);
            }
            IO::progressFinish();
        });

        $this->task('Updating site state', function() use ($feature) {
            /** @var LocalPackage[] $packages */
            $packages = $feature->getLocalPackages();

            IO::progressStart(count($packages));
            foreach($packages as $package) {
                $this->call(DepLocal::class, [
                    '--feature' => $feature->getId(),
                    '--package' => $package->getName(),
                    '--branch' => $package->getBranch(),
                    '--repository-url' => $package->getUrl(),
                ]);
                IO::progressStep(1);
            }
            IO::progressFinish();
        });

        $this->task(sprintf('Setting the default feature to %s', $feature->getName()), fn() => $featureResolver->setFeature($feature));

    }

}