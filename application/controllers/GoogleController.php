<?php

/**
 * Created by IntelliJ IDEA.
 * User: remigoyard
 * Date: 05/10/2015
 * Time: 09:07
 */

define('CLIENT_SECRET_PATH_G',APPLICATION_PATH . '/credentials/client_secret.json');

class GoogleController extends Zend_Controller_Action
{

    const REDIRECT_URL = 'http://localhost:81/google/callback';
    const APPLICATION_NAME = 'PDF Tour de cou';
    const CREDENTIALS_PATH = '/tmp/.credentials/drive-php-quickstart.json';
    const CLIENT_SECRET_PATH = CLIENT_SECRET_PATH_G;

    private $file_url = "https://www.googleapis.com/drive/v2/files/1xqaQ1Iszdm7THtu94SNlz_9OXloUr7n1Bif5yk4Vk6s?export=gid=1938063635&format=csv";

    private $fileId = "1xqaQ1Iszdm7THtu94SNlz_9OXloUr7n1Bif5yk4Vk6s";


    public function indexAction()
    {
        $client = $this->getClient();


        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
            $drive_service = new Google_Service_Drive($client);
            $file = $drive_service->files->get($this->fileId);

            /*$request = new Google_Http_Request($this->file_url);
            $http    = $client->getAuth()->authenticatedRequest($request);

            if ($http->getResponseHttpCode() !== 200) {
                echo "Failed downloading file content.\n";
                exit -1;
            }

// Process file content into an array of arrays, one for each row
            $rows    = array_map('str_getcsv', explode("\n", $http->getResponseBody()));
            var_dump($rows);die();
            */
            $url = $file->getExportLinks();
            echo "<br />";
            var_dump($file);die();
            var_dump($url);

            /*$files_list = $drive_service->files->listFiles(array())->getItems();
            foreach($files_list as $file){
                // @var Google_Service_Drive_DriveFile $file
                echo $file->getTitle();
                echo '<br />';
            }*/
            //echo json_encode($files_list);
        } else {
            $this->redirect($this->_helper->url('callback', 'google'));
            header('Location: ' . filter_var($client->createAuthUrl(), FILTER_SANITIZE_URL));
        }


    }

    public function callbackAction()
    {
        $client = $this->getClient();

        if (! $this->getRequest()->getParam('code', false)) {
            $auth_url = $client->createAuthUrl();
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        } else {
            $client->authenticate($this->getRequest()->getParam('code'));
            $_SESSION['access_token'] = $client->getAccessToken();

            // TODO Get the File Inscrit



            $redirect_uri = $this->_helper->url('index', 'index');
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }

    }

    public function revokeAction(){
        $client = $this->getClient();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            echo "REVOKING ....";
            $client->revokeToken();

            unset($_SESSION['access_token']);
            $this->redirect($this->_helper->url('index', 'index'));

        } else {
            $this->redirect($this->_helper->url('index', 'google'));
        }
    }



    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName(self::APPLICATION_NAME);

        $client->addScope(array(Google_Service_Drive::DRIVE, Google_Service_Drive::DRIVE_FILE, Google_Service_Drive::DRIVE_METADATA_READONLY ));
        $client->setAuthConfigFile(self::CLIENT_SECRET_PATH);
        $client->setRedirectUri(self::REDIRECT_URL);
        $client->setClassConfig('Google_Http_Request', 'disable_gzip', true);

        return $client;

    }

}
