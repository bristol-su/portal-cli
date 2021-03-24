<?php

namespace App\Core\Contracts;

use App\Core\Contracts\Feature\FeatureRepository;
use App\Core\Contracts\Feature\FeatureResolver;
use App\Core\Contracts\Site\SiteRepository;
use App\Core\Contracts\Site\SiteResolver;
use App\Core\Feature\Feature;
use App\Core\Helpers\IO\IO;
use App\Core\Helpers\IO\Proxy;
use App\Core\Site\Site;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends \LaravelZero\Framework\Commands\Command
{

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        if(! $output instanceof OutputStyle) {
            throw new \Exception(sprintf('The output interface must be of type %s, %s given.', OutputStyle::class, get_class($output)));
        }
        $this->app->instance(Proxy::class, new Proxy($output));
    }

    protected function getOrAskForOption(string $option, \Closure $ask, \Closure $validator, bool $useOption = true)
    {
        if($useOption && $this->option($option)) {
            $value = $this->option($option);
        } else {
            $value = $ask();
        }

        if(!$validator($value)) {
            IO::error(sprintf('[%s] is not a valid %s', $value, $option));
            return $this->getOrAskForOption($option, $ask, $validator, false);
        }

        return $value;
    }

    public function getSite(string $message = 'Which site would you like to perform the action against?', \Closure $siteFilter = null, bool $withoutDefault = false): Site
    {
        $siteRepository = app(SiteRepository::class);
        if($siteRepository->count() === 0) {
            throw new \Exception('No sites are available');
        }

        if(!$withoutDefault) {
            $siteResolver = app(SiteResolver::class);
            if($siteResolver->hasSite()) {
                return $siteResolver->getSite();
            }
        }

        $siteOptions = $siteRepository->all();
        if($siteFilter !== null) {
            $siteOptions = $siteOptions->filter($siteFilter);
        }

        $siteId = $this->option('site');
        if($siteId === null) {
            $prefixedSiteId = $this->choice(
                $message,
                $siteOptions->mapWithKeys(fn(Site $site) => [sprintf('site-%u', $site->getId()) => $site->getName()])->toArray()
            );
            $siteId = (int) Str::substr($prefixedSiteId, 5);
        }

        if(!$siteId) {
            throw new \Exception('The site could not be found');
        }

        return $siteRepository->getById($siteId);
    }

    public function getFeature(string $message = 'Which feature would you like to perform the action against?', \Closure $featureFilter = null, bool $withoutDefault = false): Feature
    {
        $featureRepository = app(FeatureRepository::class);
        if($featureRepository->count() === 0) {
            throw new \Exception('No features are available');
        }

        if(!$withoutDefault) {
            $featureResolver = app(FeatureResolver::class);
            if($featureResolver->hasFeature()) {
                return $featureResolver->getFeature();
            }
        }

        $featureOptions = $featureRepository->all();
        if($featureFilter !== null) {
            $featureOptions = $featureOptions->filter($featureFilter);
        }

        $featureId = $this->option('feature');
        if($featureId === null) {
            $prefixedFeatureId = $this->choice(
                $message,
                $featureOptions->mapWithKeys(fn(Feature $feature) => [sprintf('feature-%u', $feature->getId()) => $feature->getName()])->toArray()
            );
            $featureId = (int) Str::substr($prefixedFeatureId, 8);
        }

        if(!$featureId) {
            throw new \Exception('The feature could not be found');
        }

        return $featureRepository->getById($featureId);
    }

}
