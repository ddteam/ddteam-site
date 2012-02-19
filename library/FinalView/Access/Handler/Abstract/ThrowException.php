<?php

abstract class FinalView_Access_Handler_Abstract_ThrowException extends FinalView_Access_Handler_Abstract
{

    public function runHandler()
    {
        $message = $this->_getErrorMessage();
        $code = $this->_getReturnCode();

        if (empty($message)) {
            $message = $code;
        }

        throw new FinalView_Exception($message, $code);
    }

    abstract protected function _getReturnCode();
}