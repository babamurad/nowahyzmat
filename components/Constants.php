<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 *
 * @author AGA
 */
require_once(ROOT . '/models/Esasy.php');

class Constants
{

    public function __construct()
    {
        $configs = Esasy::getConfigs();

        ini_set('display_errors', intval($configs['с_display_errors']));
        error_reporting(E_ALL);
        define('FRONT_ITEMS_PER_PAGE', intval($configs['c_front_items_per_page']));
        define('ITEMS_PER_PAGE', intval($configs['c_items_per_page']));
        define('IMAGE_QUALITY', intval($configs['c_image_quality']));
        define('IMAGE_SIZE', intval($configs['c_image_size']));
        define('SHOW_DOROJKA', intval($configs['c_show_dorojka']));
        define('SHOW_BLOG', intval($configs['c_show_blog']));
        //define('SHOW_PARTNER', intval($configs['c_show_partner']));
        define('SHOW_REVIEW', intval($configs['c_show_review']));
        define('SHOW_ENGLISH', intval($configs['c_english_lang']));
        define('SHOW_TURKISH', intval($configs['c_turkish_lang']));
       // define('SHOW_CONTACT', intval($configs['с_contact']));
       // define('CURRENCY', $configs['с_currency']);
       // define("FETCHED_DATE_FORMAT", "d-m-Y");
    
        return true;
    }

    

}
