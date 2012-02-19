<?php

class IndexController extends FinalView_Controller_Action
{

    public function indexAction()
    {
        
    }
    
    public function worksviewAction()
    {
        $this->view->works = Doctrine::getTable('Work')->findByParams(array(
            'status'   =>  1,
            'orderBy' => array('field' => 'id', 'direction' => 'DESC'),
        ));
    }
    
    public function workviewAction()
    {
        $work_id = $this->_getParam('work_id');
        $this->view->work = $work = Doctrine::getTable('Work')->findOneByParams(array(
            'id'   =>  $work_id
        ));
    }
}

