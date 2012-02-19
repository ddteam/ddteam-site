<?php

class FinalView_Access_Handler_Forbidden extends FinalView_Access_Handler_Abstract_ThrowException
{

    protected function _getReturnCode()
    {
        return 403;
    }

}