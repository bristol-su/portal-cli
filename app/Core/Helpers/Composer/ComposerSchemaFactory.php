<?php

namespace App\Core\Helpers\Composer;

use App\Core\Helpers\Composer\Schema\Schema\AuthorSchema;
use App\Core\Helpers\Composer\Schema\Schema\AutoloadSchema;
use App\Core\Helpers\Composer\Schema\Schema\ComposerSchema;
use App\Core\Helpers\Composer\Schema\Schema\FundingSchema;
use App\Core\Helpers\Composer\Schema\Schema\NamespaceAutoloadSchema;
use App\Core\Helpers\Composer\Schema\Schema\PackageSchema;
use App\Core\Helpers\Composer\Schema\Schema\SuggestedPackageSchema;
use App\Core\Helpers\Composer\Schema\Schema\SupportSchema;
use Carbon\Carbon;

class ComposerSchemaFactory
{
    const NO_DEFAULT = 'nodefault';

    public array $schema;

    public function create(array $schema): ComposerSchema
    {
        $this->schema = $schema;

        $composerSchema = new ComposerSchema(
            $this->get('name'),
            $this->get('description', null),
            $this->get('version', null),
            $this->get('type', 'library'),
            $this->get('keywords', []),
            $this->get('homepage', null),
            $this->get('readme', null),
            (array_key_exists('time', $schema) ? Carbon::parse($schema['time']) : null),
            $this->get('license', null),
            $this->getAuthors($this->get('authors', [])),
            $this->getSupport($this->get('support', [])),
            $this->getFunding($this->get('funding', [])),
            $this->getPackages($this->get('require', [])),
            $this->getPackages($this->get('require-dev', [])),
            $this->getPackages($this->get('conflict', [])),
            $this->getPackages($this->get('replace', [])),
            $this->getPackages($this->get('provide', [])),
            $this->getSuggestedPackages($this->get('suggest', [])),
            $this->getAutoload($this->get('autoload', [])),
            $this->getAutoload($this->get('autoload-dev', [])),
            $this->get('include-path', []),
            $this->get('target-dir', null),
            $this->get('minimum-stability', null),
            $this->get('prefer-stable', false),
            $this->get('repositories', []),
            $this->get('config', []),
            $this->get('scripts', []),
            $this->get('extra', []),
            $this->get('bin', []),
            $this->get('archive', null),
            $this->get('abandoned', false),
            $this->get('non-feature-branches', []),
        );

        return $composerSchema;
    }

    private function get(string $key, $default = self::NO_DEFAULT)
    {
        $value = data_get($this->schema, $key, $default);
        if($value === self::NO_DEFAULT) {
            throw new \Exception(
                sprintf('Key %s was not found in composer', $key)
            );
        }
        return $value;
    }

    /**
     * @param array $authors
     * @return array|AuthorSchema[]
     */
    private function getAuthors($authors = []): array
    {
        $authorModels = [];
        foreach($authors as $author) {
            $authorModels[] = new AuthorSchema(
                data_get($author, 'name', null),
                data_get($author, 'email', null),
                data_get($author, 'homepage', null),
                data_get($author, 'role', null),
            );
        }
        return $authorModels;
    }

    private function getSupport(array $support): SupportSchema
    {
        return new SupportSchema(
            data_get($support, 'email', null),
            data_get($support, 'issues', null),
            data_get($support, 'forum', null),
            data_get($support, 'wiki', null),
            data_get($support, 'irc', null),
            data_get($support, 'source', null),
            data_get($support, 'docs', null),
            data_get($support, 'rss', null),
            data_get($support, 'chat', null),
        );
    }

    /**
     * @param array $finding
     * @return array|FundingSchema
     */
    private function getFunding(array $funding): array
    {
        $fundingSchema = [];
        foreach($funding as $fundingMethod) {
            $fundingSchema[] = new FundingSchema(
                data_get($fundingMethod, 'type', null),
                data_get($fundingMethod, 'url', null)
            );
        }
        return $fundingSchema;
    }

    /**
     * @param array $packages
     * @return array|PackageSchema[]
     */
    private function getPackages(array $packages): array
    {
        $packageSchema = [];
        foreach($packages as $name => $version) {
            $packageSchema[] = new PackageSchema(
                $name,
                $version
            );
        }
        return $packageSchema;
    }

    /**
     * @param array $packages
     * @return array|SuggestedPackageSchema[]
     */
    private function getSuggestedPackages(array $packages): array
    {
        $packageSchema = [];
        foreach($packages as $name => $description) {
            $packageSchema[] = new SuggestedPackageSchema(
                $name,
                $description
            );
        }
        return $packageSchema;
    }

    /**
     * @param array $autoload
     * @return AutoloadSchema
     */
    private function getAutoload(array $autoload): AutoloadSchema
    {
        return new AutoloadSchema(
            $this->getNamespaceAutoload(data_get($autoload, 'psr-4', [])),
            $this->getNamespaceAutoload(data_get($autoload, 'psr-0', [])),
            data_get($autoload, 'classmap', []),
            data_get($autoload, 'files', []),
            data_get($autoload, 'exclude-from-classmap', [])
        );
    }

    /**
     * @param array $namepsaceAutoload
     * @return array|NamespaceAutoloadSchema[]
     */
    private function getNamespaceAutoload(array $namespaceAutoload): array
    {
        $namespaces = [];
        foreach($namespaceAutoload as $namespace => $paths) {
            $namespaces[] = new NamespaceAutoloadSchema(
                $namespace,
                $paths
            );
        }
        return $namespaces;
    }

}
