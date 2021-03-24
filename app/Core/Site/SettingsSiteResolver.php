<?php

namespace App\Core\Site;

use App\Core\Contracts\Helpers\Settings\SettingRepository;
use App\Core\Contracts\Site\SiteResolver;

class SettingsSiteResolver implements SiteResolver
{

    /**
     * @var SettingRepository
     */
    private SettingRepository $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function setSite(Site $site): void
    {
        $this->settingRepository->set('current-site', $site->getId());
    }

    public function getSite(): Site
    {
        if($this->hasSite()) {
            return Site::findOrFail(
                $this->settingRepository->get('current-site')
            );
        }
        throw new \Exception('No site is set');
    }

    public function hasSite(): bool
    {
        return $this->settingRepository->has('current-site');
    }

}
