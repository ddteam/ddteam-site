<?php
class Admin_Grid_Categories extends Admin_Grid_Base
{
    public function __construct()
    {
        parent::__construct(array(
            'model' => 'Category',
            'filter'    =>  array(
        		'order_by' => array('field'=>'position','direction'=>'asc')
            ),
            
        
        ));

        $router = Zend_Controller_Front::getInstance()->getRouter();
        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'edit_action', 
                'Edit',
                $router->getRoute('AdminIndexEditcategory'), 
                array('id' => 'id')
            )
        );        
        $this->getColumns()->removeColumn('descriptionhead');
        $this->getColumns()->removeColumn('keywordshead');
        $this->getColumns()->removeColumn('titlehead');
        $this->getColumns()->removeColumn('position');
        $this->getColumns()->removeColumn('ids');
        $this->getColumns()->removeColumn('title');
        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'up',
                'Up',
                $router->getRoute('AdminCategoryUp'),
                array('id' => 'id')
            )
        );

        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'down',
                'Down',
                $router->getRoute('AdminCategoryDown'),
                array('id' => 'id')
            )
        );
        
       $this->addColumn(
                 new FinalView_Grid_Column_Iterator(
                     'Del'
                 )
    	);
	    
        $this->addPlugin(new FinalView_Grid_Plugin_Sortable(array(
            'id', 'title'
        )));
        
        $this->addPlugin(new FinalView_Grid_Plugin_Gridactions(array(
            array('type'    =>  'submit', 'value' => 'New category', 'name' => 'newcategory'),
        )));
        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'title', 
                '',
                $router->getRoute('AdminWorkIndex'), 
                array('category_id' => 'id')
            ),
            FinalView_Grid_ColumnsCollection::APPEND_AFTER_COLUMN,
            'id'
        ); 
        
        $this->getRenderer()->addScriptPath(APPLICATION_PATH . "/" . "modules/admin/views/grid");
       
    }
    
    public function statusHandler($params, FinalView_Grid_Renderer $view)
    {
        if ($params->status) { $view->value = 'Active';}
        else {$view->value = 'Inactive';}
    }
    
    public function titleHandler($params, $view) {
    	$this->getColumns()->title->handler($params, $view);
    	$view->label = $params->title;
    }
    
    public function upHandler($params, $view) {
    	$this->getColumns()->up->handler($params, $view);
	    $view->setScript("up.phtml");
    }
    public function downHandler($params, $view) {
	    $this->getColumns()->down->handler($params, $view);
    	$view->setScript("down.phtml");
    }

    //        $this->addColumn(
//	            new FinalView_Grid_Column_Action(
//	                'Del', 
//	                'Delete',
//	                $router->getRoute('AdminIndexDelcat'), 
//	                array('id' => 'id')
//	            )
//	    );
    
    public function DelHandler($params, $view) {
     	$count =  Doctrine::getTable('Work')->countByParams(array(
            'category_id' =>  $params->id
        ));
        if ($count == 0) {$view->value = '<a href="/admin/'.$params->id.'/delcategory.html">Delete</a>';} else {$view->value = '';}
    }
}
