<?php

// Users group
$group = new \Phalcon\Mvc\Router\Group(array(
    'controller' => 'parser'
));

// Signup
$group->add(
    '/parser/:action',
    array(
        'action'=>1,
    )
);

$router->mount($group);