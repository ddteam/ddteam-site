<?php

class FinalView_Auth_Storage_Cookie implements Zend_Auth_Storage_Interface
{
    /**
     * Default cookie name
     */
    const MODE_NAME = 'auth';

    /**
     * Cookie name
     *
     * @var string
     */
    protected $_name;

    /**
     * Sets session cookit name
     *
     * @param  striong $auth
     * @return void
     */
    public function __construct($auth = self::MODE_NAME)
    {
        $this->_name = $auth;
    }

    /**
     * Returns the cookie name
     *
     * @return string
     */
    public function getCookieName()
    {
        return $this->_name;
    }

    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return FinalView_Cookies::getInstance()->get($this->_name) ? false : true;
    }

    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return mixed
     */
    public function read()
    {
        $encyptor = FinalView_Encrypt_Mcrypt::getInstance();

        $cookie = FinalView_Cookies::getInstance()->get($this->_name);
               
        return json_decode($encyptor->decrypt(urldecode($cookie)));
    }

    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @param  array $contents
     * @return void
     */
    public function write($contents)
    {       
        $encyptor = FinalView_Encrypt_Mcrypt::getInstance();
        
        $cookie = $encyptor->encrypt(json_encode($contents));
               
        FinalView_Cookies::getInstance()->set($this->_name, urlencode($cookie));
    }

    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return void
     */
    public function clear()
    {
       FinalView_Cookies::getInstance()->set($this->_name, '', null, -1);
       unset($_COOKIE[$this->_name]);
        
    }

    private function _getApiConfig()
    {
        return Zend_Controller_Front::getInstance()
                ->getParam('bootstrap')->getOption('api');
    }

}
