<?php

namespace App\Core\Stubs;

use App\Core\Stubs\Entities\Stub;

abstract class StubRegistrar
{

    private StubStore $stubStore;

    public function __construct(StubStore $stubStore)
    {
        $this->stubStore = $stubStore;
    }

    public function register(Stub $stub)
    {
        $this->stubStore->registerStub($stub);
    }

    public function new()
    {
        // TODO create a new stub model
    }

}
