<?php

abstract class FinalView_Access_Handler_Redirect_Abstract extends FinalView_Access_Handler_Abstract
{
    abstract protected function _getRedirectRoute();

    protected function _getRouteParams()
    {
        return array();
    }

    protected function _getUrlParams()
    {
        return array();
    }

    protected function _getRedirectorHelper()
    {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
    }

    protected function _getRedirectUrl()
    {
        $urlHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Url');
        $addToUrlHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('AddToUrl');

        $url = $urlHelper->url($this->_getRouteParams(), $this->_getRedirectRoute());
        $url = $addToUrlHelper->addToUrl($this->_getUrlParams(), $url);
        return $url;
    }

    public function runHandler()
    {
        $this->_getRedirectorHelper()->gotoUrl($this->_getRedirectUrl());
    }
}