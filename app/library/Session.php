<?php

class Session extends Phalcon\Session\Adapter\Files
{
    /**
     * Starts the session
     * @return bool
     */
    public function start()
    {
        if ($this->_started) {
            return false;
        }

        // Configure cookie lifetime
        $lifetime = 0;
        if (!empty($this->_options['cookie_lifetime'])) {
            $lifetime = (int) $this->_options['cookie_lifetime'];
        }

        // Configure cookie path
        $path = "/";
        if (!empty($this->_options['cookie_path'])) {
            $lifetime = $this->_options['cookie_path'];
        }

        // Configure cookie domain
        $domain = '.' . $_SERVER['SERVER_NAME'];
        if (!empty($this->_options['cookie_domain'])) {
            $domain = $this->_options['cookie_domain'];
        }

        // Configure if cookies should be transfered only via protected connection
        $secure = false;
        if (!empty($this->_options['cookie_secure'])) {
            $domain = (bool) $this->_options['cookie_secure'];
        }

        // Configure cookie access level
        $httponly = false;
        if (!empty($this->_options['cookie_httponly'])) {
            $domain = (bool) $this->_options['cookie_httponly'];
        }

        // Set cookie parameters
        session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);

        // Start session
        return parent::start();
    }
}