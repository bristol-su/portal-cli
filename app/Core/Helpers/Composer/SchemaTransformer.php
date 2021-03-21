<?php


namespace App\Core\Helpers\Composer;


use App\Core\Helpers\Composer\Schema\Schema\ComposerSchema;
use App\Core\Helpers\WorkingDirectory\WorkingDirectory;

class SchemaTransformer
{

    /**
     * @var WorkingDirectory
     */
    private WorkingDirectory $workingDirectory;
    private string $filename;
    private array $operations = [];


    public function __construct(WorkingDirectory $workingDirectory, string $filename = 'composer.json')
    {
        $this->workingDirectory = $workingDirectory;
        $this->filename = $filename;
    }

    public static function for(WorkingDirectory $workingDirectory, string $filename = 'composer.json'): SchemaTransformer
    {
        return new static($workingDirectory, $filename);
    }

    public function require(string $name, string $version): SchemaTransformer
    {
        return $this->addOperation('require', [
            'name' => $name, 'version' => $version
        ]);
    }

    // TODO add all operations here. Just helpers to let us use typehinting for core ones

    public function addOperation(string $operation, array $arguments)
    {
        $this->operations[] = [
            'operation' => $operation,
            'arguments' => $arguments
        ];

        return $this;
    }

    public function transform(ComposerSchema $composerSchema)
    {

        // TODO Implement
        /**
         * Go through each of the operations. Resolve them using the manager, taking into account the __construct parameters being in arguments.
         * Run process() or w/e and pass it the schema
         */

    }

}
