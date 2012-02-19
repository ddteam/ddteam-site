<?php
class Application_AccessRules_TinyMce extends FinalView_Access_Rules_Abstract
{

    public function imageInParamsRule()
    {
        return array_key_exists('id', $this->_params);
    }
    
    public function imageExistRule()
    {        
        $image = Doctrine::getTable('TinyMceImages')->findByParams(
            array('id' =>  $this->_params['id'])
        );
        
        if ($image) return true;
        
        return false;
    }  
}
