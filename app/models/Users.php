<?php

class Users extends \Phalcon\Mvc\Model
{

    protected $id;

    protected $visible;

    protected $name;

    protected $city;

    protected $sid;

    protected $login;

    protected $password;


    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setCity($name)
    {
        $this->city = $name;

        return $this;
    }

    public function setSid($sid)
    {
        $this->sid = $sid;

        return $this;
    }

    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function makeSid(){
        return $this->getDI()->get('security')->hash(time());
    }

    public function getVisible()
    {
        return $this->visible;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getSid()
    {
        return $this->sid;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'visible' => 'visible', 
            'name' => 'name', 
            'city' => 'city',
            'login' => 'login', 
            'password' => 'password',
            'sid' => 'sid'
        );
    }

    /**
     * Create a new user
     * @return bool
     * @throws Exception
     */
    public function signup()
    {
        // Check if login exists
        if($user = $this->checkIfLoginExists($this->getLogin())) throw new \Exception('Login exists');

        // Create a new user
        if(!$this->save()) throw new \Exception('User creation failed');

        // Force login
        $this->login($this->getLogin(), $this->getPassword(), true);

        return true;
    }


    /**
     * Perform login
     * @param string $login
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public function login($login = '', $password = '', $passwordSecured = false)
    {
        $this->getSession()->remove('sid');

        // Get user by auth data
        $user = $this->getByAuthData($login, $password, $passwordSecured);

        // Generate new session id
        if(!$sid = $this->makeSid()) throw new \Exception('Empty session id');

        // Change user model data set
        $user->setSid($sid);

        // Store sid in the session
        $this->getSession()->set('sid', $sid);
        $this->getSession()->set('uid', $user->getId());

        // Update user
        if(!$user->update()) throw new \Exception('Session id update failed');

        return true;
    }

    /**
     * Checks if current user is authorised
     * no matter who it is
     * @return bool
     */
    public function isAuthorised(){
        try{
            if(!$this->getSession()->has('sid')) throw new \Exception;

            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * Checks if login exists
     * @param string $login
     * @return Users|null
     * @throws Exception
     */
    public function checkIfLoginExists($login = ''){
        if(empty($login)) throw new \Exception('Не указан login');

        $result = $this->query()->where("login='{$login}'")->limit(1)->execute();

        return ($user = $result->getFirst()) ? $user : null;
    }

    /**
     * Get user by login & password
     * @param string $login
     * @param string $password
     * @return Users
     * @throws Exception
     */
    public function getByAuthData($login = '', $password = '', $passwordSecured = false)
    {
        if(empty($login)) $login = $this->getLogin();
        if(empty($password)) $password = $this->getPassword();

        if(empty($login)) throw new \Exception('Не указан login');
        if(empty($password)) throw new \Exception('Не указан пароль');

        if(!$user = $this->checkIfLoginExists($login)) throw new Exception('Пользователь не зарегистрирован');

        if($passwordSecured){
            if($password != $user->password) throw new \Exception('Неверный пароль');
        }else{
            if(!$this->getDI()->get('security')->checkHash($password, $user->password)) throw new \Exception('Неверный пароль');
        }

        return $user;
    }

    /**
     * Get user by session id
     * @return null|Users
     */
    public function getBySid()
    {
        try{
            if(!$this->getSession()->has('sid')) throw new \Exception;

            $sid = $this->getSession()->get('sid');

            $result = $this->query()->where("sid='{$sid}'")->limit(1)->execute();

            if(!$user = $result->getFirst()) throw new \Exception;

            return $user;
        }catch (\Exception $e){
            return null;
        }
    }

    /**
     * Logging out and destroy current session
     */
    public function logout(){
        $this->getSession()->destroy();
    }

    /**
     * Returns current session service
     * @return Phalcon\Session\Bag()
     */
    protected function getSession(){
        return $this->getDI()->get('session');
    }
}
