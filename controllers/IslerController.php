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

class IslerController {

    public function actionIndex($dil = 'tm') {

        $dil = Functions::clearStr($dil);
        include ROOT . '/template/lang/' . $dil . '.php';

       
        
        //session_destroy();        
        
        $shortAbout = Esasy::getTextById(5);
        $metall = Esasy::getTextById(6);
       // $post1Text = Esasy::getTextById(3);
      //  $adres = Esasy::getTextById(4);
       // $slogan = Esasy::getTextById(5);
      //  $telefon = Esasy::getTextById(6);
      //  $time = Esasy::getTextById(8);
      //  $email = Esasy::getTextById(10);
        
        $companyData = Esasy::getCompany(1);        
        $sliderList = Esasy::getDataList('tb_slider');

        $newsList = Esasy::getDataList('tb_news', 4);  
        $isler = Esasy::getDataListOfText(3, 0);
        
        
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
       
        $page = 'isler';
        $title = ${$page};
        require_once(ROOT . '/template/nowah/isler.tpl.php');
//factory
//default
        return true;
    }

}
