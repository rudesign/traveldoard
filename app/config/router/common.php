<?php

$router = new Phalcon\Mvc\Router(false);

$router->setDI($di);

// Remove last backslash
$router->removeExtraSlashes(true);

$router->setDefaultController('index');
$router->setDefaultAction('index');

// Index page
$router->add('/');

// Users + People group
include __DIR__ . '/users.php';

include __DIR__ . '/parser.php';

include __DIR__ . '/process.php';


// Not found
$router->notFound(array(
    'controller'=>'error',
    'action' => 'e404'
));

return $router;
