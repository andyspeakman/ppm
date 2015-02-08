<?php
// public/index.php

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH',
        realpath(dirname(__FILE__) . '/../application'));

defined('APPLICATION_ENVIRONMENT')
    || define('APPLICATION_ENVIRONMENT',
        (getenv('APPLICATION_ENVIRONMENT') ? getenv('APPLICATION_ENVIRONMENT') : 'production'));

set_include_path(implode(PATH_SEPARATOR, array(
    dirname(dirname(__FILE__)) . '/library',
        get_include_path()
)));

/** Zend Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENVIRONMENT,
    APPLICATION_PATH . '/config/config.xml'
);
$application->setAutoloaderNamespaces(array('Lightman_'))
            ->bootstrap()
            ->run();
