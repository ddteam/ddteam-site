<?php

class FinalView_Encrypt_Mcrypt
{

    protected static $_instance = null;
    private $_key = 'default key';
    private $_algorithm = MCRYPT_RIJNDAEL_128;
    private $_mode = MCRYPT_MODE_ECB;
    private $_size = MCRYPT_DEV_RANDOM;
    private $_iv = false;

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct()
    {
        
    }

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {
        
    }

    /**
     * Returns an instance of Debug
     *
     * Singleton pattern implementation
     *
     * @return Debug 
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->_generateIV();
        }

        return self::$_instance;
    }

    /**
     * Magic geter
     * 
     * @param string $name
     * @return mixed Variable or null if var not exist
     */
    public function __get($name)
    {
        $name = '_' . $name;

        return $this->_getClassVar($name) ? $this->$name : null;
    }

    public function __set($name, $value)
    {
        $name = '_' . $name;

        if ($this->_getClassVar($name)) {
            $this->$name = $value;

            switch ($name) {
                case '_algorithm':
                    $this->_generateIV();
                    break;
                case '_size':
                    $this->_generateIV();
                    break;
                case '_mode':
                    $this->_generateIV();
                    break;
                default:
                    break;
            }
        } else {
            throw new Exception('Variable not exist');
        }
    }

    /**
     * Get true if var exist.
     * 
     * @param string $name
     * @return bool 
     */
    private function _getClassVar($name)
    {
        return array_key_exists($name, get_class_vars(get_class($this)));
    }

    /**
     * Encrypt value
     * 
     * @param string $value
     */
    public function encrypt($string)
    {
        $td = mcrypt_module_open($this->_algorithm, '', $this->_mode, '');
        mcrypt_generic_init($td, $this->_key, $this->_iv);
        $encrypted_data = mcrypt_generic($td, $string);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $encrypted_data;
    }

    /**
     * Decrypt value
     * 
     * @param string $value
     */
    public function decrypt($string)
    {
        $td = mcrypt_module_open($this->_algorithm, '', $this->_mode, '');
        mcrypt_generic_init($td, $this->_key, $this->_iv);
        $decrypted_data = mdecrypt_generic($td, $string);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return trim($decrypted_data);
    }

    private function _generateIV()
    {
        $td = mcrypt_module_open($this->_algorithm, '', $this->_mode, '');
        $this->_iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), $this->_size);
        mcrypt_module_close($td);
    }

}
