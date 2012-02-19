<?php
class Default_View_Helper_Headertag extends Zend_View_Helper_Abstract
{
    public function headertag($item)
    {
        $this->view->headMeta()->appendName('description', $item->descriptionhead);
        $this->view->headMeta()->appendName('keywords', $item->keywordshead);
        $this->view->headTitle()->append($item->titlehead);
    }
}