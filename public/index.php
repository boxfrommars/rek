<?php

$startTime = microtime(true);
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));


/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'functions.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    array(
        'config' => array(APPLICATION_PATH . '/configs/application.ini', APPLICATION_PATH . '/configs/local.ini'),
    )
);


Whale_Log::setStartTime($startTime);

$application->bootstrap()
            ->run();

Whale_Log::log('end');
