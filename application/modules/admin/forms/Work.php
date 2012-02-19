<?php

class Admin_Form_Work extends Zend_Form
{
    
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
    	
    	$cat = Doctrine::getTable('Category')->findByParams(array(
            'status' => 1
        ));
        foreach ($cat as $key=>$value) {
        	$category_list[$value->id] = $value->title; 
        } 
        unset($cat);
    	
        $element = new Zend_Form_Element_Text('name');
        $element
            ->setLabel('Name')
            ->setRequired()
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('date');
        $element
            ->setLabel('Date')
            ->setRequired()
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);

        
        $element = new Zend_Form_Element_Select('category_id');
        $element
            ->setLabel('Category')
            ->setRequired()
            ->addMultiOptions(array($category_list))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
    	$element = new Zend_Form_Element_Select('status');
        $element
            ->setLabel('Status')
            ->addMultiOptions(array(0=>'В работе',1=>'Готовая'))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
                $element = new Zend_Form_Element_Text('titlehead');
        $element
            ->setLabel('Title')
            ->setRequired()
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Textarea('keywordshead');
        $element
            ->setLabel('Keywords')
            ->setRequired()
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Textarea('descriptionhead');
        $element
            ->setLabel('DescriptionHead')
            ->setRequired()
            ;
        $this->addElement($element);

        $element = new FinalView_Form_Element_WYSIWYG('description');
        $element
            ->setLabel('Description')
            ->setRequired()
            ;
        $this->addElement($element);
        
        for ($i=1;$i<=30;$i++) {
        $element = new Zend_Form_Element_Text("$i");
        $element
            ->setLabel('Image '.$i)
            ->setBelongsTo('file')
            ->setAttribs(array('readonly'=>''))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);    
        }    
        
        
        $element = new Zend_Form_Element_Submit('submit');
        $element
            ->setLabel('Submit')
            ->setIgnore(true)
            ;
        $this->addElement($element);
    }
    
}