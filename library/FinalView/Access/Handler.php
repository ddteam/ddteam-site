<?php

class FinalView_Access_Handler
{

    /**
     * @var string name of the default handler
     */
    protected static $_defaultHandlerName = 'default';

    /**
     * @var Zend_Loader_PluginLoader
     */
    protected static $_pluginLoader = null;

    private function __construct()
    {
        
    }

    /**
     * Run handler
     * 
     * @param string $handlerName name of the handler
     * @param FinalView_Application_Resources $resource resource which runs the handler
     */
    public static function runHandler($handlerName, FinalView_Application_Resources $resource)
    {
        $filter = new Zend_Filter_Word_UnderscoreToCamelCase();
        $handlerName = $filter->filter($handlerName);
        
        $loader = self::_getPluginLoader();
        
        try {
            $className = $loader->load($handlerName);
        } catch (Exception $e){
            throw new FinalView_Exception('Handler '.$handlerName.' not found in handler paths.');
        }
        
        $handler = new $className($resource);
        if (!($handler instanceof FinalView_Access_Handler_Abstract)) {
            throw new FinalView_Exception('Handler ' . $handlerName . ' not a valid handler.');
        }

        $handler->runHandler();
    }

    public static function runDefaultHandler(FinalView_Application_Resources $resource)
    {
        self::runHandler(self::getDefaultHandler(), $resource);
    }

    /**
     * Get name of the default handler
     * 
     * @return string
     */
    public static function getDefaultHandler()
    {
        return self::$_defaultHandlerName;
    }

    /**
     * Set name of the default handler
     * 
     * @param type $handlerName 
     */
    public static function setDefaultHandler($handlerName)
    {
        self::$_defaultHandlerName = $handlerName;
    }

    /**
     * Add path to handlers
     * 
     * @param string $prefix
     * @param string $path 
     */
    public static function addHandlerPath($prefix, $path)
    {
        self::_getPluginLoader()->addPrefixPath($prefix, $path);
    }

    /**
     * Get path(s) to handlers
     * 
     * @param string $prefix
     * @return array
     */
    public static function getHandlerPaths($prefix = null)
    {
        return self::_getPluginLoader()->getPaths($prefix);
    }

    /**
     * Get plugin loader
     * 
     * @return Zend_Loader_PluginLoader
     */
    protected static function _getPluginLoader()
    {
        if (self::$_pluginLoader === null) {
            $defaultPath = self::_getDefaultHandlerPath();
            self::$_pluginLoader = new Zend_Loader_PluginLoader($defaultPath);
        }
        return self::$_pluginLoader;
    }

    protected static function _getDefaultHandlerPath()
    {
        $defaultPath = implode(DIRECTORY_SEPARATOR, array(LIBRARY_PATH, 'FinalView', 'Access', 'Handler'));
        $defaultPrefix = 'FinalView_Access_Handler';

        return array($defaultPrefix => $defaultPath);
    }
}