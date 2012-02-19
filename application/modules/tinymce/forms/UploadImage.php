<?php

class Tinymce_Form_UploadImage extends Zend_Form
{    
    const ALLOWED_EXTENSIONS = 'jpg,png,gif,jpeg';    
    
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
        $fileElement = new Zend_Form_Element_File('image_file');
        $fileElement->setLabel('Image')            
            ->addValidator('IsImage')
            ->addValidator('Size', false, array(
                'max' => FinalView_Config::get('tinymce', 'photo_max_size'),
                'bytestring' => false))
            ->addValidator('Extension', false, self::ALLOWED_EXTENSIONS)
            ->setDescription('Max allowed file size: ' . 
                FinalView_Config::get('tinymce', 'photo_max_size'))
        ;
    	$fileElement->setRequired();
        $fileElement->getValidator('Zend_Validate_File_Upload')->setMessages(array(
            Zend_Validate_File_Upload::NO_FILE  =>  'You should select Image'                
        ));
        
        $this->addElement($fileElement);                   
                
        $element = new Zend_Form_Element_Submit('submit');
        $element
            ->setLabel('Upload Image')
            ->setIgnore(true)
            ;
        $this->addElement($element);                
    }
}
