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

class ToparController {

    public function actionIndex($dil = 'tm', $pagin = 1, $cat = 'all') {
        
        $pagin = intval($pagin);
        
        if ($cat == 'all') $cat_id = 0; else $cat_id = Functions::get_id_from_seo($cat); 

        $dil = Functions::clearStr($dil);
        require_once(ROOT . '/template/lang/' . $dil . '.php');

        $catList = Esasy::getCatList();
        
        $catsListMenu = Esasy::getLimitedCatList();
        
        $harytList = Esasy::getHarytlarList($cat_id,$pagin);
        
        $shortAbout = Esasy::getTextById(1);
        
        $textAdres = Esasy::getTextByName('cont-adres');
        
        $mailTel = Esasy::getTextByName('mail-tel');
        
        $totalPagPages = floor(Esasy::countHarytlar($cat_id) / ITEMS_PER_PAGE)+1;
        $prev = $pagin-1;
        $next = $pagin+1;
 
        //$harytlarList = Esasy::getHarytlarList('2','0','1');

        $page = 'topar';
        $title = ${$page};        

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
/*
        $pagination = new Pagination();

        $paginate = $pagination->calculate_pages(count($totalHarytlar), FRONT_ITEMS_PER_PAGE, '/market/tm/', $pagin); */

        require_once(ROOT . '/template/factory/products.tpl.php');
//factory
//default
        return true;
    }

}
