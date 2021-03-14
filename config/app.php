<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => 'Atlas',

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | This value determines the "version" your application is currently running
    | in. You may want to follow the "Semantic Versioning" - Given a version
    | number MAJOR.MINOR.PATCH when an update happens: https://semver.org.
    |
    */

    'version' => app('git.version'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. This can be overridden using
    | the global command line "--env" option when calling commands.
    |
    */

    'env' => 'development',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        App\Providers\AppServiceProvider::class,
    ],

    'cms-url' => 'git@github.com:ElbowSpaceUK/AtlasCMS-Laravel-Template',

    'setup' => [
        'steps' => [
            \App\Core\Setup\Steps\CreateWorkingDirectory::class,
            \App\Core\Setup\Steps\CreateDatabase::class,
            \App\Core\Setup\Steps\MigrateDatabase::class,
            \App\Core\Setup\Steps\SetProjectDirectory::class
        ]
    ],

    'install' => [
        'cms' => [
            'tasks' => [
                \App\Core\Instance\Install\Tasks\CMS\CloneGitRepository::class,
                \App\Core\Instance\Install\Tasks\CMS\InstallComposerDependencies::class,
                \App\Core\Instance\Install\Tasks\CMS\CreateMainEnvironmentFile::class
            ],
            'ports' => [
                'HTTP' => 'APP_PORT',
                'database' => 'FORWARD_DB_PORT',
                'mail' => 'FORWARD_MAILHOG_PORT',
                'mail dashboard' => 'FORWARD_MAILHOG_DASHBOARD_PORT',
                'redis' => 'FORWARD_REDIS_PORT',
                'selenium' => 'FORWARD_SELENIUM_PORT',
                'test database' => 'FORWARD_DB_TESTING_PORT',
            ]
        ]
    ]

];
