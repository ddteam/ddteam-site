<?php

require_once LIBRARY_PATH . '/FinalView/Bootstrap.php';

class Bootstrap extends FinalView_Bootstrap
{

    /**
     * Init plugin to use https for secure pages
     *
     */
    protected function _initSecurePlugin()
    {
        $this->bootstrap('AplicationAutoloader');

        $this->bootstrap('FrontController');

        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Application_Plugin_SecureRequest, 3);
    }

    protected function _initRoles()
    {
        require_once APPLICATION_PATH.'/Roles.php';
    }
    
    protected function _initAuthUserTable()
    {       
        $this->bootstrap('Doctrine');

        FinalView_Auth::setAuthEntityTable('User');
    }

    protected function _initAuthUser()
    {
        $this->bootstrap('AuthUserTable');

        FinalView_Auth::getInstance()->refreshStorage();
    }
        
    protected function _initAccessRules()
    {
        $this->bootstrap('Doctrine');

        $rules = array();

        $newRules = APPLICATION_PATH . '/configs/rules/';

        iterate_resursive($newRules, function ($path, $var) {
                    if (strtolower(pathinfo($path, PATHINFO_EXTENSION) == 'yml')) {
                        $var[0] += Doctrine_Parser::load($path, 'yml');
                    }
                }, array(&$rules));

        FinalView_Access_Rules::setSchema($rules);

        $accessRulesConfig = $this->getOption('rules');
        if (!is_null($accessRulesConfig)) {
            if (array_key_exists('default_behavior', $accessRulesConfig)) {
                $accessRulesConfig['default_behavior'] = (bool) $accessRulesConfig['default_behavior'];
            }
            FinalView_Access_Rules::$options = $accessRulesConfig;
        }

        $loader = $this->getResource('AplicationAutoloader');
        $loader->addResourceType('rules', '/rules', 'Rules');
    }

    protected function _initAccessHandlers()
    {
        FinalView_Access_Handler::addHandlerPath('Application_Access_Handler', APPLICATION_PATH . '/access/handlers');
    }

    protected function _initResources()
    {
        $this->bootstrap('Doctrine');

        $resources = array();

        $newResourcesDir = APPLICATION_PATH . '/configs/resources/';

        iterate_resursive($newResourcesDir, function ($path, $var) {
                    if (strtolower(pathinfo($path, PATHINFO_EXTENSION) == 'yml')) {
                        $var[0] += Doctrine_Parser::load($path, 'yml');
                    }
                }, array(&$resources));

        FinalView_Application_Resources::setAccessMode(FinalView_Application_Resources::ACCESS_MODE_SOFT);
        FinalView_Application_Resources::setResources($resources);

        $this->bootstrap('FrontController');
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Application_Plugin_Access);
    }

    /**
     * Cache. Intentionally commented.
     */
    protected function _initCache()
    {
        $this->bootstrap('cachemanager');

        $manager = $this->getResource('cachemanager');

        Zend_Registry::set('cacheManager', $manager);
    }

    /**
     * Init plugin to use custom parameters in route
     *
     */
    protected function _initRoutePlugin()
    {
        $this->bootstrap('FrontController');

        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Application_Plugin_Route, 2);
    }

    /**
     * Init plugin contex switcher
     *
     */
    protected function _initContextSwitcher()
    {
        $this->bootstrap('FrontController');

        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Application_Plugin_ContextSwitcher, 4);
    }
    
}
