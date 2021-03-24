<?php

namespace App\Commands;

use App\Core\Contracts\Feature\FeatureRepository;
use App\Core\Contracts\Instance\InstanceFactory;
use App\Core\Contracts\Site\SiteRepository;
use App\Core\Feature\Feature;
use App\Core\Instance\Instance;
use App\Core\Site\Site;
use Illuminate\Console\Scheduling\Schedule;
use App\Core\Contracts\Command;

class FeatureList extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'feature:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all instances of Atlas currently installed.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(FeatureRepository $featureRepository)
    {
        $features = $featureRepository->all();

        $this->table(
            ['ID', 'Name', 'Description', 'Type', 'Site'],
            $features->map(function(Feature $feature) {
                return [
                    $feature->getId(),
                    $feature->getName(),
                    $feature->getDescription(),
                    $feature->getType(),
                    $feature->getSite()->getName()
                ];
            })
        );
    }
}
