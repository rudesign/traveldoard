<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'travelboard',
        'password'    => 'sdghw234',
        'dbname'      => 'travelboard',
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'helpersDir'     => __DIR__ . '/../../app/helpers/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'incubatorDir'   => __DIR__ . '/../../app/library/incubator/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '/',
    )
));
