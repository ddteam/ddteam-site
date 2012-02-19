<?php

class Application_Plugin_Route extends Zend_Controller_Plugin_Abstract
{

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        parent::routeShutdown($request);

        $custom = array();

        $cache = Zend_Registry::get('cacheManager')->getCache('route');
        $routes = $cache->load('route');
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $appConf = Zend_Controller_Action_HelperBroker::getStaticHelper('config')->get('route');

        if (!empty($appConf) && array_key_exists('param', $appConf)) {
            $custom += $appConf['param']['default'];
        }
        if (!empty($routes[$router->getCurrentRouteName()])) {
        foreach ($routes[$router->getCurrentRouteName()] as $k => $v) {
            switch ($k) {
                case 'type': break;
                case 'route': break;
                case 'defaults': break;
                default:
                    $custom[$k] = $v;
                    break;
            }
        }
        }
        $router->setParams($custom);
    }

}
