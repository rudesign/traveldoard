<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 * @todo which services included?
 */
$di = new FactoryDefault();

// Common config
$di->set('config', function() use ($di){
    return include __DIR__ . '/config.php';
}, true);

// Router
$di->set('router', function () use ($di) {
    return include __DIR__ . '/router/common.php';
}, true);

// Session
$di->set('session', function () {
    $session = new Session(array(
        'cookie_lifetime' => 86400,
    ));
    $session->start();

    return $session;
});

// URL
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

// Database connection
$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => 'utf8',
    ));
});

// View
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

// Cookie helper
$di->set('cookie',  function()
{
    return new Cookie();
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 * @todo what MetaDataAdapter is needed for?
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});