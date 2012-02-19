<?php

class Application_Plugin_SecureRequest extends Zend_Controller_Plugin_Abstract
{

    protected $_secure_pages;

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // skip ajax and errors
        if ($request->isXmlHttpRequest() ||
            'default' == $request->getModuleName() &&
            'error' == $request->getControllerName() &&
            'error' == $request->getActionName())
        {
            return;
        }

        $isSecure = Zend_Controller_Front::getInstance()->getRouter()->getParam('isSecure');
        
        switch(true)
        {
            case $isSecure && !$request->isSecure() :
                $scheme = Zend_Controller_Request_Http::SCHEME_HTTPS;
                break;

            case $request->isSecure() && !$isSecure:
                $scheme = Zend_Controller_Request_Http::SCHEME_HTTP;
                break;
        }

         if (isset($scheme)) {
             Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')
                 ->gotoUrl($scheme . '://' . $request->getHttpHost() . $request->getRequestUri());
         }
    }
}