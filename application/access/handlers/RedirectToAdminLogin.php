<?php

class Application_Access_Handler_RedirectToAdminLogin extends FinalView_Access_Handler_Redirect_Abstract
{    protected function _getRedirectRoute()
    {        return 'AdminAuthLogin';    }
}