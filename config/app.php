<?php
use Cake\Cache\Engine\FileEngine;
use Cake\Database\Connection;
use Cake\Database\Driver\Sqlite;
use Cake\Error\ExceptionRenderer;
use Cake\Log\Engine\FileLog;
use Cake\Mailer\Transport\MailTransport;

return [
    'debug' => true,

    'App' => [
        'namespace' => 'App',
        'encoding' => 'UTF-8',
        'defaultLocale' => 'ja_JP',
        'defaultTimezone' => 'Asia/Tokyo',
        'base' => false,
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        'fullBaseUrl' => false,
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'plugins' => [ROOT . DS . 'plugins' . DS],
            'templates' => [APP . 'Template' . DS],
            'locales' => [APP . 'Locale' . DS],
        ],
    ],

    'Security' => [
        'salt' => '2dd94367369213e3a965f1fcf0d14db4513e921ebd31a6b76197e8c5549bae7b',
    ],

    'Asset' => [],

    'Cache' => [
        'default' => [
            'className' => FileEngine::class,
            'path' => CACHE,
            'duration' => '+10 minutes',
        ],
        '_cake_core_' => [
            'className' => FileEngine::class,
            'prefix' => 'handson_cake_core_',
            'path' => CACHE . 'persistent/',
            'serialize' => true,
            'duration' => '+1 years',
        ],
        '_cake_model_' => [
            'className' => FileEngine::class,
            'prefix' => 'handson_cake_model_',
            'path' => CACHE . 'models/',
            'serialize' => true,
            'duration' => '+1 years',
        ],
    ],

    'Error' => [
        // 警告・通知・非推奨の表示を止めて運用している
        'errorLevel' => E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_USER_DEPRECATED,
        'exceptionRenderer' => ExceptionRenderer::class,
        'skipLog' => [],
        'log' => true,
        'trace' => true,
    ],

    'EmailTransport' => [
        'default' => [
            'className' => MailTransport::class,
        ],
    ],

    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => 'you@localhost',
        ],
    ],

    'Datasources' => [
        'default' => [
            'className' => Connection::class,
            'driver' => Sqlite::class,
            'database' => ROOT . DS . 'db' . DS . 'app.sqlite',
            'encoding' => 'utf8',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
            'log' => false,
        ],
    ],

    'Log' => [
        'debug' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'debug',
            'levels' => ['notice', 'info', 'debug'],
        ],
        'error' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'error',
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        ],
    ],

    'Session' => [
        'defaults' => 'php',
    ],
];
