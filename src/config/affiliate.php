<?php
use modava\affiliate\components\MyErrorHandler;

$config = [
    'defaultRoute' => 'affiliate/index',
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'aliases' => [
        '@affiliateweb' => '@modava/affiliate/web',
    ],
    'components' => [
        'errorHandler' => [
            'class' => MyErrorHandler::class,
        ],
    ],
];

return $config;
