<?php

use Phalcon\Http\Client\Request;

class TestController extends BaseController
{
    public function initialize(){
        parent::initialize();
    }

    public function testAction()
    {
        echo shell_exec('curl -I ya.ru');

        $this->view->disable();
    }
}
