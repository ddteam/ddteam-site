<?php

class Tinymce_Bootstrap extends FinalView_Application_Module_Bootstrap 
{ 

    public function init()
    {
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/');
        
        $layout->setLayout('tinymce');        
    }
}