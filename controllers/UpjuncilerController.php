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

class UpjuncilerController {

    public function actionIndex($dil = 'tm') {

        $dil = Functions::clearStr($dil);
        require_once(ROOT . '/template/lang/' . $dil . '.php');

        $shortAbout = Esasy::getTextById(1);
        
        $textAdres = Esasy::getTextByName('cont-adres');
        
        $mailTel = Esasy::getTextByName('mail-tel');
        
        $textUpjunciler = Esasy::getTextByName('t-upjunciler');

        $page = 'upjunciler';
        $title = ${$page};

        require_once(ROOT . '/template/default/suppliers.tpl.php');

        return true;
    }

}
