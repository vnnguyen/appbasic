<?php
$params = [];
$params = array_merge(
    $params,
    require(__DIR__ . '/params.php')
);
// Yii::setAlias('@webroot', 'http://localhost/appbasic');
$config = [
    'id' => 'amica-client',
    'name' => 'Amica Travel Client Page',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timezone' => 'UTC',

    'on beforeAction' => function ($event) {
            Yii::$app->language = (Yii::$app->session->has('language'))? Yii::$app->session->get('language'): 'en';
    },
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        // 'i18n' => [
        //     'translations' => [
        //         '*' => [
        //             'class' => 'yii\i18n\PhpMessageSource',
        //             //'basePath' => '@app/messages',
        //             'sourceLanguage' => 'en',
        //             'fileMap' => [
        //                 'app' => 'app.php',
        //                 'app/error' => 'error.php',
        //             ],
        //         ],
        //     ],
        // ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'request' => [
            'enableCsrfValidation'=>false,
            'cookieValidationKey' => '5tWb^Y*N7ujm',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // 'class' => 'codemix\localeurls\UrlManager',
            // 'languages' => ['en', 'fr', 'vi'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'post1/update/<id:\d+>/<status>' => 'post1/update',
                'post1/view/<id:\d+>/<status>' => 'post1/view',
                'tag/list/<query>' => 'tag/list',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                ///////////////////////////////
                ''=>'default/index',

                'tours'=>'default/tours',
                'tours/<id:\d+>'=>'default/tours-view',

                'account'=>'default/account',

                'b-<token>/itinerary'=>'default/booking-itinerary',

                'b-<token>/registration'=>'default/booking-registration',
                'b-<token>/registration/travellers'=>'default/booking-registration-travellers',
                'b-<token>/registration/visas'=>'default/booking-registration-visas',
                'b-<token>/registration/flights'=>'default/booking-registration-flights',
                'b-<token>/registration/rooms'=>'default/booking-registration-rooms',
                'b-<token>/registration/submit'=>'default/booking-registration-submit',

                'help'=>'default/help',
                'help/<a>'=>'default/help',
                'me'=>'default/me',
                'me/<a>'=>'default/me',

                'login'=>'login/index',
                'logout'=>'login/logout',
                'login/<a>'=>'login/<a>',

                'select/lang/<lang:en|fr|vi>'=>'default/select-lang',
                //                FOR IMS
                'site/imsprint/<id>/<code>' => 'site/imsprint',
                'site/imsprint-en/<id>/<code>' => 'site/ims-print-en',
                 'site/imsprint-b2b/<id>/<code>' => 'site/imsprint-b2b',
                'site/imsprint-b2b-en/<id>/<code>' => 'site/imsprint-b2b-en',
            ],
        ],
        'view'=>[
            'theme' => [
                'pathMap' => [
                        '@app/views' =>[
                                '@app/themes/mytheme',
                                '@app/themes/yourtheme'
                                ]
                            ],
                'baseUrl' => '@web/../themes/mytheme',
            ],
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'nguyen.nv@amica-travel.com',
                'password' => 'pass',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'mailqueue' => array(
            'class' => 'yiicod\mailqueue\MailQueue',
            'modelMap' => array(
                'MailQueue' => array(
                    'alias' => 'yiicod\mailqueue\models\MailQueueModel',
                    'class' => 'yiicod\mailqueue\models\MailQueueModel',
                    'fieldFrom' => 'from',
                    'fieldTo' => 'to',
                    'fieldSubject' => 'subject',
                    'fieldBody' => 'body',
                    'fieldAttachs' => 'attachs',
                    'fieldStatus' => 'status',
                    'status' => array(
                        'send' => 1,
                        'unsend' => 0,
                        'failed' => 0,
                    )
                )
            ),
            'mailer' => 'mail',
            'components' => array(
                'mailQueue' => array(
                    'class' => 'yiicod\mailqueue\components\MailQueue',
                    'afterSendDelete' => false,
                ),
            ),
        ),
    ],
    'params' => $params,

    // 'preload' => array('mailqueue'),
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
