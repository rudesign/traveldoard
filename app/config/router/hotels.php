<?php

$group = new \Phalcon\Mvc\Router\Group(array(
    'controller' => 'hotels'
));

// Grid
$group->add(
    '/hotels',
    array(
        'action' => 'showGrid'
    )
)->setName('hotels');

// Item
$group->add(
    '/hotels/:int',
    array(
        'action' => 'showItem',
        'id' => 1,
    )
)->setName("hotelsItem");

$router->mount($group);