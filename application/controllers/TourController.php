<?php
/**
 * Created by IntelliJ IDEA.
 * User: remi
 * Date: 13/10/2014
 * Time: 11:39
 */

class TourController extends Zend_Controller_Action
{

    const VERSION = "v5";
    private $service = null;
    private $directory = '';

    private $imgPath;

    private $fileName;

    public function init()
    {
        /* Initialize action controller here */
        $this->service = new Model_Service();
        $this->directory = realpath(APPLICATION_PATH . "/../public/pdf/");
        $this->imgPath = realpath(APPLICATION_PATH . "/datas/images/");
        $this->fileName = "PDF-TOURDECOU-A6-" . self::VERSION . " - " . date('dmYHis', time());
    }


    public function indexAction()
    {
        ini_set("memory_limit", "1024M");
        // Create FileName
        $this->createPdf();

        $files = glob($this->directory . '/*.pdf');
        sort($files);
        $this->view->files = $files;
    }


    /**
     * Remove all Created Files
     */
    public function deleteAction()
    {

        $files = glob($this->directory . '/*.(pdf|csv)');
        foreach ($files as $file) {
            unlink($file);
        }
        $this->redirect('/');
    }


    public function updateAction(){
        $file = $this->getRequest()->getParam("file");
        $pdf = $this->directory .'/'.$file;
        $fileCSV = str_replace('.pdf', '.csv', $pdf);
        if( is_file($fileCSV) ){

        } else {
            echo "KO";
        }

    }

    /**
     * retreive data from service
     * @return mixed
     */
    private function getDatas()
    {
        return $this->service->getAllParticipants();
    }


    /**
     * @param $participant
     * @return string
     */
    private function getImage($participant)
    {
        $image = '';
        switch (strtolower($participant->getType())) {
            case 'orga';
                $image = $this->imgPath .'/badge-orga.jpeg';
                break;
            case 'etudiant';
                $image = $this->imgPath .'/badge-etudiant.jpeg';
                break;
            case 'speaker';
                $image = $this->imgPath .'/badge-speaker.jpeg';
                break;
            case 'participant':
                $image = $this->imgPath .'/badge-participant.jpeg';
                break;
            default:
                $this->view->logged[] = $participant;
                return $image;

        }
        return $image;
    }


    private function getPdfFile($i = 0)
    {

        $fileName = $this->fileName . '-'.$i.'.pdf';
        return $this->directory .'/'.  $fileName;
    }

    private function getCsvFile()
    {
        $fileName = $this->fileName . '.csv';
        return $this->directory .'/'. $fileName;
    }

    private function createPdf()
    {
        $cartons = new Model_Cartons();
        $this->view->logged = array();
        $printed = array();
        $datas = $this->getDatas();

        $step = 500;
        $limit = $step;
        $i=0;
        while($i < count($datas) ){
            $participant = $datas[$i];
            $carton = new Model_Carton(Model_Carton::PAGE_SIZE_A6);
            $image = $this->getImage($participant);

            if ($image == '') {
                $this->view->logged[] =  $participant->__toArray();
                continue;
            } else {
                $carton->drawCard($participant->formatPrenom(), $participant->formatNom(), $image);
                $cartons->add($carton);
                array_push($printed, $participant->__toArray());
            }
            if( $i > $limit){
                $limit += $step;
                $cartons->save($this->getPdfFile($i));
                $cartons = new Model_Cartons();

            }


            $i++;
        }

        $cartons->save($this->getPdfFile());
        //$cartons->save($this->getPdfFile());
        $this->createCSV($printed);
    }

    private function createCSV($printed)
    {
        $f = fopen($this->getCsvFile(), 'w');
        foreach($printed as $v){
            fputcsv($f, $v);
        }
    }


}