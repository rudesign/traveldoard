<?php

// Users group
$group = new \Phalcon\Mvc\Router\Group(array(
    'controller' => 'user'
));

// Signup
$group->add(
    '/signup',
    array(
        'action'=>'signup',
    )
);

// Login
$group->add(
    '/login',
    array(
        'action'=>'login',
    )
);
$group->add(
    '/login/do',
    array(
        'action'=>'doLogin',
    )
);

// Logout
$group->add(
    '/logout',
    array(
        'action'=>'logout',
    )
);

$router->mount($group);

// People group
$group = new \Phalcon\Mvc\Router\Group(array(
    'controller' => 'people'
));

// Grid
$group->add(
    '/people',
    array(
        'action' => 'showGrid'
    )
)->setName('people');

// Item
$group->add(
    '/people/:int',
    array(
        'action'     => 'showItem',
        'id'     => 1,
    )
)->setName("peopleItem");

$router->mount($group);