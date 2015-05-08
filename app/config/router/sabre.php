<?php

$group = new \Phalcon\Mvc\Router\Group(array(
    'controller' => 'sabre'
));

// Grid
$group->add(
    '/sabre/([a-zA-Z]+)/([a-zA-Z]+)',
    array(
        'action' => 1,
        'type' => 2
    )
);

$router->mount($group);