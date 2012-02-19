<?php

class FinalView_Mail_Template_Script implements FinalView_Mail_Template_Interface
{
    protected $_path;
    protected $_template;
    protected $_subject;
    protected $_bodyText;
    protected $_view;

    public function __construct($path, $subject = '', $bodyText = '')
    {
        $this->_path = $path;
        $this->_subject = $subject;
        $this->_bodyText = $bodyText;
        $this->_view = new Zend_View();
        $this->_view->addScriptPath(PUBLIC_PATH);
    }

    public function getSubject()
    {
        return $this->_subject;
    }
    
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    public function getBodyText(){
        return $this->_bodyText;
    }
    
    public function setBodyText($bodyText)
    {
        $this->_bodyText = $bodyText;
        return $this;
    }

    public function getBodyHtml(){
        return $this->_getTemplate();
    }
    
    private function _getTemplate()
    {
        if ($this->_template === null) {
            if (!file_exists($this->_path)) {
                throw new FinalView_Mail_Exception('Template ' . $this->_path . 'couldn\'t be found');
            }
            
            $this->_template = $this->_view->render($this->_path);
        }
        return $this->_template;
    }
    
    public function getView()
    {
        return $this->_view;
    }

}