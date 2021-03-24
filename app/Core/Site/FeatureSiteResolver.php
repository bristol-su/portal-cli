<?php

namespace App\Core\Site;

use App\Core\Contracts\Feature\FeatureResolver;
use App\Core\Contracts\Helpers\Settings\SettingRepository;
use App\Core\Contracts\Site\SiteResolver;

class FeatureSiteResolver implements SiteResolver
{

    /**
     * @var SiteResolver
     */
    private SiteResolver $siteResolver;
    /**
     * @var FeatureResolver
     */
    private FeatureResolver $featureResolver;

    public function __construct(FeatureResolver $featureResolver, SiteResolver $siteResolver)
    {
        $this->siteResolver = $siteResolver;
        $this->featureResolver = $featureResolver;
    }

    public function setSite(Site $site): void
    {
        $this->siteResolver->setSite($site);
    }

    public function getSite(): Site
    {
        if($this->featureResolver->hasFeature()) {
            return $this->featureResolver->getFeature()->site;
        }
        return $this->siteResolver->getSite();
    }

    public function hasSite(): bool
    {
        return $this->featureResolver->hasFeature() || $this->siteResolver->hasSite();
    }

}
