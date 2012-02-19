<?php

class Application_Access_Handler_RedirectToAdminIndex extends FinalView_Access_Handler_Redirect_Abstract
{    protected function _getRedirectRoute()
    {        return 'AdminIndexIndex';    }

    protected function _getRedirectUrl()
    {        return Zend_Controller_Front::getInstance()->getRequest()->getParam('back_url', parent::_getRedirectUrl());
    }
}