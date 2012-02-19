<?php

class Application_Access_Handler_NotDetectedUser extends FinalView_Access_Handler_Abstract_Complex
{    protected $_defaultHandlerName = 'NotFound';

    protected function _getMapping()
    {        return array(
            'NotFound'        =>    array('user_exist'),
            'RedirectToLogin' =>    array('logged_in')
        );    }
}