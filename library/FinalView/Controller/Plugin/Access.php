<?php

abstract class FinalView_Controller_Plugin_Access extends Zend_Controller_Plugin_Abstract
{

    /**
     * Current resource (that matched current request)
     * @var FinalView_Application_Resources 
     */
    private $_resource;

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $resource = $this->_matchRequestToResource($request);
        $resource = FinalView_Application_Resources::get($resource);

        if (is_null($resource)) {
            $this->setResource($resource);
            try {
                FinalView_Access_Handler::runHandler('notFound', $resource);
            } catch (Exception $e) {
                $this->_throwException($e);
            }
        } else {
            $this->setResource($resource);

            try {
                $requestAllowed = $resource->getAccessRule()->check($request->getParams());
            } catch (Exception $e) {
                $this->_throwException($e);
                return;
            }

            if (!$requestAllowed) {
                if ($handler = $resource->getResource('handler')) {
                    try {
                        FinalView_Access_Handler::runHandler($handler, $resource);
                    } catch (Exception $e) {
                        $this->_throwException($e);
                    }
                } else {
                    try {
                        FinalView_Access_Handler::runDefaultHandler($resource);
                    } catch (Exception $e) {
                        $this->_throwException($e);
                    }
                }
            }
        }
    }

    protected abstract function _matchRequestToResource(Zend_Controller_Request_Abstract $request);

    public function setResource(FinalView_Application_Resources $resource)
    {
        $this->_resource = $resource;
        return $this;
    }

    public function getResource()
    {
        return $this->_resource;
    }

    protected function _throwException(Exception $e)
    {
        $error = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);

        $error->exception = $e;
        $error->type = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER;
        $error->request = clone $this->_request;

        $this->_request
                ->setModuleName('default')
                ->setControllerName('error')
                ->setActionName('error')
                ->setParam('error_handler', $error)
                ->setDispatched(true);
    }

}
