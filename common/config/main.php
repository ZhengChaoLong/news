<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    	'authManager' => [
    			'class' =>'yii\rbac\DbManager',
    	],
    ],
    'aliases' => [
        'staticWebPath' => 'http://localhost:9999',
        'frontendWebPath' => ''
    ],
    'name' => '新闻网站',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
];
