<?php

error_reporting(E_ALL);

try {

    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();

    echo '<h1>Stacktrace</h1>';

    foreach($e->getTrace() as $row){
        echo $row['class'].$row['type'].$row['function'].(!empty($row['file']) ? ' (file '.$row['file'].' at line '.$row['line'].')' : '').'<br />';
    }
}
