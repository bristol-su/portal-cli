<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            // TODO Refactor
            'root' => '/home/toby/.atlas-cli/work',
        ],
    ],
];
