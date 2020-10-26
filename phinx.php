<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'sqlite',
            'host' => '',
            'name' => 'database/database',
            'user' => '',
            'pass' => '',
            'port' => '',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
