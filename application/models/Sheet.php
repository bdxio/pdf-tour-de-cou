<?php


/**
 * Created by IntelliJ IDEA.
 * User: remi
 * Date: 05/10/2014
 * Time: 18:49
 */
define('CLIENT_SECRET_PATH_G', APPLICATION_PATH . '/credentials/client_secret.json');

class Model_Sheet
{


    private $APPLICATION_NAME = 'PDF Tour de cou';
    private $CREDENTIALS_PATH = '/tmp/.credentials/drive-php-quickstart.json';
    private $CLIENT_SECRET_PATH = CLIENT_SECRET_PATH_G;
    private $SCOPES;
    private $API_KEY = "AIzaSyBrI7IORdOSlhwSN_1soQhShhYyobS90og";
    private $NB_BADGES_VIDES = 50;
    private $file_url = "https://www.googleapis.com/drive/v2/files/1xqaQ1Iszdm7THtu94SNlz_9OXloUr7n1Bif5yk4Vk6s?export=gid=1938063635&format=csv";

    private $fileId = "1xqaQ1Iszdm7THtu94SNlz_9OXloUr7n1Bif5yk4Vk6s";

    public function __construct()
    {

    }

    public function getDatas()
    {
        $debug = false;
        $datas = array();

        $file = APPLICATION_PATH . '/datas/Inscrits bdxio 2017 - Liste inscrits_F.csv';

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
        //var_dump($datas);die();
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

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName($this->APPLICATION_NAME);

        $client->setDeveloperKey($this->API_KEY);

        return $client;
    }


    /**
     * Download a file's content.
     *
     * @param Google_Servie_Drive $service Drive API service instance.
     * @param Google_Servie_Drive_DriveFile $file Drive File instance.
     * @return String The file's content if successful, null otherwise.
     */
    function downloadFile($service, $file)
    {
        $downloadUrl = $file->getDownloadUrl();
        if ($downloadUrl) {
            $request = new Google_Http_Request($downloadUrl, 'GET', null, null);
            $httpRequest = $service->getClient()->getAuth()->authenticatedRequest($request);
            if ($httpRequest->getResponseHttpCode() == 200) {
                return $httpRequest->getResponseBody();
            } else {
                // An error occurred.
                return null;
            }
        } else {
            // The file doesn't have any content stored on Drive.
            return null;
        }
    }

}
