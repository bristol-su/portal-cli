<?php

namespace App\Core\Feature;

use App\Core\Contracts\Feature\FeatureResolver;
use App\Core\Contracts\Helpers\Settings\SettingRepository;

class SettingsFeatureResolver implements FeatureResolver
{

    /**
     * @var SettingRepository
     */
    private SettingRepository $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function setFeature(Feature $feature): void
    {
        $this->settingRepository->set('current-feature', $feature->getId());
    }

    public function getFeature(): Feature
    {
        if($this->hasFeature()) {
            return Feature::findOrFail(
                $this->settingRepository->get('current-feature')
            );
        }
        throw new \Exception('No feature is set');
    }

    public function hasFeature(): bool
    {
        return $this->settingRepository->has('current-feature');
    }
}
