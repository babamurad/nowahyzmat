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

class YalykController {

    public function actionIndex($dil = 'tm', $item) {
        
        $harytItem = Esasy::getHarytByURL($item);
        
        $harytCAT = Esasy::getCatByID($harytItem['cat_id']);

        $dil = Functions::clearStr($dil);
        
        require_once(ROOT . '/template/lang/' . $dil . '.php');

        $catList = Esasy::getCatList();
        
        $catsListMenu = Esasy::getLimitedCatList();
        
        $shortAbout = Esasy::getTextById(1);
        
        $textAdres = Esasy::getTextByName('cont-adres');
        
        $mailTel = Esasy::getTextByName('mail-tel');

        //$harytlarList = Esasy::getHarytlarList('2','0','1');      
        
        $page = 'yalyk';
        $title = ${$page};
        
        $mostReviewed5 = Esasy::getLast5HarytList($harytItem['id']);
        
        if(isset($_POST['comSubmit'])) {
        //registr a comment
            $name = $_POST['comname'];
            $review = $_POST['commesage'];
            $email = $_POST['commail'];
            $product_id = $harytItem['id'];

            Esasy::regReview($product_id, $name, $email, $review);
        } else {
        //update review
        Esasy::updateReview($harytItem['id']);
        }
        $catList = Esasy::getCatList();

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
             
        
        $teswirlerList = Esasy::getReviews($harytItem['id']);

        require_once(ROOT . '/template/factory/detail.tpl.php');
//factory
//default
        return true;
    }
    

}
