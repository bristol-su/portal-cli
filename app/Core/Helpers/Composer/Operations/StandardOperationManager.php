<?php

namespace App\Core\Helpers\Composer\Operations;

use App\Core\Helpers\Composer\Operations\Operations\AddRepository;
use App\Core\Helpers\Composer\Operations\Operations\ChangeDependencyVersion;
use App\Core\Helpers\Composer\Operations\Operations\Remove;
use App\Core\Helpers\Composer\Operations\Operations\RemoveRepository;
use App\Core\Helpers\Composer\Operations\Operations\RequireDev;
use App\Core\Helpers\Composer\Operations\Operations\RequirePackage;
use Illuminate\Support\Facades\Validator;

class StandardOperationManager extends OperationManager
{

    public function validateParameters(array $parameters, array $rules, array $messages = []): array
    {
        return Validator::make($parameters, $rules, $messages)
            ->validate();
    }

    public function createAddRepositoryOperation(array $parameters): AddRepository
    {
        $parameters = $this->validateParameters($parameters, [
            'type' => 'required',
            'url' => 'required',
            'options' => 'sometimes|nullable',
            'package' => 'sometimes|nullable'
        ]);
        return new AddRepository(
            $parameters['type'],
            $parameters['url'],
            data_get($parameters, 'options', []),
            data_get($parameters, 'package', null)
        );
    }

    public function createRemoveRepositoryOperation(array $parameters): RemoveRepository
    {
        $parameters = $this->validateParameters($parameters, [
            'type' => 'required',
            'url' => 'required',
            'options' => 'sometimes|nullable',
            'package' => 'sometimes|nullable'
        ]);
        return new RemoveRepository(
            $parameters['type'],
            $parameters['url'],
            data_get($parameters, 'options', []),
            data_get($parameters, 'package')
        );
    }

    public function createChangeDependencyVersionOperation(array $parameters): ChangeDependencyVersion
    {
        $parameters = $this->validateParameters($parameters, [
            'name' => 'required',
            'version' => 'required'
        ]);
        return new ChangeDependencyVersion(
            $parameters['name'],
            $parameters['version']
        );
    }

    public function createRemoveOperation(array $parameters): Remove
    {
        $parameters = $this->validateParameters($parameters, [
            'name' => 'required'
        ]);
        return new Remove(
            $parameters['name'],
        );
    }

    public function createRequireDevOperation(array $parameters): RequireDev
    {
        $parameters = $this->validateParameters($parameters, [
            'name' => 'required',
            'version' => 'sometimes|nullable'
        ]);
        return new RequireDev(
            $parameters['name'],
            data_get($parameters, 'version')
        );
    }

    public function createRequireOperation(array $parameters): RequirePackage
    {
        $parameters = $this->validateParameters($parameters, [
            'name' => 'required',
            'version' => 'sometimes|nullable'
        ]);
        return new RequirePackage(
            $parameters['name'],
            data_get($parameters, 'version')
        );
    }

}
