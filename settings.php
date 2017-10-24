<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Please replace your_* with your config for database
        'db' => [
            'pdo1' => [
                'dsn' => 'mysql:host=localhost;dbname=slim;charset=utf8',
                'usr' => 'root',
                'pwd' => 'pass'
            ],
            'pdo2' => [
                'dsn' => 'mysql:host=localhost;dbname=db2;charset=utf8',
                'usr' => 'root',
                'pwd' => 'pass'
            ]

        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/var/log/app-'.date('Y-m').'.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
