<?php

$group = new \Phalcon\Mvc\Router\Group(array(
    'controller' => 'process'
));

$group->add(
    '/process/:action',
    array(
        'action'=>1,
    )
);

$router->mount($group);