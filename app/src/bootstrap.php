<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Symfony\Component\Dotenv\Dotenv;

// phpcs:ignoreFile
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('SRC_PATH', ROOT_PATH . 'src/');
define('VAR_PATH', ROOT_PATH . 'var/');
define('STORAGE_PATH', ROOT_PATH . 'storage/');
define('VAR_CACHE_PATH', VAR_PATH . 'cache/');
define('VAR_TMP_PATH', VAR_PATH . 'tmp/');

require ROOT_PATH . 'vendor/autoload.php';

if (!file_exists(__DIR__ . '/../.env')) {
    echo '.env file not found';
    exit;
}

date_default_timezone_set('Europe/Moscow');

(new Dotenv())->usePutenv()->bootEnv(__DIR__ . '/../.env');

$containerBuilder = include CONFIG_PATH . '/container.php';

$logger = new Logger('main');
$logger->pushHandler(new RotatingFileHandler(VAR_PATH . 'log/main.log'));
