<?php

class User_Model_RememberMe
{

    protected $_identityKey = 'identity';
    protected $_credentialKey = 'credential';
    protected $_userIdentityKey = 'email';
    protected $_userCredentialKey = 'password';
    protected $_identity = null;
    protected $_credential = null;
    protected $_expires = 14;

    public function rememberUser($user)
    {
        $this->_getCookies()->set($this->_identityKey, $user->{$this->_userIdentityKey}, null, $this->_expires);
        $this->_getCookies()->set($this->_credentialKey, $user->{$this->_userCredentialKey}, null, $this->_expires);
    }

    /**
     * Get remembered user (if any)
     * @return User 
     */
    public function getRememberedUser()
    {
        return Doctrine::getTable('User')->findOneByParams(array(
            $this->_userIdentityKey => $this->getIdentity()
        ));
    }

    public function forgetRememberedUser()
    {
        $this->_clearCookies();
    }

    protected function _getCookies()
    {
        return FinalView_Cookies::getInstance();
    }

    /**
     * Get identity (if any) stored in cookies
     * @return string 
     */
    public function getIdentity()
    {
        if ($this->_identity === null) {
            $this->_identity = $this->_getCookies()->get($this->_identityKey);
        }
        return $this->_identity;
    }

    /**
     * Get credential (if any) stored in cookies
     * @return string 
     */
    public function getCredential()
    {
        if ($this->_credential === null) {
            $this->_credential = $this->_getCookies()->get($this->_credentialKey);
        }
        return $this->_credential;
    }

    protected function _clearCookies()
    {
        $this->_getCookies()->set($this->_identityKey, $user->{$this->_userIdentityKey}, null, $this->_expires);
        $this->_getCookies()->set($this->_credentialKey, $user->{$this->_userCredentialKey}, null, $this->_expires);
    }

}