<?php

/**
 * This is fucking hell!
 * 
 * @example "#2 "QUOTE"Priority Listing"QUOTE"" -> #2 "Priority Listing"
 */
define('QUOTE', '"');

abstract class FinalView_Config
{
    const CONFIG_FILENAME_INI = 'config.ini';
    const CONFIG_FILENAME_YAML = 'config.yml';

    /**
     * Modules config params
     * 
     * @var array
     */
    static private $_cach = array();

    /**
     * Return config value(s)
     * 
     * @example 
     * 1. param is string then return $value or $default|null
     * 2. param is index of array then return $values or $default|null.
     * Can be string or array. String format: x.x.x
     * 3. param is associated array then return $values or default values (values of given array)
     * 
     * param(array('v1', 'v2' => 'default', 'v3', ...)), default = 'def' - used if the default value is not specified
     * 
     * if param array('v1.v2', 'v1', ...), output: array('v1.v2' => 'val', 'v1' => 'val')
     * 
     * @param string $module
     * @param mixed $param
     * @param mixed $default
     * @return mixed array, string or NULL
     */
    static public function get($module, $param, $default = null)
    {
        $module = strtolower($module);

        if (!array_key_exists($module, self::$_cach)) {
            self::_load($module);
        }

        $config = self::$_cach[$module];

        $output = array();
        $outFlag = 1;

        if (!is_array($param)) {
            $param = (array) $param;
            $outFlag = 0;
        }

        foreach ($param as $param_or_index => $default_or_param) {

            $str = '';

            if (is_numeric($param_or_index)) {
                $parameter = $default_or_param;
                $def = $default;
            } else {
                $parameter = $param_or_index;
                $def = $default_or_param;
            }

            $tmp = explode('.', $parameter);
            foreach ($tmp as $tmpV) {
                $str .= "['{$tmpV}']";
            }

            eval("\$output['$parameter'] = \$config$str;");

            if ($output[$parameter] === NULL) {
                $output[$parameter] = $def;
            }
        }

        //if param array - return with request key
        return $outFlag ? $output : reset($output);
    }

    /**
     * Load given module config params
     * 
     * @param string $module
     */
    static private function _load($module)
    {
        if (file_exists($file = APPLICATION_PATH . '/modules/' . $module . '/' .
                        self::CONFIG_FILENAME_INI)) {
            $config = new Zend_Config_Ini($file);
            self::$_cach[$module] = $config->toArray();
        } elseif (file_exists($file = APPLICATION_PATH . '/modules/' . $module . '/' .
                        self::CONFIG_FILENAME_YAML)) {
            $config = Doctrine_Parser::load($file, 'yml');
            self::$_cach[$module] = $config;
        }
    }

    static public function factory($config = array(), $section= null, $options = false)
    {
        if (is_string($config) and file_exists($config)) {
            $suffix = strtolower(pathinfo($config, PATHINFO_EXTENSION));
            
            switch ($suffix) {
                case "ini":
                    $configObj = new Zend_Config_Ini($config, $section, $options);
                    break;
                case "xml":
                    $configObj = new Zend_Config_Xml($config, $section, $options);
                    break;
                case "json":
                    $configObj = new Zend_Config_Json($config, $section, $options);
                    break;
                case "yaml":
                    $configObj = new Zend_Config_Yaml($config, $section, $options);
                    break;
                case "yml":
                    $configObj = new Zend_Config_Yaml($config, $section, $options);
                    break;
                case "php":
                case "inc":
                    $configArr = include $config;
                    if (!is_array($config)) {
                        throw new Zend_Config_Exception('Invalid configuration file provided; PHP file does not return array value');
                    }
                    $configObj = new Zend_Config($configArr, $options);
                    break;
                default:
                    throw new Zend_Config_Exception("Invalid configuration file provided; unknown config type");
                    break;
            }
        } elseif (is_array($config)) {
            $configObj = new Zend_Config($config, $options);
        } else {
            throw new Zend_Config_Exception("Invalid configuration file provided; unknown config type");
        }
        return $configObj;
    }

}

abstract class Config extends FinalView_Config
{
    
}
