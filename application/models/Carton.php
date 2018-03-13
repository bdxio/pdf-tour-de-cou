<?php

/**
 * Created by IntelliJ IDEA.
 * User: remi
 * Date: 05/10/2014
 * Time: 17:18
 */
class Model_Carton extends Zend_Pdf_Page
{

    const NAME_X = 75.13;
    const NAME_Y = 299.30;
    //const NAME_Y = 330;
    const FONT_SIZE = 20;
    const LINE_SPACING = 10;
    const ROTATE_DEG = -37;
    const TEXT_COLOR = "#4a4a4b";
    const PAGE_SIZE_A6 = "298:420:";
    const PAGE_SIZE_A4 = '595:842:';
    const FONT_FACE = '/fonts/Dosis-Bold.ttf';
    const IMG_WIDTH_LOGO_MAX = 100;
    const IMG_HEIGHT_LOGO_MAX = 50;


    private $font;

    private $directory;


    public function __construct($param1, $param2 = null, $param3 = null)
    {
        $this->directory = APPLICATION_PATH . '/datas';

        parent::__construct($param1, $param2 = null, $param3 = null);

        $this->font = Zend_Pdf_Font::fontWithPath($this->directory . '' . self::FONT_FACE . '');
        $this->color = new Zend_Pdf_Color_Html(self::TEXT_COLOR);
    }


    public function drawCard(Model_People $participant, $image)
    {

        if (is_file($image)) {
            $imageDeFond = Zend_Pdf_Image::imageWithPath($image);

            $this->drawImage($imageDeFond, 0, 0, $this->getWidth(), $this->getHeight());

            $this->rotate(self::NAME_X, self::NAME_Y, deg2rad(self::ROTATE_DEG));
            $this->setFont($this->font, self::FONT_SIZE);
            $this->setFillColor($this->color)->drawText($participant->formatPrenom(), self::NAME_X, self::NAME_Y + (self::FONT_SIZE + self::LINE_SPACING), 'UTF-8');
            if (strlen($participant->formatNom()) > 15) {
                $this->setFont($this->font, self::FONT_SIZE - 3);
            }

            $this->setFillColor($this->color)->drawText($participant->formatNom(), self::NAME_X, self::NAME_Y, 'UTF-8');


            $this->drawNomSociete($participant);


            if ($participant->hasLogo()) {
                try {
                    $this->drawLogoSociete($participant);
                } catch (Zend_Pdf_Exception $zpe) {
                    Model_Log::log($zpe->getMessage(), Zend_Log::ERR);
                }

            }

            $this->drawWifi($participant);
        }

    }

    private function getImageWidthHeight($logo)
    {

        $size = getimagesize($logo);
        $ratio = $size[0] / $size[1]; // width/height
        if ($ratio > 1) {
            $width = self::IMG_WIDTH_LOGO_MAX;
            $height = self::IMG_WIDTH_LOGO_MAX / $ratio;
        } else {
            $width = self::IMG_HEIGHT_LOGO_MAX * $ratio;
            $height = self::IMG_HEIGHT_LOGO_MAX;
        }
        return [$width, $height];
    }

    /**
     * @param Model_People $participant
     */
    public function drawNomSociete(Model_People $participant)
    {
        $this->setFont($this->font, self::FONT_SIZE - 5);
        $decallagesociete = (self::FONT_SIZE + ((1 / 2) * self::LINE_SPACING));
        $company = $participant->formatCompany();

        $this->setFillColor($this->color)->drawText($company[0], self::NAME_X, self::NAME_Y - $decallagesociete, 'UTF-8');
        if (count($company) > 1) {
            $this->setFillColor($this->color)->drawText($company[1], self::NAME_X, self::NAME_Y - (2 * $decallagesociete), 'UTF-8');
        }
    }

    /**
     * @param Model_People $participant
     * @throws Zend_Pdf_Exception
     */
    public function drawLogoSociete(Model_People $participant)
    {
        // echo $participant->formatNom();
        $imageSociete = Zend_Pdf_Image::imageWithPath($participant->getLogo());
        $decallageImage = (3 * self::LINE_SPACING) + 2 * self::FONT_SIZE;

        $dimensions = $this->getImageWidthHeight($participant->getLogo());

        $x1 = self::NAME_X;
        $y1 = self::NAME_Y - $decallageImage - $dimensions[1];
        $x2 = self::NAME_X + $dimensions[0];
        $y2 = self::NAME_Y - $decallageImage;

        $this->drawImage($imageSociete, $x1, $y1, $x2, $y2);
    }


    public function drawWifi(Model_People $participant)
    {
        $this->setFont($this->font, self::FONT_SIZE - 13);

        $wifi1 = "Login : " . $participant->getLogin();
        $wifi2 = "Password : " . $participant->getPassword();
        // on remet le texte droit
        $this->rotate(self::NAME_X, self::NAME_Y, deg2rad(-self::ROTATE_DEG));
        $this->rotate(0, 0, deg2rad(-128));
        $this->setFillColor($this->color)->drawText($wifi1, -320, 32, 'UTF-8');
        $this->setFillColor($this->color)->drawText($wifi2, -230, 32, 'UTF-8');
    }
}
