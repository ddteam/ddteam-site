<?php

class Application_Access_Handler_RedirectToLogin extends FinalView_Access_Handler_Redirect_Abstract
{
    protected function _getRedirectRoute()
    {        return 'UserAuthLogin';    }

    protected function _getUrlParams()
    {        $option = isset($_SERVER['REQUEST_URI'])
            ? array('back_url' => $_SERVER['REQUEST_URI'])
            : array();

        return $option;    }
}