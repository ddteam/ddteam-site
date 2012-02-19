<?php

/**
 * Authenticate user by account info stored in cookies
 */
class User_Plugin_RememberMe extends Zend_Controller_Plugin_Abstract
{

    protected $_auth = null;
    protected $_rememberMeModel = null;
    protected $_userIdentityKey = 'email';
    protected $_userCredentialKey = 'password';
    public $storageParams = array('id');

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$this->_isAuthenticated()) {
            $identity = $this->_getRememberMeModel()->getIdentity();
            if ($identity) {
                $user = $this->_getRememberMeModel()->getRememberedUser();

                if (!$user) {
                    return;
                }

                $credential = $this->_getRememberMeModel()->getCredential();

                // for use with FV_Auth, encrypt password (mutator will do it)
                $user->{$this->_userCredentialKey} = $user->{$this->_userCredentialKey};

                $verifuingData = array(
                    $this->_userIdentityKey => $identity,
                    $this->_userCredentialKey => $credential
                );

                $result = $this->_getAuthInstance()->authenticate(new User_Auth_Adapter(
                                        $verifuingData,
                                        $user,
                                        $this->storageParams
                        ));

                if ($result->getCode() !== Zend_Auth_Result::SUCCESS) {
                    $this->_getRememberMeModel()->forgetRememberedUser();
                }
            }
        }
    }

    /**
     * Is user already authenticated?
     * @return bool 
     */
    protected function _isAuthenticated()
    {
        return $this->_getAuthInstance()->hasIdentity();
    }

    protected function _getAuthInstance()
    {
        if ($this->_auth === null) {
            $this->_auth = FinalView_Auth::getInstance();
        }
        return $this->_auth;
    }

    /**
     * Get remember me model
     * 
     * @return User_Model_RememberMe 
     */
    protected function _getRememberMeModel()
    {
        if ($this->_rememberMeModel === null) {
            $this->_rememberMeModel = new User_Model_RememberMe();
        }
        return $this->_rememberMeModel;
    }

}