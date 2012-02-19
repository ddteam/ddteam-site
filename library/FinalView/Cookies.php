<?php

class FinalView_Cookies
{

    protected static $_instance = null;

    protected function __construct()
    {
        
    }

    protected function __clone()
    {
        
    }

    public static function getInstance()
    {
        if (self::$_instance === null) {
            $c = __CLASS__;
            self::$_instance = new $c;
        }
        return self::$_instance;
    }

    /**
     * Get cookie
     * @param string $key key name
     * @return mixed value
     */
    public function get($key)
    {
        $request = $this->_getRequest();
        $cookie = $request->getCookie();

        return isset($cookie[$key]) ? $cookie[$key] : null;
    }

    /**
     * Set cookie
     * @param string $key key
     * @param string $value value
     * @param string $domain domain
     * @param Zend_Date|int $expires end date (if instance Zend_Date given) 
     *                      or number of days that cookie will be stored (int)
     * @param string $path path
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function set($key, $value, $domain = null, $expires = 15, $path = '/', $secure = null, $httpOnly = null)
    {
        if (!($expires instanceof Zend_Date)) {
            $expDate = new Zend_Date();
            $expDate->addDay($expires);

            $expires = $expDate;
        }

        $expiresString = $expires->get(Zend_Date::COOKIE);

        $response = $this->_getResponse();
        if (!$response->canSendHeaders()) {
            throw new Zend_Exception('Headers already sent');
        }

        $cDomain = $domain ? ' Domain=' . $domain . ';' : '';
        $cSecure = $secure ? '; Secure' : '';
        $cHttpOnly = $httpOnly ? '; HttpOnly' : '';

        $response->setHeader('Set-Cookie', "$key=$value;$cDomain expires=$expiresString; path=$path" . $cSecure . $cHttpOnly);
    }
   
    protected function _getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }

    protected function _getResponse()
    {
        return Zend_Controller_Front::getInstance()->getResponse();
    }

}