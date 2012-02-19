<?php

/**
* Confirmation_Controller
*
*/
class Confirmation_IndexController extends FinalView_Controller_Action
{

    public function acceptAction()
    {
        $confirmation = $this->_helper->confirmation();

        $filter = new Zend_Filter();
        $filter->addFilter(new Zend_Filter_Word_CamelCaseToDash())
               ->addFilter(new Zend_Filter_StringToLower());

        $this->_forward($confirmation->confirmation_type, $filter->filter($confirmation->entity_model), 'confirmation', array(
            'hash'  =>  $this->getRequest()->getParam('hash'),
            'reply' =>  Confirmation::ACTION_ACCEPT
        ));
    }

    public function declineAction()
    {
        $confirmation = $this->_helper->confirmation();

        $filter = new Zend_Filter();
        $filter->addFilter(new Zend_Filter_Word_CamelCaseToDash())
               ->addFilter(new Zend_Filter_StringToLower());

        $this->_forward($confirmation->confirmation_type, $filter->filter($confirmation->entity_model), 'confirmation', array(
            'hash'  =>  $this->getRequest()->getParam('hash'),
            'reply' =>  Confirmation::ACTION_DECLINE
        ));
    }
}
