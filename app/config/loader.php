<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->helpersDir,
        $config->application->libraryDir
    )
)->register();

$loader->registerNamespaces(array(
    'Phalcon' => '../incubator/Library/Phalcon/'
))->register();

$loader->register();
