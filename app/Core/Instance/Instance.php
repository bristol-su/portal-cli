<?php

namespace App\Core\Instance;

use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

/**
 * @todo
 */
class Instance
{

    const STATUS_MISSING = 'missing';

    const STATUS_READY = 'ready';

    const STATUS_DOWN = 'down';

    /**
     * The meta data associated with the instance
     *
     * @var MetaInstance
     */
    private MetaInstance $metaInstance;

    /**
     * The working directory of the instance
     *
     * @var WorkingDirectory
     */
    private WorkingDirectory $workingDirectory;

    /**
     * The ID of the instance
     *
     * @var string
     */
    private string $instanceId;

    /**
     * The status of the instance
     *
     * @var string
     */
    private string $status;

    /**
     * @return MetaInstance
     */
    public function getMetaInstance(): MetaInstance
    {
        return $this->metaInstance;
    }

    /**
     * @param MetaInstance $metaInstance
     */
    public function setMetaInstance(MetaInstance $metaInstance): void
    {
        $this->metaInstance = $metaInstance;
    }

    /**
     * @throws \Exception If an instance is not registered
     * @return Instance
     */
    public static function current(): Instance
    {
        return app(\App\Core\Contracts\Instance\InstanceResolver::class)
            ->getInstance();
    }

    /**
     * @return string
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * @param string $instanceId
     */
    public function setInstanceId(string $instanceId): void
    {
        $this->instanceId = $instanceId;
    }

    /**
     * @return WorkingDirectory
     */
    public function getWorkingDirectory(): WorkingDirectory
    {
        return $this->workingDirectory;
    }

    /**
     * @param WorkingDirectory $workingDirectory
     */
    public function setWorkingDirectory(WorkingDirectory $workingDirectory): void
    {
        $this->workingDirectory = $workingDirectory;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getReadableStatus(): string
    {
        switch($this->getStatus()) {
            case static::STATUS_MISSING:
                return 'Project files missing';
                break;
            case static::STATUS_DOWN:
                return 'Down';
                break;
            case static::STATUS_READY:
                return 'Ready';
                break;
        }
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

}
