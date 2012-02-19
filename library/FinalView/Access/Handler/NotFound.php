<?php

class FinalView_Access_Handler_NotFound extends FinalView_Access_Handler_Abstract_ThrowException
{

    protected function _getReturnCode()
    {
        return 404;
    }

}