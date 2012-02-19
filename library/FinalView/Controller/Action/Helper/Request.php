<?php

class FinalView_Controller_Action_Helper_Request extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Request params
     * 
     * @var array
     */
    private $_params = array();

    /**
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     * 
     * @var Array HTTP 1.1 allowed methods
     */
    private $_allowedMethods = array(
        'ALL',
        'OPTIONS',
        'GET',
        'HEAD',
        'POST',
        'PUT',
        'DELETE',
        'TRACE',
        'CONNECT');

    /**
     * Hook into action controller initialization
     *
     * @return void
     */
    public function init()
    {
        $this->_isXmlHttpRequest();
        $this->_controlRequestMethod();
        $this->_initRequestParams();
        $this->_transformRequestParams();
    }

    private function _isXmlHttpRequest()
    {
        if (Zend_Controller_Front::getInstance()->getRouter()->getParam('XMLHttpRequest') &&
                !$this->getRequest()->isXmlHttpRequest()) {
            Zend_Controller_Action_HelperBroker::getStaticHelper('error')->forbidden(
                    'Method is available only with header X-Requested-With: XMLHttpRequest');
        }
    }

    private function _controlRequestMethod()
    {

        //TODO: move in cli_module branch
        if ($this->getRequest() instanceof FinalView_Controller_Request_Cli) {
            return;
        }

        $currentMethod = $this->getRequest()->getMethod();
        $request = explode('|', Zend_Controller_Front::getInstance()->getRouter()->getParam('method'));

        foreach ($request as $k => $v) {

            $v = strtoupper($v);

            if ($v == $this->_allowedMethods[0]) {
                return;
            }
            if (!in_array($v, $this->_allowedMethods)) {
                Zend_Controller_Action_HelperBroker::getStaticHelper('error')->notFound(
                        'Method ' . $v . ' not found, existing methods: ' . implode(', ', $this->_allowedMethods));
            }
        }

        if (!in_array($currentMethod, $request)) {
            $allowedMethodsAsString = implode(' or ', $request);
            $message = sprintf('Not Found: use method %s instead of %s', $allowedMethodsAsString, $currentMethod);
            Zend_Controller_Action_HelperBroker::getStaticHelper('error')->notFound($message);
        }
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getParam($name, $default = null)
    {
        return $this->hasParam($name) ? $this->_params[$name] : $default;
    }

    public function hasParam($name)
    {
        return array_key_exists($name, $this->_params);
    }

    /**
     * Set params types if annotated
     * 
     */
    private function _initRequestParams()
    {
        $params = $this->getRequest()->getUserParams();
        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        $this->_params = $params;
    }

    private function _transformRequestParams()
    {
        $actionClass = $this->getActionController();
        $actionMethod = $this->getFrontController()->getDispatcher()->getActionMethod($this->getRequest());
        $reflMethod = new Zend_Reflection_Method($actionClass, $actionMethod);
        try {
            $docblock = $reflMethod->getDocblock();
            $tagParams = $docblock->getTags();
            foreach ($this->_params as $paramName => &$paramValue) {

                foreach ($tagParams as $tagParam) {
                    /* @var $tagParam Zend_Reflection_Docblock_Tag_Param */

                    // work only with "param" tag
                    if ('param' != $tagParam->getName()) {
                        continue;
                    }

                    if ($this->_getRefParamName($tagParam) == $paramName) {
                        $this->_setParamType($paramValue, $this->_getRefParamTypes($tagParam));
                        break;
                    }
                }
            }
        } catch (Zend_Reflection_Exception $exception) {
            // no docblock annotation - no params transformation :)
        }
    }

    private function _setParamType(&$paramValue, array $refParamTypes)
    {
        // value IS NULL and can be NULL so everything ok
        if (is_null($paramValue) && $this->_paramCanBeNull($refParamTypes)) {
            return;
        }

        // search for the first NOT NULL type
        while ($type = array_shift($refParamTypes)) {
            if (!$this->_paramTypeIsNull($type)) {
                break;
            }
        }

        $basicTypes = array
            (
            'bool', 'boolean',
            'int', 'integer',
            'double', 'float',
            'string',
            'array',
            'object',
        );

        switch (true) {
            case in_array($type, $basicTypes) :
                settype($paramValue, $type);
                break;
            case!$this->_paramTypeIsNull($type) && class_exists($type, true) :
                $paramValue = new $type($paramValue);
                break;
            default :
                trigger_error(sprintf('Invalid type %s given', $type), E_USER_ERROR);
                break;
        }
    }

    private function _getRefParamName(Zend_Reflection_Docblock_Tag_Param $tagParam)
    {
        return ltrim($tagParam->getVariableName(), '$');
    }

    private function _getRefParamTypes(Zend_Reflection_Docblock_Tag_Param $tagParam)
    {
        return array_filter(explode('|', $tagParam->getType()));
    }

    private function _paramCanBeNull(array $refParamTypes)
    {
        foreach ($refParamTypes as $type) {
            if ($this->_paramTypeIsNull($type)) {
                return true;
            }
        }

        return false;
    }

    private function _paramTypeIsNull($type)
    {
        return 0 === strcasecmp($type, 'null');
    }

}