<?php
include APPLICATION_PATH . '/../vendor/autoload.php';
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

/**
 * Created by IntelliJ IDEA.
 * User: remi
 * Date: 05/10/2014
 * Time: 18:49
 */
class Model_Sheet
{

    private $scope = array(
        'https://www.googleapis.com/auth/drive',
        'https://spreadsheets.google.com/feeds'
    );

    private $client = null;

    const WORKBOOK_TITLE = 'Inscrits7';
    const SHEET_NAME = 'Liste inscrits';
    const GOOGLE_CLIENTID = '191025209153-bs79o95lcn4ouferarmc4qjat9ikhauh.apps.googleusercontent.com';
    const GOOGLE_ACCOUNT_EMAIL = '191025209153-bs79o95lcn4ouferarmc4qjat9ikhauh@developer.gserviceaccount.com';
    const GOOGLE_PRIVATEKEY = '/datas/2a74cf461485d11e69b4ab03e032f018a6834ef4-privatekey.p12';

    public function __construct()
    {
        $client = new Google_Client();
        // Get your credentials from the  Google Developper console
        $client->setClientId(self::GOOGLE_CLIENTID);
        $credentials = new Google_Auth_AssertionCredentials(
            self::GOOGLE_ACCOUNT_EMAIL,
            $this->scope,
            file_get_contents(APPLICATION_PATH . self::GOOGLE_PRIVATEKEY));
        $client->setAssertionCredentials($credentials);
        $client->setCache(new Google_Cache_Null($client));

        if ($client->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($credentials);
        }

        $obj_token = json_decode($client->getAccessToken());
        $accessToken = $obj_token->access_token;


        $serviceRequest = new DefaultServiceRequest($accessToken);
        ServiceRequestFactory::setInstance($serviceRequest);
    }

    public function getDatas()
    {
        $spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
        $spreadsheetFeed = $spreadsheetService->getSpreadsheets();

        $spreadsheet = $spreadsheetFeed->getByTitle(self::WORKBOOK_TITLE);
        $worksheetFeed = $spreadsheet->getWorksheets();
        $worksheet = $worksheetFeed->getByTitle(self::SHEET_NAME);
        $listFeed = $worksheet->getListFeed();

        $datas = array();
        foreach ($listFeed->getEntries() as $entry) {
            $values = $entry->getValues();
            /*
            For Debug Prupose, uncomment to see the key names
            $keys = array_keys($values);
            $keys = array_map('utf8_decode', $keys);
            echo join(' -- ', $keys).'<br />';
            die();
            */
            if (trim($values['typebadge']) != '' && !in_array($values['nom'], array(1, 2, 3, 4, 5, 6, 7, 8))) {
                // Remove this line if you want to print all sheets
                if ($values['aimprimerlorsduprochainbatch'] == 1) {

                    array_push($datas, new Model_People($values['nom'], $values['prénom'], $values['typebadge']));
                    /*
                    This is for updating the spreadsheet
                    $values['badgeimprimé'] == 1;
                    $values['badgeàréimprimer'] == 0;
                    $entry->update($values);
                    */
                }
            }

        }




        /*
         * Sort data by name,
         * Commented, because it's easier to find charly ...
        usort($datas, function ($a, $b) {
            return strcmp($a->getFullname(), $b->getFullname());
        });
        */



        // Impresssion des vides
        for($i = 0; $i<=30; $i++){
            array_push($datas, new Model_People("", "", "participant"));
        }

        return $datas;

    }


}