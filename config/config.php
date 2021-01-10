<?php

require_once __DIR__ . '/config_pass.php';

//================================================= PATH ==============================================================

define('MODE', 0); // 0 - dev, 1 - prod

$dir = __DIR__ . DIRECTORY_SEPARATOR;

define('NAME_APPLICATION', '');

if (isset($argv) && php_sapi_name() === 'cli') {
    $getenvData = getenv();
    define('DIR_APPLICATION', $getenvData['PWD'] .  DIRECTORY_SEPARATOR);
} else {
    define('DIR_APPLICATION', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . NAME_APPLICATION . DIRECTORY_SEPARATOR);
}

define('DIR_CORE', DIR_APPLICATION  . 'application' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);
define('DIR_CONTROLLERS', DIR_APPLICATION  . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR);
define('DIR_MODELS', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR);
define('DIR_VIEWS', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('DIR_TEMPLATES', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR. 'templates' . DIRECTORY_SEPARATOR);
//define('DATA_DIR', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);
define('DIR_ENGINE', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR
    . 'core' . DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR);
define('DIR_LIBRARY', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR
    . 'core' . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR);
define('DIR_LIBRARY_CRYPT', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR
    . 'core' . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'crypt' . DIRECTORY_SEPARATOR);
define('DIR_LOG', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR
    . 'core' . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR);
define('DIR_LOG_APP', '/var/log/' . NAME_APPLICATION);
define('DIR_ROUTE', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR
    . 'core' . DIRECTORY_SEPARATOR . 'route' . DIRECTORY_SEPARATOR);
define('DIR_VERIFY', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR
    . 'core' . DIRECTORY_SEPARATOR . 'verify' . DIRECTORY_SEPARATOR);
define('DIR_RESPONSE', DIR_APPLICATION . 'application' . DIRECTORY_SEPARATOR
    . 'core' . DIRECTORY_SEPARATOR . 'response' . DIRECTORY_SEPARATOR);

//================================================= URI/URL ===========================================================

//$hostURI = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';
$hostName = gethostname();
$hostURI = 'https://';
$hostURI = $hostURI . $hostName . (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 ?  ':'.
        $_SERVER['SERVER_PORT']  : '');

define('HOST_URI', $hostURI);

if (isset($argv) && php_sapi_name() === 'cli') {
    $_SERVER['REQUEST_URI'] = $argv[6];
    define('REQUEST_URI', HOST_URI . $argv[6]);
} else {
    define('REQUEST_URI', HOST_URI . $_SERVER['REQUEST_URI']);
}

//================================================= DATABASE ==========================================================

define('DB_HOST', $dbHost);
define('DB_NAME', $dbName);
define('DB_USER', $dbUser);
define('DB_PASSWORD', $dbPassword);

//================================================= VERIFY ============================================================

define('SERVICE_USER', $serviceUser);
define('SERVICE_PASSWORD', $servicePassword);

//================================================= COMMON ============================================================


//================================================ DEBUG ==============================================================
define('SHOW_RUNTIME', false);

//================================================ FILES ==============================================================


//================================================ EMAILS =============================================================


//=============================================== LINKS ===============================================================


//=====================================================================================================================
define('KEY_CRYPT' , $keyKrypt);




