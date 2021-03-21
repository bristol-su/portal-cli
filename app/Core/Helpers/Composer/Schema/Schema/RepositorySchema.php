<?php


namespace App\Core\Helpers\Composer\Schema\Schema;


class RepositorySchema
{

    private string $type;

    private string $url;

    private array $options;

    private PackageRepositorySchema $package;

    /**
     * @return PackageRepositorySchema
     */
    public function getPackage(): PackageRepositorySchema
    {
        return $this->package;
    }

    /**
     * @param PackageRepositorySchema $package
     */
    public function setPackage(PackageRepositorySchema $package): void
    {
        $this->package = $package;
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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        throw new \Exception('Don\'t forget that this schema may also be loaded with a key rather than an array. A builder must take that into account');
        $this->options = $options;
    }


}
