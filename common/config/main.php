<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'dataFormatter' => [
            'class' => 'common\components\DataFormatterComponent',
        ],
    ],
];
