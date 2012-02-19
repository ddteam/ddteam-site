<?php

class Application_Access_Handler_NotFoundIfAdminAuthorized extends FinalView_Access_Handler_Abstract_Complex
{    protected $_defaultHandlerName = 'Forbidden';

    protected function _getMapping()
    {        return array(
            'RedirectToAdminLogin'    =>    array('admin_logged_in')
        );    }
}