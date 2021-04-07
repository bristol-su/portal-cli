<?php

namespace App\Commands;

use App\Core\Contracts\Command\Command;
use App\Core\Contracts\Command\FeatureCommand;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Stubs\Entities\Stub;
use App\Core\Stubs\Entities\StubFile;
use App\Core\Stubs\StubMigrator;
use App\Core\Stubs\StubDataCollector;
use App\Core\Stubs\StubSaver;
use App\Core\Stubs\StubStore;

class StubMake extends FeatureCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'stub:make
                            {--S|stub= : The name of the stub to make}
                            {--L|location= : The directory relative to the project to save the stubs in}
                            {--O|overwrite : Overwrite any files that already exist}
                            {--U|use-default : Use the default settings for the stub}
                            {--R|dry-run : Do not save any stub files, just output them to the terminal}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Use a stub';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(StubStore $stubStore, StubMigrator $stubCreator, StubDataCollector $dataCollector)
    {
        $stubName = $this->getOrAskForOption(
            'stub',
            fn() => $this->choice(
                'Which stub would you like to use?',
                collect($stubStore->getAllStubs())->map(fn(Stub $stub) => $stub->getName())->toArray()
            ),
            fn($value) => $value && $stubStore->hasStub($value)
        );

        $workingDirectory = $this->getWorkingDirectory();

        $stub = $stubStore->getStub($stubName);

        $compiledStubs = $stubCreator->create($stub, $this->option('use-default'));

        IO::info('Stubs compiled');

        $saver = StubSaver::in(WorkingDirectory::fromPath(
            Filesystem::append(
                $workingDirectory->path(),
                $this->option('location') ?? $stub->getDefaultLocation()
            )
        ))->force($this->option('overwrite'));

        foreach($compiledStubs as $stub) {
            $saver->save($stub, $this->option('dry-run'));
        }

        IO::success('Stubs saved');

    }

}
