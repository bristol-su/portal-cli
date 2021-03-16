<?php

namespace App\Commands;

use App\Core\Contracts\Instance\MetaInstanceRepository;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Pipeline\PipelineManager;
use Illuminate\Support\Str;
use App\Core\Contracts\Command;

class FeatureNew extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:new
                            {--N|name= : The name of the feature}
                            {--R|repository=cms : Takes values of `cms` or `frontend`}
                            {--D|description= : A description for the feature}
                            {--T|type= : The type of change}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new instance of Atlas.';


    /**
     * @var MetaInstanceRepository
     */
    protected $metaInstanceRepository;

    protected $instanceId = null;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PipelineManager $installManager, MetaInstanceRepository $metaInstanceRepository)
    {
        $this->metaInstanceRepository = $metaInstanceRepository;
        $this->info('Creating a new feature');

        $name = trim($this->getInstanceName());
        $instanceId = trim($this->getInstanceId($name));
        $description = trim($this->getInstanceDescription());
        $type = trim($this->getInstanceChangeType());

        $workingDirectory = WorkingDirectory::fromInstanceId($instanceId);

        try {
            $installManager->driver(
                $this->option('repository')
            )->install($workingDirectory);
            $metaInstance = $metaInstanceRepository->create(
                $instanceId,
                $name,
                $description,
                $type,
                $this->option('repository')
            );
        } catch (\Exception $e) {
            if($this->output->isVerbose()) {
                throw $e;
            }
            IO::error('Install failed: ' . $e->getMessage());
            return;
        }

        $this->getOutput()->success(sprintf('Installed a new Atlas instance.'));
    }

    private function getInstanceId(string $name): string
    {
        if($this->instanceId === null) {
            $id = Str::kebab($name);
            $prefix = '';
            while($this->metaInstanceRepository->exists($id . $prefix) === true) {
                if($prefix === '') {
                    $prefix = 1;
                } else {
                    $prefix++;
                }
            }
            $this->instanceId = $id . $prefix;
        }
        return $this->instanceId;
    }

    private function getInstanceName(): string
    {
        return $this->getOrAskForOption(
            'name',
            fn() => $this->ask('Name this feature in a couple of words'),
            fn($value) => $value && is_string($value)
        );
    }

    private function getInstanceDescription(): string
    {
        return $this->getOrAskForOption(
            'name',
            fn() => $this->ask('Describe what this feature will do'),
            fn($value) => $value && is_string($value) && strlen($value) < 250
        );
    }

    private function getInstanceChangeType()
    {
        $allowedTypes = [
            'added' => 'Added (for new features)',
            'changed' => 'Changed (for changes in existing functionality)',
            'deprecated' => 'Deprecated (for soon-to-be removed features)',
            'removed' => 'Removed (for now removed features)',
            'fixed' => 'Fixed (for any bug fixes)',
            'security' => 'Security (in case of vulnerabilities)'
        ];

        return $this->getOrAskForOption(
            'type',
            fn() => array_search(
                $this->choice('What kind of change is this?', array_values($allowedTypes)),
                $allowedTypes
            ),
            fn($value) => $value && in_array($value, array_keys($allowedTypes))
        );
    }

}
