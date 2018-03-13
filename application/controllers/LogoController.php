<?php

class LogoController extends Zend_Controller_Action {


    public function index(){

    }

    public function logoAction(){
        $service = new Model_Service();
        $service->getAllParticipants();
    }
}