<?php

namespace App\Core\Helpers\Storage;

use App\Core\Helpers\Settings\Settings;
use App\Core\Helpers\WorkingDirectory\ConfigDirectoryLocator;
use Illuminate\Support\Str;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class Filesystem
{

    // TODO Decorator around filesystem to append on the working directory, so we can just work in the working directory.

    public static function create(): SymfonyFilesystem
    {
        return new SymfonyFilesystem();
    }

    public static function database(string $append = ''): string
    {
        return static::append(
            ConfigDirectoryLocator::locate(),
            $append
        );
    }

    public static function project(string $append = ''): string
    {
        if(!Settings::has('project-directory')) {
            throw new \Exception('Please set a project directory');
        }
        return static::append(
            Settings::get('project-directory'),
            $append
        );
    }

    public static function append(string $root, string $path)
    {
        return (
            Str::startsWith($path, DIRECTORY_SEPARATOR)
                ? $root . $path
                : $root . DIRECTORY_SEPARATOR . $path
        );
    }

    public static function read(string $path)
    {
        return file_get_contents($path);
    }

}
