<?php

namespace App\Core\Stubs;

use App\Core\Helpers\IO\IO;
use App\Core\Stubs\Entities\Stub;

class StubDataCollector
{

    public function collect(Stub $stub, array $data = []): array
    {
        // TODO pay attention to the type of the replacement
        // TODO allow nesting, only ask this if this is true.
        // Maybe would be best to have one callback that can ask everything, rather than trying to do it ourselves?

        $data = [];
        foreach($stub->getStubFiles() as $stubFile) {
            foreach($stubFile->getReplacements() as $replacement) {
                if(!array_key_exists($replacement->getVariableName(), $data)) {
                    $data[$replacement->getVariableName()] = IO::ask($replacement->getQuestion(), $replacement->getDefault());
                }
            }
        }

        return $data;
        // Get the variables we actually need. Ideally, each one needs a type, variable name, default, question string (to show the user to ask for it)
        // Ensure each one has a value, either through the passed in data, the default or the question string.
        //
//        return ['extraRoute' => true, 'thisIsTheExtraRoute' => 'Another test, more dynamic though :)'];
        /*
         * Want to be able to
         * - Handle defaults. If default set, use that if not given and don't even ask the user.
         * - Override this behaviour if the user asks to be always asked.
         */
    }

}
