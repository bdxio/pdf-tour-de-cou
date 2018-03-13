<?php

/**
 * Created by IntelliJ IDEA.
 * User: remigoyard
 * Date: 03/10/2015
 * Time: 19:17
 */
class PdfController  extends Zend_Controller_Action
{

    public function indexAction(){


        $file_name = $this->getRequest()->getParam('key');
        $file = "/tmp/".$file_name;
        if(is_file($file)){
            // disable the view ... and perhaps the layout
            $this->view->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            header('Content-Type: image/jpeg');
            header('Content-Disposition: attachment; filename="logo.jpg"');
            readfile($file);
        }

    }
}