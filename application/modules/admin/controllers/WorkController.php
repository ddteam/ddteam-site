<?php
class Admin_WorkController extends FinalView_Controller_Action
{

    private $_WorkForm;

    public function indexAction()
    {
    }

    public function editworkAction()
    {
    }

    public function newworkAction()
    {
        if ($this->getRequest()->isPost())
        {
            if ($this->getWorkForm()->isValid(
            $this->getRequest()->getPost()))
            {
                $Work = $this->saveWork();
                $Work->save();
                $Workdir = $_SERVER['DOCUMENT_ROOT'] . '/images/works/' .
                 $Work->id . '/';
                if (! is_dir($Workdir))
                {
                    mkdir($Workdir, 0777);
                }
                //$uploadedData = $this->getWorkForm()->getValues();
                //$fullFilePath = $this->getWorkForm()->file->getFileName();
                $fullFilePath = array();
                $newfilename = array();
                $newdest = array();
                foreach ($this->_getParam('file') as $key => $value)
                {
                    if (! empty($value))
                    {
                        //if ()
                        $fullFilePath[$key] = $_SERVER['DOCUMENT_ROOT'] .
                         $value;
                        $newfilename[$key] = $Workdir .
                         $Work->id .
                         '_' .
                         $key .
                         '.jpg';
                        $image = "image$key";
                        $Work->$image = $Work->id .
                         '_' .
                         $key .
                         '.jpg';
                        if ($key ==
                         1)
                        {
                            $firstfile = $Workdir .
                             $Work->id .
                             '_first.jpg';
                            copy(
                            $fullFilePath[$key], 
                            $firstfile);
                            system(
                            "mogrify -resize 480x270 $firstfile");
                        }
                        rename(
                        $fullFilePath[$key], 
                        $newfilename[$key]);
                        system(
                        "mogrify -resize 1000x562 $newfilename[$key]");
                    }
                }
                $Work->save();
                $this->_helper->redirector->gotoRoute(
                array('id' => $Work->id), 
                'AdminWorkView');
            }
        }
        $Work = $this->getWorkForm();
        $this->view->form = $Work;
    }

    public function delworkAction()
    {
        $id = $this->_getParam('id');
        $Work = Doctrine::getTable('Work')->findOneByParams(
        array('id' => $id));
        $cat_id = 1;
        if (! empty($Work->id))
        {
            $cat_id = $Work->category_id;
            system("rm -f -r /var/www/doit/www/images/works/$Work->id");
            //echo "rm -f -r /var/www/www/Works/$Work->id";
            $Work->delete();
        }
        $this->_helper->redirector->gotoRoute(array('category_id' => $cat_id), 
        'AdminWorkIndex');
        //system ()
    }

    public function viewworkAction()
    {
        $work_id = $this->_getParam('id');
        $this->view->work = $work = Doctrine::getTable('Work')->findOneByParams(
        array('id' => $work_id));
    }

    public function deleteimageAction()
    {
        $this->_helper->layout()->disableLayout();
        $work_id = $this->_getParam('work_id');
        $image_id = $this->_getParam('image_id');
        if (! empty($work_id))
        {
            $work = Doctrine::getTable('Work')->findOneByParams(
            array('id' => $work_id));
            $image = "image".$image_id;
            $work->$image = null;
            $work->save();
            $filename = $work->id."_".$image_id.".jpg"; 
            system("rm -f -r /var/www/doit/www/images/works/$work->id/$filename");
            return $filename;
        } else {
            return 0;
        }
    }

    private function getWorkForm()
    {
        if ($this->_WorkForm === null)
        {
            $this->_WorkForm = new Admin_Form_Work();
        }
        return $this->_WorkForm;
    }

    private function saveWork($cat = null)
    {
        if ($this->getRequest()->isPost())
        {
            if ($this->getWorkForm()->isValid(
            $this->getRequest()->getPost()))
            {
                if (is_null($cat))
                {
                    $cat = Doctrine::getTable(
                    'Work')->create();
                }
                $cat->merge(
                $this->getWorkForm()->getValues());
                return $cat;
            }
        }
        $this->view->form = $this->getWorkForm();
    }

    public function upAction()
    {
        $this->_move('up');
    }

    public function downAction()
    {
        $this->_move('down');
    }

    private function _move($direction)
    {
        $record = Doctrine::getTable('Work')->findOneByParams(
        array('id' => $this->_getParam('id')));
        if (! $record)
        {
            $this->_helper->error->notFound();
        }
        switch ($direction) {
            case 'up':
                $record->moveUp();
                break;
            case 'down':
                $record->moveDown();
                break;
            default:
                trigger_error('Unknown direction', 
                E_USER_ERROR);
        }
        //        print_r($record->toArray());
        //        exit();
        $this->_helper->redirector->gotoRoute(
        array(), 'AdminWorkIndex');
    }
}



