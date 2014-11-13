<?php

class ErrorController extends ViewsController
{
    public function e404Action()
    {
        $this->setTitle('Not Found');

        $this->response->setStatusCode(404, 'Not Found');

        $this->view->setVar('message', 'Добро пожаловать на '.$this->tag->linkTo('/', 'стартовую страницу сайта'));
    }
}