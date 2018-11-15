<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', ],
    'controllerNamespace' => 'app\commands',
    'components' => [

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // 'viewPath'         => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'nguyen.nv@amica-travel.com',
                'password' => 'app_gmail_9999',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'mailqueue' => [
            'class' => \yiicod\mailqueue\MailQueue::class,
            'modelMap' => [
                'mailQueue' => [
                    'class' => \yiicod\mailqueue\models\MailQueueModel::class,
                    // 'fieldFrom' => 'from',
                    // 'fieldTo' => 'to',
                    // 'fieldSubject' => 'subject',
                    // 'fieldBody' => 'body',
                    // 'fieldAttachs' => 'attachs',
                    // 'fieldStatus' => 'status',
                    // 'fieldPriority' => 'priority',
                    // // 'fieldCreatedDate' => 'createDate',
                    // // 'fieldUpdatedDate' => 'updateDate',
                    // 'status' => [
                    //     'send' => 1,
                    //     'unsend' => 0,
                    //     'failed' => 0,
                    // ]
                ],
            ],
            'commandMap' => [
                'mail-queue' => [
                    'class' => \yiicod\mailqueue\commands\WorkerCommand::class,
                ],
            ],
        ],

        'myNty' => [
            'class' => 'app\notifications\MyNotification',
        ]

    ],

    'modules' => [
        'notifications' => [
            'class' => 'webzop\notifications\Module',
            'channels' => [
                'screen' => [
                    'class' => 'webzop\notifications\channels\ScreenChannel',
                ],
                'email' => [
                    'class' => 'webzop\notifications\channels\EmailChannel',
                    'message' => [
                        'from' => 'example@email.com'//nguyen.nv@amica-travel.com
                    ],
                ],
                // 'voice' => [
                //     'class' => 'app\channels\VoiceChannel',
                // ],
            ],
        ],
        // 'admin' => [
        //     'class' => 'app\modules\admin\Module',
        // ],
    ],


    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
