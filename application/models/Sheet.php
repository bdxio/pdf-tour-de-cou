<?php


/**
 * User: remi
 * Date: 05/10/2014
 * Time: 18:49
 */
class Model_Sheet
{
    private $NB_BADGES_VIDES = 50;

    public function __construct()
    {

    }

    public function getDatas()
    {
        $debug = false;
        $datas = array();
        $file = APPLICATION_PATH . "/datas/inscrits.csv";
        if( $_REQUEST['file'] && $_REQUEST['file'] !== ''){
            $file = APPLICATION_PATH . "/datas/".$_REQUEST['file'];
        }
        


        if (is_file($file)) {
            $row = 1;
            if (($handle = fopen($file, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    $prenom = $data[1];
                    $nom = $data[2];
                    if( $prenom == '' && $nom == ''){
                        continue;
                    }
                    $typebadge = trim(strtolower($data[5]));
                    $aimprimerlorsduprochainbatch = $data[9];
                    $societe = trim($data[6]);
                    $logoUrl = trim($data[7]);
                    $login = trim($data[15]);
                    $password = trim($data[16]);
                    $logo = '';
                    if ($logoUrl != '') {
                        $logo = $this->getImageFromUrl($logoUrl, $societe);
                    }

                    if ($debug) {
                        echo $prenom . " - " . $nom . ' - ' . $typebadge . '-' . $aimprimerlorsduprochainbatch . ' - ';
                        echo $societe . " - " . $logo . "<br />";
                    }


                    if ($typebadge !== '') {
                        //   echo "ee";
                        // Remove this line if you want to print all sheets
                        if ($aimprimerlorsduprochainbatch == 1) {
                            if (!$debug) {
                                array_push($datas, new Model_People($nom, $prenom, $typebadge, $societe, $logo, $login, $password));
                            }
                        }
                    }
                    $row++;
                }
            }
            fclose($handle);
        } else {
            throw new Exception('File '.$file.' does not exists');
        }


        /*
         * Sort data by name,
         * Commented, because it's easier to find charly ...
            usort($datas, function ($a, $b) {
                return strcmp($a->getFullname(), $b->getFullname());
            });
        */


        // Impresssion des vides
        for ($i = 0; $i <= $this->NB_BADGES_VIDES; $i++) {
            array_push($datas, new Model_People("", "", "participant", "", ""));
        }
    
        return $datas;

    }

    private function getImageFromUrl($logoUrl, $societe)
    {

        $hash = md5($societe);
        $directory = "/tmp";
        $file = $directory . '/' . $hash;
        $files = glob($file . '.');
        if (count($files) == 0) {

            if ($this->isValidImageUrl($logoUrl)) {

                $imgSrc = file_get_contents($logoUrl);
                if ($imgSrc) {
                    file_put_contents($file, $imgSrc);
                    $file_info = new finfo(FILEINFO_MIME_TYPE);
                    $mime_type = $file_info->buffer(file_get_contents($file));
                    // http://php.net/manual/fr/function.image-type-to-mime-type.php

                    switch ($mime_type) {
                        case image_type_to_mime_type(IMAGETYPE_GIF):
                            $extension = "gif";
                            break;
                        case image_type_to_mime_type(IMAGETYPE_JPEG):
                            $extension = "jpg";
                            break;
                        case image_type_to_mime_type(IMAGETYPE_PNG):
                            $extension = "png";
                            break;
                        default:
                            Model_Log::log("Unknown Mime Type :: " . $mime_type . ' (setting no logo) :: ' . $logoUrl);
                            //$extension = "bmp";
                            return '';

                    }
                    if (copy($file, $file . "." . $extension)) {
                        unlink($file);
                        return $file . "." . $extension;
                    }
                } else {
                    Model_Log::log("Unable to Download :: " . $societe . ' :: ' . $logoUrl);
                    return '';
                }

            }
        } else {
            return $files[0];
        }
    }


    function isValidImageUrl($url){
        if (preg_match('/^(http|https)/', $url)){
            return true;
        } else {
            echo $url." NOT A VALID IMAGE URL \n";
            return false;
        }
    }


}
