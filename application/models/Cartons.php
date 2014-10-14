<?php

class Model_Cartons{

    /*
    // A4
    const NAME_X = 150;
    const NAME_Y = 600;
    const FONT_SIZE = 36;
    const LINE_SPACING = 15;
    */


    private $pdf;

    public function __construct(){
        $this->pdf = new Zend_Pdf();
    }

    public function add(Model_Carton $carton){
        $this->pdf->pages[] = $carton;
    }

    public function save($filename = "file.pdf"){
        $pdf_Str = $this->pdf->render();
        file_put_contents($filename, $pdf_Str);
    }



}