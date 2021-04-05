<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Stubs\Entities\Stub;
use App\Core\Stubs\Entities\StubFile;
use App\Core\Stubs\StubMigrator;
use App\Core\Stubs\StubDataCollector;
use App\Core\Stubs\StubSaver;
use App\Core\Stubs\StubStore;

class StubMake extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'stub:make
                            {--S|stub= : The name of the stub to make}
                            {--F|feature= : The id of the feature}';

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

        $compiledStubs = $stubCreator->create($stub);

        StubSaver::in(WorkingDirectory::fromPath(
            Filesystem::append($workingDirectory->path(), $stub->getDefaultLocation())
        ))->saveAll($compiledStubs);

    }

}
