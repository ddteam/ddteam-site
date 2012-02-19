<?php
class Admin_Grid_Works extends Admin_Grid_Base
{
    public function __construct($category_id)
    {
    	
    	if (!empty($category_id))
    	{
        parent::__construct(array(
            'model' => 'Work',
            'filter'    =>  array(
        		'category_id' => $category_id,
        		'order_by' => array('field'=>'position','direction'=>'asc')
            ),
        ));
    	} else {
    	parent::__construct(array(
            'model' => 'Work',
            'filter'    =>  array(
        		'order_by' => array('field'=>'position','direction'=>'asc')
            ),
        ));	
    	}
        $router = Zend_Controller_Front::getInstance()->getRouter();

        
          $this->addColumn(
            new FinalView_Grid_Column_Action(
                'up',
                'Up',
                $router->getRoute('AdminWorkUp'),
                array('id' => 'id')
            )
        );
        
        $this->getColumns()->removeColumn('descriptionhead');
        $this->getColumns()->removeColumn('keywordshead');
        $this->getColumns()->removeColumn('titlehead');
		
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'down',
                'Down',
                $router->getRoute('AdminWorkDown'),
                array('id' => 'id')
            )
        );
        
        $this->getColumns()->removeColumn('image1');
        $this->getColumns()->removeColumn('image2');
        $this->getColumns()->removeColumn('image3');
        $this->getColumns()->removeColumn('image4');
        $this->getColumns()->removeColumn('image5');
        $this->getColumns()->removeColumn('image6');
        $this->getColumns()->removeColumn('image7');
        $this->getColumns()->removeColumn('image8');
        $this->getColumns()->removeColumn('image9');
        $this->getColumns()->removeColumn('image10');
        $this->getColumns()->removeColumn('image11');
        $this->getColumns()->removeColumn('image12');
        $this->getColumns()->removeColumn('image13');
        $this->getColumns()->removeColumn('image14');
        $this->getColumns()->removeColumn('image15');
        $this->getColumns()->removeColumn('description');
        $this->getColumns()->removeColumn('created_at');
        $this->getColumns()->removeColumn('name');
        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'edit', 
                'Edit',
                $router->getRoute('AdminWorkEdit'), 
                array('id' => 'id')
            )
        );        
	        $this->addColumn(
	            new FinalView_Grid_Column_Action(
	                'Del', 
	                'Delete',
	                $router->getRoute('AdminWorkDelwork'), 
	                array('id' => 'id')
	            )
	        );               

        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'name', 
                '',
                $router->getRoute('AdminWorkView'), 
                array('id' => 'id')
            ),
            FinalView_Grid_ColumnsCollection::APPEND_AFTER_COLUMN,
            'category_id'
        ); 
        
        $this->addPlugin(new FinalView_Grid_Plugin_Sortable(array(
            'id', 'status', 'category_id', 'name'
        )));
        
        $this->addPlugin(new FinalView_Grid_Plugin_Gridactions(array(
            array('type'    =>  'submit', 'value' => 'New Work', 'name' => 'newWork'),
        )));
        
        
        $this->getColumns()->setColumnsOrder(array('up','down','name','status','id','category_id','updated_at','edit','Del'));
        $this->addPlugin(new FinalView_Grid_Plugin_Colspan(array('up'=>3)));

        $this->getRenderer()->addScriptPath(APPLICATION_PATH . "/" . "modules/admin/views/grid");
    }
    
    public function upHandler($params, $view) {
    	$this->getColumns()->up->handler($params, $view);
	    $view->setScript("up.phtml");
    }
    public function downHandler($params, $view) {
	    $this->getColumns()->down->handler($params, $view);
    	$view->setScript("down.phtml");
    }
    
    public function nameHandler($params, $view) {
    	$this->getColumns()->name->handler($params, $view);
    	$view->label = $params->name;
    }

    public function ColumnHandler($params, $view) {
    	parent::ColumnHandler($params, $view);
    	if($params['column']->getName() == 'Del') {
    		$view->td_attribs = array('class' => 'column deleteLink');

    	}    	
    }
    
    public function statusHandler($params, FinalView_Grid_Renderer $view)
    {
        if ($params->status) { $view->value = 'Готовая';}
        else {$view->value = 'В работе';}
    }

    public function categoryIdHandler($params, FinalView_Grid_Renderer $view)
    {
    	$cat =  Doctrine::getTable('Category')->findOneByParams(array(
            'id' =>  $params->category_id
        ));
        $view->value = $cat->title;        
    }
    
    
}
