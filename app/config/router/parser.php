<?php

$group = new \Phalcon\Mvc\Router\Group(array(
    'controller' => 'parser'
));

$group->add(
    '/parser/:action',
    array(
        'action'=>1,
    )
);

$router->mount($group);