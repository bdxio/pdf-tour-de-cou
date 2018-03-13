<?php
/**
 * Created by IntelliJ IDEA.
 * User: remi
 * Date: 05/10/2014
 * Time: 18:50
 */

class Model_Service {

    public function __construct(){

    }

    public function getAllParticipants(){
         return $this->getFromSheet();
    }


    /**
     * Return from Google Spreadsheet
     *
     * @return array
     */
    public function getFromSheet(){
        $sheet = new Model_Sheet();
        return $sheet->getDatas();
    }

} 