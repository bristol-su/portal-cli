<?php

return [
    'default' => 'atlas',
    'disks' => [
        'atlas' => [
            'driver' => 'atlas'
        ],
        'config' => [
            'driver' => 'local',
            'root' => $_SERVER['HOME'] . '/.atlas-cli'
        ]
    ]
];
