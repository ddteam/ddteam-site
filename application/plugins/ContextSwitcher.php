<?php

class Application_Plugin_ContextSwitcher extends Zend_Controller_Plugin_Abstract
{

    /**
     * Called before an action is dispatched by Zend_Controller_Dispatcher.
     *
     * This callback allows for proxy or filter behavior.  By altering the
     * request and resetting its dispatched flag (via
     * {@link Zend_Controller_Request_Abstract::setDispatched() setDispatched(false)}),
     * the current action may be skipped.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // TODO: move in cli_module branch
        if ($this->getRequest() instanceof FinalView_Controller_Request_Cli) {
            return;
        }

        Zend_Controller_Action_HelperBroker::getStaticHelper('Context');
    }

}