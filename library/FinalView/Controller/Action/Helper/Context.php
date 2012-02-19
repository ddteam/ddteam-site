<?php

class FinalView_Controller_Action_Helper_Context extends Zend_Controller_Action_Helper_ContextSwitch
{

    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->removeContext('xml');

        $this->addContext('html', array(
            'headers' => array('Content-Type' => 'text/html'),
            'callbacks' => array(
                'init' => 'initHtmlContext',
            ),
        ));

        $this->addContext('xml', array(
            'headers' => array('Content-Type' => 'application/xml'),
            'callbacks' => array(
                'post' => 'postXmlContext'
            )
        ));
    }

    /**
     * JSON post processing
     *
     * JSON serialize view variables to response body
     *
     * @return void
     */
    public function postJsonContext()
    {
        $viewRendererHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

        unset($viewRendererHelper->view->navigation);

        //@TODO: Warning: json_encode(): recursion detected in /var/www/vhosts/inearbeer/library/Zend/Json.php on line 146
        @parent::postJsonContext();
    }

    /**
     * HTML context extra initialization
     *
     * Turns on viewRenderer auto-rendering
     *
     * @return void
     */
    public function initHtmlContext()
    {
        // first check "is layout autodisabled", then "context init callback",
        // so turn it ON manualy
        $this->setAutoDisableLayout(false);
        Zend_Layout::getMvcInstance()->enableLayout();
    }

    public function postXmlContext()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $xml = new FinalView_Xml();

        $viewRenderer->setNoRender(true);
        $view = $viewRenderer->view;

        $this->getResponse()->setBody($xml->generateXml($view->getVars(), 'root'));
    }

    /**
     * Init current action context
     *
     * @return void
     */
    public function preDispatch()
    {
        $this->addActionContext($this->getRequest()->getActionName(), array('html', 'json', 'xml'));
        $this->initContext($this->defineContext());
    }

    public function defineContext()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();

        return $router->getParam('context');
    }

}