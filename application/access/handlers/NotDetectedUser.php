<?php

class Application_Access_Handler_NotDetectedUser extends FinalView_Access_Handler_Abstract_Complex
{

    protected function _getMapping()
    {
            'NotFound'        =>    array('user_exist'),
            'RedirectToLogin' =>    array('logged_in')
        );
}