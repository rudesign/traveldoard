<?php

class Cookie extends \Phalcon\Http\Response\Cookies{

    public function __construct(){
        $this->useEncryption(false);
    }

    public function set($name, $value=null, $expire=null, $path=null, $secure=null, $domain=null, $httpOnly=null){

        $expire = time()+86400;
        $httpOnly = false;

        parent::set($name, $value, $expire, $path, $secure, $domain, $httpOnly);
    }
}