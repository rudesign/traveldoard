<?php

class UserController extends ViewsController
{
    public function initialize(){
        parent::initialize();
    }

    /**
     * Display signup view
     */
    public function signupAction()
    {
        // Move authorised user to the index page
        $this->redirectAuthorised();

        $this->setTitle('Регистрация');
    }

    /**
     * Display login view
     */
    public function loginAction()
    {
        // Move authorised user to the index page
        $this->redirectAuthorised();

        $this->setTitle('Авторизация');
    }

    /**
     * Check login form & login
     */
    public function doLoginAction()
    {
        // Increase login attempts counter
        $this->session->set('attempts', ($this->session->get('attempts')+1));

        // Get async request helper
        $async = new AsyncRequest();

        if($this->session->get('attempts') < 30){
            // Get user model
            $user = new Users();

            // Change user model data
            $user->setLogin($this->request->getPost('email'));
            $user->setPassword($this->request->getPost('password'));

            // Validate data
            if ($async->validateModel($user)) {
                try {
                    // Perform login
                    $user->login();

                    // Clean login attempts counter
                    $this->session->remove('attempts');

                    // Move to the index page
                    $async->setLocation();

                }catch (\Exception $e){
                    // Alert message
                    $async->setMessage($e->getMessage());
                }
            }
        }else{
            $async->setLocation();
        }

        $async->submitJSON();
    }

    /**
     * Redirect authorised user to index page
     */
    protected function redirectAuthorised()
    {
        if($this->session->has('sid')) {
            $this->redirectToIndex();
        }
    }

    /**
     * Logout
     */
    public function logoutAction()
    {
        $user = new Users();
        $user->logout();
        $this->redirectToIndex();
    }
}

