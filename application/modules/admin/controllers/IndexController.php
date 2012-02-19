<?php

class Admin_IndexController extends FinalView_Controller_Action
{ 
	
    public function indexAction() 
    {
    
    }
    
    public function delcatAction()

   public function upAction()
    {
        $this->_move('up');
    }

    public function downAction()
    {
        $this->_move('down');
    }

	
}



