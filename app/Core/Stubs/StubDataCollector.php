<?php

namespace App\Core\Stubs;

use App\Core\Helpers\IO\IO;
use App\Core\Stubs\Entities\CollectedStubData;
use App\Core\Stubs\Entities\Stub;

class StubDataCollector
{

    public function collect(Stub $stub, array $data = []): CollectedStubData
    {
        $data = [];
        $stubFiles = [];
        foreach($stub->getStubFiles() as $stubFile) {
            if($stubFile->showIf($data)) {
                $stubFiles[] = $stubFile;
            } else {
                continue;
            }
            foreach($stubFile->getReplacements() as $replacement) {
                if(!array_key_exists($replacement->getVariableName(), $data)) {
                    $data = $replacement->appendData($data);
                }
            }
        }

        return (new CollectedStubData())
            ->setStubFiles($stubFiles)
            ->setData($data);
    }

}
