<?php

class Application_Access_Handler_NotFoundIfAdminAuthorized extends FinalView_Access_Handler_Abstract_Complex
{

    protected function _getMapping()
    {
            'RedirectToAdminLogin'    =>    array('admin_logged_in')
        );
}