<?php

class Application_Access_Handler_RedirectToLogin extends FinalView_Access_Handler_Redirect_Abstract
{
    protected function _getRedirectRoute()
    {

    protected function _getUrlParams()
    {
            ? array('back_url' => $_SERVER['REQUEST_URI'])
            : array();

        return $option;
}