<?php

use Symfony\Component\Dotenv\Dotenv;

define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('SRC_PATH', ROOT_PATH . 'src/');
define('VAR_PATH', ROOT_PATH . 'var/');
define('STORAGE_PATH', ROOT_PATH . 'storage/');
define('VAR_CACHE_PATH', VAR_PATH . 'cache/');
define('VAR_TMP_PATH', VAR_PATH . 'tmp/');


require ROOT_PATH . 'vendor/autoload.php';

if (!file_exists(__DIR__ . '/../.env')) {
    error_log('.env file not found');
    exit;
}

(new Dotenv())->usePutenv()->bootEnv(__DIR__ . '/../.env');

$containerBuilder = include CONFIG_PATH . '/container.php';
