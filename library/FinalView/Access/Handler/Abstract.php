<?php

abstract class FinalView_Access_Handler_Abstract {

    /**
     * Resource connected to this handler
     * @var FinalView_Application_Resources
     */
    protected $_resource = null;

    /**
     * Constructor
     * 
     * @param FinalView_Application_Resources $resource the resource, which uses
     *      this handler 
     */
    public function __construct(FinalView_Application_Resources $resource)
    {
        $this->_resource = $resource;
    }

    
    /**
     * Actualy do all work to handle the rule failure for the resource
     */
    abstract public function runHandler();
    
    /**
     * Get resource connected to this handler
     * @return FinalView_Application_Resources
     */
    public function getResource()
    {
        return $this->_resource;
    }

    /**
     * Get error messages for failed rules
     * @return string 
     */
    protected function _getErrorMessage()
    {
        //get failed rules for the resource
        $rules = $this->getResource()->getAccessRule()->getFailedRules();
        $message = '';
        foreach ($rules as $rule) {
            if (!$rule->isAutoBuiltRule()) {
                $message .= __($rule->getTranslationKey()) . ' ';
            }
        }
        $message = rtrim($message);
        return $message;
    }
}