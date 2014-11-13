<?php

class AsyncRequest extends BaseController
{
    public $data = array();

    public function setData($data = array(), $append = false)
    {
        try{
            if($append){
                $this->data = array_merge($data, $this->getData());
            }else{
                $this->data = $data;
            }
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function validateModel(\Phalcon\Mvc\ModelInterface $obj)
    {
        if ($obj->validation() == false) {

            $messages = $obj->getMessages();
            $message = (string) reset($messages);
            $this->setMessage($message);

            return false;
        } else return true;
    }

    public function setMessage($message = '')
    {
        $this->setData(array('message' => $message), true);
    }

    public function setLocation($location = '')
    {
        if(empty($location)) $location = $this->url->getBaseUri();
        $this->setData(array('location' => $location), true);

        return true;
    }

    /**
     * Submit content to the output
     */
    public function submitJSON()
    {
        $this->response->setJsonContent($this->data);

        $this->response->send();

        $this->view->disable();
    }
}