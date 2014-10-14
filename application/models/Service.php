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
        //return $this->getDataFromCsv();
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




    /**
     * Get some demos datas
     *
     * @return array
     */
    public function getDataDemo()
    {

        return array(
            array(
                "prenom" => "Marie-aline",
                "nom" => "Camblor",
                "type" => 'orga'
            ),

            array(
                "prenom" => "Christian Alonso",
                "nom" => "Chavez Ley",
                "type" => 'participant'
            ),
            array(
                "prenom" => "Nicolas",
                "nom" => "De Loof",
                "type" => 'speaker'
            ),

            array(
                "prenom" => "Jérémy",
                "nom" => "Morin",
                "type" => "etudiant"
            )

        );
    }

    /**
     * Return data from CSV
     * @return array
     */
    public function getDataFromCsv($csvfile)
    {
        $file = APPLICATION_PATH . '/datas/'.$csvfile;

        $lines = array();
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                array_push($lines, $data);
            }
            fclose($handle);
        }
        $res = array_filter($lines, function ($line) {
            $blackListvalue = array('');
            $blackListRegexp = array(
                '/(orgas|total|jury|helpers|speakers|invit|sponsor|Ajout|etudiants|early|fin|standards).*/i'
            );
            if (!in_array($line[0], $blackListvalue)) {
                foreach ($blackListRegexp as $reg) {
                    if (preg_match($reg, $line[0])) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        });
        $datas = array();
        foreach($res as $k=>$v){
            if( ! in_array($v[2], array(1,2,3,4,5,6,7,8)) ){
                array_push($datas, new Model_People($v[2], $v[1], $v[5]));
            }

        }
        return $datas;
    }
} 