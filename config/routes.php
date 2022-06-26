<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'admin/settings' => 'admin/settings',  // actionRegistration in AdminController
    'admin/registr' => 'admin/registr',  // actionRegistration in AdminController
    'admin/profile' => 'admin/profile',  // actionPrifile in AdminController
    'admin/login' => 'admin/login',  // actionLogin in AdminController
    'admin/logout' => 'admin/logout',  // actionLogout in AdminController
    'admin/request/([0-9]+)' => 'admin/request/$1',  // actionRequest in AdminController
    'admin/review/([0-9]+)' => 'admin/review/$1',  // actionReview in AdminController
    'admin/harytlar/([0-9]+)/([0-9]+)/([0-9]+)' => 'admin/harytlar/$1/$2/$3',  // actionHarytlar in AdminController
    
    'admin/esasy' => 'admin/esasy',  // actionEsasy in AdminController
    'admin/bizbarada' => 'admin/bizbarada',  // actionIsler in AdminController
    'admin/ulanyjy/([0-9]+)/([0-9]+)' => 'admin/ulanyjy/$1/$2',  // actionIsler in AdminController
    'admin/company' => 'admin/company',  // actionIsler in AdminController
    'admin/aragatnasyk' => 'admin/aragatnasyk',  // actionAragatnasyk in AdminController
    'admin/slider/([0-9]+)/([0-9]+)' => 'admin/slider/$1/$2',  // actionSlider in AdminController
    'admin/tazelik/([0-9]+)/([0-9]+)' => 'admin/tazelik/$1/$2',  // actionSlider in AdminController
    'admin/surat/([0-9]+)/([0-9]+)' => 'admin/surat/$1/$2',  // actionSurat in AdminController
    'admin/text/([0-9]+)' => 'admin/text/$1',  // actionSlider in AdminController
    'admin' => 'admin/company',  // actionIndex in AdminController 
    
    'upjunciler/([a-z][a-z])' => 'upjunciler/index/$1',  // actionIndex in UpjuncilerController 
    'aragatnasyk/([a-z][a-z])' => 'aragatnasyk/index/$1',  // actionIndex in AragatnasykController 
    'bizbarada/([a-z][a-z])' => 'bizbarada/index/$1',  // actionIndex in BizbaradaController
    'isler/([a-z][a-z])' => 'isler/index/$1',  // actionIndex in IslerController    
    'onumler/([a-z][a-z])' => 'onumler/index/$1', // actionIndex in OnumlerController
    'hyzmatdaslar/([a-z][a-z])' => 'hyzmatdaslar/index/$1',  // actionIndex in HyzmatdaslarController
    'zapros/([a-z][a-z])' => 'zapros/index/$1',  // actionIndex in HakymyzdaController    
    'tazelik/([a-z][a-z])/([a-z0-9]+)' => 'tazelik/index/$1/$2', // actionIndex in TazelikController
    'tazelikler/([a-z][a-z])/([0-9]+)' => 'tazelikler/index/$1/$2',  // actionIndex in MarketController
    'esasy/([a-z][a-z])' => 'esasy/index/$1',      // actionIndex in EsasyController
    '' => 'esasy/index'      // actionIndex in EsasyController
    
);

