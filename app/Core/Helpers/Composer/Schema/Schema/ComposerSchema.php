<?php

namespace App\Core\Helpers\Composer\Schema\Schema;

use Carbon\Carbon;

class ComposerSchema
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $description;

    /**
     * @var string
     */
    private string $version;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var array|string[]
     */
    private array $keywords;

    /**
     * @var string
     */
    private string $homepage;

    /**
     * @var string
     */
    private string $readme;

    /**
     * @var Carbon
     */
    private Carbon $time;

    /**
     * @var string|array
     */
    private $licence;

    /**
     * @var array|AuthorSchema[]
     */
    private array $authors;

    /**
     * @var
     */
    private SupportSchema $support;

    /**
     * @var array|FundingSchema[]
     */
    private array $funding;

    /**
     * @var array|PackageSchema[]
     */
    private array $require;

    /**
     * @var array|PackageSchema[]
     */
    private array $requireDev;

    /**
     * @var array|PackageSchema[]
     */
    private array $conflict;

    /**
     * @var array|PackageSchema[]
     */
    private array $replace;

    /**
     * @var array|PackageSchema[]
     */
    private array $provide;

    /**
     * @var array|SuggestedPackageSchema[]
     */
    private array $suggest;

    /**
     * @var AutoloadSchema
     */
    private AutoloadSchema $autoload;

    /**
     * @var AutoloadSchema
     */
    private AutoloadSchema $autoloadDev;

    /**
     * @var array
     */
    private array $includePath;

    private string $targetDir;

    private string $minimumStability;

    private bool $preferStable;

    /**
     * @var array|RepositorySchema[]
     */
    private array $repositories;

    /**
     * @var array
     */
    private array $config;

    /**
     * @var array|ScriptSchema[]
     */
    private array $scripts;

    private array $extra;

    /**
     * @var array|string[]
     */
    private array $bin;

    /**
     * @var string
     */
    private string $archive;

    /**
     * @var bool|string
     */
    private $abandomed;

    private array $nonFeatureBranches;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array|string[]
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * @param array|string[] $keywords
     */
    public function setKeywords(array $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @return string
     */
    public function getHomepage(): string
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     */
    public function setHomepage(string $homepage): void
    {
        $this->homepage = $homepage;
    }

    /**
     * @return string
     */
    public function getReadme(): string
    {
        return $this->readme;
    }

    /**
     * @param string $readme
     */
    public function setReadme(string $readme): void
    {
        $this->readme = $readme;
    }

    /**
     * @return Carbon
     */
    public function getTime(): Carbon
    {
        return $this->time;
    }

    /**
     * @param Carbon $time
     */
    public function setTime(Carbon $time): void
    {
        $this->time = $time;
    }

    /**
     * @return array|string
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * @param array|string $licence
     */
    public function setLicence($licence): void
    {
        $this->licence = $licence;
    }

    /**
     * @return AuthorSchema[]|array
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param AuthorSchema[]|array $authors
     */
    public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }

    /**
     * @return mixed
     */
    public function getSupport(): SupportSchema
    {
        return $this->support;
    }

    /**
     * @param mixed $support
     */
    public function setSupport(SupportSchema $support): void
    {
        $this->support = $support;
    }

    /**
     * @return FundingSchema[]|array
     */
    public function getFunding(): array
    {
        return $this->funding;
    }

    /**
     * @param FundingSchema[]|array $funding
     */
    public function setFunding(array $funding): void
    {
        $this->funding = $funding;
    }

    /**
     * @return PackageSchema[]|array
     */
    public function getRequire(): array
    {
        return $this->require;
    }

    /**
     * @param PackageSchema[]|array $require
     */
    public function setRequire(array $require): void
    {
        $this->require = $require;
    }

    /**
     * @return PackageSchema[]|array
     */
    public function getRequireDev(): array
    {
        return $this->requireDev;
    }

    /**
     * @param PackageSchema[]|array $requireDev
     */
    public function setRequireDev(array $requireDev): void
    {
        $this->requireDev = $requireDev;
    }

    /**
     * @return PackageSchema[]|array
     */
    public function getConflict(): array
    {
        return $this->conflict;
    }

    /**
     * @param PackageSchema[]|array $conflict
     */
    public function setConflict(array $conflict): void
    {
        $this->conflict = $conflict;
    }

    /**
     * @return PackageSchema[]|array
     */
    public function getReplace(): array
    {
        return $this->replace;
    }

    /**
     * @param PackageSchema[]|array $replace
     */
    public function setReplace(array $replace): void
    {
        $this->replace = $replace;
    }

    /**
     * @return PackageSchema[]|array
     */
    public function getProvide(): array
    {
        return $this->provide;
    }

    /**
     * @param PackageSchema[]|array $provide
     */
    public function setProvide(array $provide): void
    {
        $this->provide = $provide;
    }

    /**
     * @return SuggestedPackageSchema[]|array
     */
    public function getSuggest(): array
    {
        return $this->suggest;
    }

    /**
     * @param SuggestedPackageSchema[]|array $suggest
     */
    public function setSuggest(array $suggest): void
    {
        $this->suggest = $suggest;
    }

    /**
     * @return AutoloadSchema
     */
    public function getAutoload(): AutoloadSchema
    {
        return $this->autoload;
    }

    /**
     * @param AutoloadSchema $autoload
     */
    public function setAutoload(AutoloadSchema $autoload): void
    {
        $this->autoload = $autoload;
    }

    /**
     * @return AutoloadSchema
     */
    public function getAutoloadDev(): AutoloadSchema
    {
        return $this->autoloadDev;
    }

    /**
     * @param AutoloadSchema $autoloadDev
     */
    public function setAutoloadDev(AutoloadSchema $autoloadDev): void
    {
        $this->autoloadDev = $autoloadDev;
    }

    /**
     * @return array
     */
    public function getIncludePath(): array
    {
        return $this->includePath;
    }

    /**
     * @param array $includePath
     */
    public function setIncludePath(array $includePath): void
    {
        $this->includePath = $includePath;
    }

    /**
     * @return string
     */
    public function getTargetDir(): string
    {
        return $this->targetDir;
    }

    /**
     * @param string $targetDir
     */
    public function setTargetDir(string $targetDir): void
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @return string
     */
    public function getMinimumStability(): string
    {
        return $this->minimumStability;
    }

    /**
     * @param string $minimumStability
     */
    public function setMinimumStability(string $minimumStability): void
    {
        $this->minimumStability = $minimumStability;
    }

    /**
     * @return bool
     */
    public function isPreferStable(): bool
    {
        return $this->preferStable;
    }

    /**
     * @param bool $preferStable
     */
    public function setPreferStable(bool $preferStable): void
    {
        $this->preferStable = $preferStable;
    }

    /**
     * @return RepositorySchema[]|array
     */
    public function getRepositories(): array
    {
        return $this->repositories;
    }

    /**
     * @param RepositorySchema[]|array $repositories
     */
    public function setRepositories(array $repositories): void
    {
        $this->repositories = $repositories;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * @return ScriptSchema[]|array
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * @param ScriptSchema[]|array $scripts
     */
    public function setScripts(array $scripts): void
    {
        $this->scripts = $scripts;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     */
    public function setExtra(array $extra): void
    {
        $this->extra = $extra;
    }

    /**
     * @return array|string[]
     */
    public function getBin(): array
    {
        return $this->bin;
    }

    /**
     * @param array|string[] $bin
     */
    public function setBin(array $bin): void
    {
        $this->bin = $bin;
    }

    /**
     * @return string
     */
    public function getArchive(): string
    {
        return $this->archive;
    }

    /**
     * @param string $archive
     */
    public function setArchive(string $archive): void
    {
        $this->archive = $archive;
    }

    /**
     * @return bool|string
     */
    public function getAbandomed()
    {
        return $this->abandomed;
    }

    /**
     * @param bool|string $abandomed
     */
    public function setAbandomed($abandomed): void
    {
        $this->abandomed = $abandomed;
    }

    /**
     * @return array
     */
    public function getNonFeatureBranches(): array
    {
        return $this->nonFeatureBranches;
    }

    /**
     * @param array $nonFeatureBranches
     */
    public function setNonFeatureBranches(array $nonFeatureBranches): void
    {
        $this->nonFeatureBranches = $nonFeatureBranches;
    }

}
