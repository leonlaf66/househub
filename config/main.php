<?php
return \yii\helpers\ArrayHelper::merge(get_fdn_etc('web'), [
    'id' => 'usleju-house',
    'basePath' => dirname(__DIR__),
    'layout'=>'@module/page/views/layouts/main.phtml',
    'defaultRoute'=>'home/default/index',
    'bootstrap'    => ['assetsAutoCompress'],
    'components' => [
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['en'=>'en-US', 'zh'=>'zh-CN'],
            'languageSessionKey' => 'language',
            'languageCookieName' => 'language',
            'languageCookieOptions' => [
                'domain' => ''
            ],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix'=>strpos($_SERVER["REQUEST_URI"], '/rets-photo-') === false ? '/' : '',
            'rules'=>[]
        ],
        'page' => [
            'class' => '\module\cms\components\Page',
            'name' => ['Usleju', '米乐居']
        ],
        'view'=>[
            'class'=>'module\cms\components\View',
            'defaultExtension'=>'phtml',
            'compressEnabled' => true,
            'renderers'=>[
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => ['yii\bootstrap']
                ],
                'blade' => [
                    'class' => '\cyneek\yii2\blade\ViewRenderer',
                    'cachePath' => '@runtime/blade_cache',
                ]
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'AulhaJkw74KJHBBq1JobpnXv90jLd8ba',
        ],
        'user' => [
            'identityClass' => '\module\customer\models\UserIdentity',
            'enableAutoLogin' => true,
            'loginUrl'=>''
        ],
        'authClientCollection' => [
           'class' => 'yii\authclient\Collection',
           'clients' => [
               'google' => [
                   'class' => 'yii\authclient\clients\GoogleOpenId'
               ],
               'facebook' => [
                   'class' => 'yii\authclient\clients\Facebook',
                   'clientId' => 'facebook_client_id',
                   'clientSecret' => 'facebook_client_secret',
               ],
           ],
        ],
        'assetsAutoCompress' => [
            'class' => '\module\cms\components\AssetsCompressComponent',
            'enabled'           => true,
            'jsCompress'        => true,
            'cssFileCompile'    => true,
            'jsFileCompile'     => true,
        ],
        'errorHandler' => [
            'errorAction'=>'page/index/error'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'modules'=>[
        'catalog'=>'module\catalog\Module',
        'blocks'=>'module\blocks\Module',
        'cms'=>'module\cms\Module',
        'comment'=>'module\comment\Module',
        'contact'=>'module\contact\Module',
        'core'=>'module\core\Module',
        'customer'=>'module\customer\Module',
        'email'=>'module\email\Module',
        'estate'=>'module\estate\Module',
        'indexer'=>'module\indexer\Module',
        'news'=>'module\news\Module',
        'page'=>'module\page\Module',
        'yellowpage'=>'module\yellowpage\Module',
        'gotour'=>'module\gotour\Module',
        'schooldistrict'=>'module\schooldistrict\Module',
        'translatemanager' => 'lajax\translatemanager\Module',
        'home'=>'module\home\Module',
    ],
    'aliases'=>[
        '@bower'=>APP_ROOT.'/vendor/bower',
        'module'=>APP_ROOT.'/app',
    ]
], include(__DIR__.'/local.php'));
