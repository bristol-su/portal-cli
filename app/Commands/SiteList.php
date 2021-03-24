<?php

namespace App\Commands;

use App\Core\Contracts\Instance\InstanceFactory;
use App\Core\Contracts\Site\SiteRepository;
use App\Core\Instance\Instance;
use App\Core\Site\Site;
use Illuminate\Console\Scheduling\Schedule;
use App\Core\Contracts\Command;

class SiteList extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'site:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all sites currently installed.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SiteRepository $siteRepository)
    {
        $sites = $siteRepository->all();

        $this->table(
            ['ID', 'Name', 'Description', 'Status', 'URL'],
            $sites->map(function(Site $site) {
                return [
                    $site->getId(),
                    $site->getName(),
                    $site->getDescription(),
                    $site->instance()->getStatus(),
                    $site->instance()->getUrl()
                ];
            })
        );
    }
}
