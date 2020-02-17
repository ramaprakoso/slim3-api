<?php
return [
    'settings' => [
        /*
         * environment setting
         */
        'environment' => 'development',                 // development / production

        /*
         * slim setting
         */
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails'               => true,

        /*
         * database setting
         */
        // 'db_core' => [
        //     'driver'    => 'mysql',
        //     'host'      => 'slim-v3-db-server',
        //     'port'      => '3306',
        //     'database'  => 'dbslim',
        //     'username'  => 'dbusername',
        //     'password'  => 'dbP@ssw0rd',
        //     'charset'   => 'utf8',
        //     'collation' => 'utf8_unicode_sci',
        //     'prefix'    => '',
        // ],
        'db_test' => [
            'driver'    => 'mysql',
            'host'      => 'slim-v3-db-server',
            'port'      => '3306',
            'database'  => 'dbslim',
            'username'  => 'dbusername',
            'password'  => 'dbP@ssw0rd',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],

        /*
         * jwt setting
         */
        'jwt' => [
            'issuer'        => 'http://slim.work.local/',
            'audience'      => '*',
            'expiration'    => 86400, // 3600 = 1 hour / 86400 = 1 day
        ],

        'redis' => [
            'scheme'   => 'tcp',
            'host'     => '192.168.105.52',
            'port'     => 12399,
        ],

        /*
         * email sender
         */
        'email' => [
            'fullname' => 'eRecruitment Digius',
            'email' => 'no-reply@digius.id',
        ],

        /*
         * maingun setting
         */
        'maingun' => [
            'domain'        => 'digius.id',
            'key'           => '',
        ],

        /*
         * authorize domain client
         */
        'client_domains' => [
            '127.0.0.1',
            'localhost'
        ],

        /*
         * password hash algorithm
         * PASSWORD_DEFAULT > version php 5.0
         * PASSWORD_ARGON2I > version php 7.2
         */

    ],
];
