<?php

class Tinymce_ImageController extends FinalView_Controller_Action
{
    
    const ADD_IMAGE_SUCCESS_MESSAGE = 'ADD_IMAGE_SUCCESS_MESSAGE';
    
    private $uploadForm;

    public function indexAction()
    {        
        if ($this->getRequest()->isPost()) {
        	$uploadForm = $this->getUploadForm();
            if ($uploadForm->isValid($this->getRequest()->getPost())) {
                if ($uploadForm->image_file->isUploaded()) {
                    $uploadForm->image_file->receive();
                    $newImage = new TinyMceImages;
                    
                    
                    print_r($uploadForm->image_file);
                    exit();
                    
                    $newImage->setFile($uploadForm->image_file);
                    $this->setRecordData($newImage);
                    $newImage->save();
                    
                    $this->getHelper('FlashMessenger')
                        ->addMessage(__(self::ADD_IMAGE_SUCCESS_MESSAGE));
                    $this->_helper->redirector->gotoRoute(array(), 'TinymceImageIndex');                                                                       
                }                                 
            }
        }
        $this->_assignImages();                
        $this->view->uploadForm = $this->getUploadForm();
    }
    
    public function deleteAction()
    {
        $image = Doctrine::getTable('TinyMceImages')->findByParams(
            $this->getImageSelectors() + array('id' =>  $this->getRequest()->getParam('id', -1))
        );
        if($image){
            $image->delete();                    
        }
        $this->_helper->redirector->gotoRoute(array(), 'TinymceImageIndex');       
    }
    
    private function _assignImages()
    {
        $this->view->images = Doctrine::getTable('TinyMceImages')->findByParams(
            $this->getImageSelectors()
        );
    }
    
    private function getUploadForm()
    {
        if ($this->uploadForm === null) {
        	$this->uploadForm = new Tinymce_Form_UploadImage();
        }
        
        return $this->uploadForm;
    }
    
    private function getImageSelectors()
    {
        return array(
        );
    }
    
    private function setRecordData($image)
    {        
        return $image;
    }        
}
