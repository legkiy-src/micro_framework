<?php

ini_set('display_errors', 'on');
ini_set('log_errors', 'on');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

/*if (isset($argv)) {
    $applicationRegistry = ApplicationRegistry::instance();
    $applicationRegistry->set('argv', $argv);
}*/

Engine\AppController::run();
