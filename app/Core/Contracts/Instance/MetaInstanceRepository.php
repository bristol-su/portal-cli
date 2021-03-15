<?php

namespace App\Core\Contracts\Instance;

use Illuminate\Database\Eloquent\Collection;

interface MetaInstanceRepository
{

    public function all(): Collection;

}
