<?php

namespace App\Core\Stubs;

class StubCreator
{

    /**
     * @var StubFileCompiler
     */
    private StubFileCompiler $compiler;

    public function __construct(StubFileCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    public function createFrom(Entities\Stub $stub, array $data = []): array
    {
        $compiled = [];
        // TODO condition to check if the file is even needed
        foreach($stub->getStubFiles() as $stubFile) {
            $compiled[] = $this->compiler->compile($stubFile, $data);
        }
        return $compiled;
    }
}
