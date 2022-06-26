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


class AragatnasykController {

    public function actionIndex($dil = 'tm') {
        
        $dil = Functions::clearStr($dil);
        include ROOT . '/template/lang/' . $dil . '.php';
  
        $companyData = Esasy::getCompany(1);
        
        $sliderList = Esasy::getDataList('tb_slider');   
        
       
      

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
        $page='aragatnasyk';
        $title = ${$page};    
        
        require_once(ROOT . '/template/nowah/contact.tpl.php');

        return true;
    }

}
