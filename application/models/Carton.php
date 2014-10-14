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
    const FONT_SIZE = 20;
    const LINE_SPACING = 10;
    const ROTATE_DEG = -37;
    const TEXT_COLOR = "#4a4a4b";
    const PAGE_SIZE_A6 = "298:420:";
    const PAGE_SIZE_A4 = '595:842:';
    const FONT_FACE = '/fonts/Dosis-Bold.ttf';


    private $font;

    private $directory;


    public function __construct($param1, $param2 = null, $param3 = null){
        $this->directory = APPLICATION_PATH . '/datas';

        parent::__construct($param1, $param2 = null, $param3 = null);

        $this->font = Zend_Pdf_Font::fontWithPath($this->directory . '' . self::FONT_FACE . '');
        $this->color = new Zend_Pdf_Color_Html(self::TEXT_COLOR);
    }



    public function drawCard($prenom, $nom, $image)
    {

        if( is_file($image)){
            $imageDeFond = Zend_Pdf_Image::imageWithPath($image);

            $this->drawImage($imageDeFond, 0, 0, $this->getWidth(), $this->getHeight());

            $this->rotate(self::NAME_X, self::NAME_Y, deg2rad(self::ROTATE_DEG));
            $this->setFont($this->font, self::FONT_SIZE);
            $this->setFillColor($this->color)->drawText($prenom, self::NAME_X, self::NAME_Y + (self::FONT_SIZE + self::LINE_SPACING));
            if( strlen($nom) >15 ){
                $this->setFont($this->font, self::FONT_SIZE - 3);
            }

            $this->setFillColor($this->color)->drawText($nom, self::NAME_X, self::NAME_Y);

        }

    }

} 