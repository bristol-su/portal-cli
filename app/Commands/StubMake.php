<?php

namespace App\Commands;

use App\Core\Contracts\Command;
use App\Core\Stubs\Entities\Stub;
use App\Core\Stubs\Entities\StubFile;
use App\Core\Stubs\StubStore;

class StubMake extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'stub:make';

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
    public function handle(StubStore $stubStore)
    {
        $stubFile1 = (new StubFile())->setStubPath(__DIR__ . '/../../stubs/test/routes.api.php.stub')->setLocation('api');
        $stubFile2 = (new StubFile())->setStubPath(__DIR__ . '/../../stubs/test/routes.web.php.stub')->setLocation('web');

        $stub = new Stub();
        $stub->setName('test')->setDescription('A test stub')->setStubFiles([$stubFile1, $stubFile2])->setDefaultLocation('app/StubTest');
        $stubStore->registerStub($stub);


    }

}
