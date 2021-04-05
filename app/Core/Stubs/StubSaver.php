<?php

namespace App\Core\Stubs;

use App\Core\Helpers\Storage\Filesystem;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;
use App\Core\Stubs\Entities\CompiledStub;

class StubSaver
{

    /**
     * @var WorkingDirectory
     */
    private WorkingDirectory $workingDirectory;

    public function __construct(WorkingDirectory $workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }

    /**
     * @param CompiledStub[] $stubFiles
     */
    public function saveAll(array $stubFiles)
    {
        foreach($stubFiles as $stubFile) {
            $this->save($stubFile);
        }
    }

    public function save(CompiledStub $stubFile)
    {
        // Get the path to save in
        if($stubFile->getStubFile()->getLocation() !== null) {
            $path = Filesystem::append($this->workingDirectory->path(), $stubFile->getStubFile()->getLocation(), $stubFile->getStubFile()->getFileName());
        } else {
            $path = Filesystem::append($this->workingDirectory->path(), $stubFile->getStubFile()->getFileName());
        }

        // Make the directory
        $directory = dirname($path);
        if(!Filesystem::create()->exists($directory)) {
            Filesystem::create()->mkdir($directory);
        }

        // Save
        file_put_contents($path, $stubFile->getContent());
    }

    public static function in(WorkingDirectory $workingDirectory): StubSaver
    {
        return new static($workingDirectory);
    }

}
