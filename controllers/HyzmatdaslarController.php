<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newsController
 *
 * @author AGA
 */
include_once ROOT . '/models/Esasy.php';

class HyzmatdaslarController {

    public function actionIndex($dil = 'tm') {
        
        $dil = Functions::clearStr($dil);
        if (file_exists(ROOT . '/template/lang/' . $dil . '.php')) require_once(ROOT . '/template/lang/' . $dil . '.php');

        $catList = Esasy::getCatList();
        
        $shortAbout = Esasy::getTextById(1);
        $longAbout = Esasy::getTextById(2);
        
        $textAdres = Esasy::getTextByName('cont-adres');
        
        $mailTel = Esasy::getTextByName('mail-tel');

        $shortAbout = Esasy::getTextById(1);
        $adres = Esasy::getTextById(4);
        $telefon = Esasy::getTextById(6);
        $time = Esasy::getTextById(8);
        $email = Esasy::getTextById(10);
        $email2 = Esasy::getTextById(12);
        $partnersList = Esasy::getDataListOfText(4);
        $slogan = Esasy::getTextById(5);

        //$catList = Esasy::getCatList();
        
        $catsListMenu = Esasy::getLimitedCatList();

        switch ($dil) {
            case "tm":
            $dil_full="Türkmençe";
                break;
            case "ru":
            $dil_full="Русский";
                break;
            case "en":
                $dil_full="English";
                break;
            case "tr":
                $dil_full="Türkçe";
                break;
        } 
        
         $page = 'hyzmatdaslar';
        $title = ${$page};

        require_once(ROOT . '/template/pasylson/hyzmatdaslar.tpl.php');

        return true;
    }

}
