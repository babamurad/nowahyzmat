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

class TazeliklerController {

    public function actionIndex($dil = 'tm', $pagin) {

        $dil = Functions::clearStr($dil);
        include ROOT . '/template/lang/' . $dil . '.php';

       $pagin = intval($pagin);
        
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

        $totalRecords = Esasy::getDataListAll('tb_news');

        if (count($totalRecords) % (FRONT_ITEMS_PER_PAGE) > 0)
            $totalPagPages = floor((count($totalRecords) / FRONT_ITEMS_PER_PAGE) + 1);
        else
            $totalPagPages = floor((count($totalRecords) / ITEMS_PER_PAGE));

        $newsList = Esasy::getDataListOfTable('tb_news', $pagin);      
       
        
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
       
        $page = 'tazelikler';
        $title = ${$page};
        require_once(ROOT . '/template/nowah/news.tpl.php');
//factory
//default
        return true;
    }

}
