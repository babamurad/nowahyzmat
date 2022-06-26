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
class Db {

    public static function getConnection() {

        $paramPath = ROOT . '/config/db_params.php';
        $params = include($paramPath);

        $db = new PDO("mysql:host={$params['host']};dbname={$params['dbname']};charset=utf8", $params['user'], $params['password']);

        return $db;
    }

}
