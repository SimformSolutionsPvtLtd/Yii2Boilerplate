<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=advanced_yii2',
            'username' => 'root',
            'password' => 'Root@123',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\symfonymailer\Mailer',
            'useFileTransport' => false, // Set this to false to send real emails
            'transport' => [
                'dsn' => 'smtp://[YOUR_USERNAME]:[YOUR_PASSWORD]@[YOUR_HOST]:587?debug=1',
                // 'options' => [
                //     'debug' => true, // Enable debug output
                // ],
            ],
        ],
    ],
];
