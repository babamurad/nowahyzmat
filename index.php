<?php

/* Author Aziz Aaganiyazov from DogryDesign
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Front Controler



//session_start();
session_start();
//2.System files connection
define('ROOT', dirname(__FILE__));
define('HOST', gethostname());
require_once(ROOT.'/components/Router.php');
require_once(ROOT.'/components/Functions.php');
require_once(ROOT.'/components/Pagination.php');

//3.DataBase connection
require_once(ROOT.'/components/Db.php');
require_once(ROOT.'/components/Constants.php');
// 1.General Options
$configs = new Constants();

//4.Call Router

$router = new Router();

$router -> run();