<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of news
 *
 * @author AGA
 */
class Esasy {

    public static function getConfigs() {
        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $result = $db->query('SELECT * FROM tb_configs WHERE id=1 LIMIT 1');

            //$result -> setFetchMode(PDO::FETCH_NUM); 
            //$result -> setFetchMode(PDO::FETCH_ASSOC); 

            $configs = $result->fetch(PDO::FETCH_ASSOC);

            return $configs;
        } catch (Exception $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }
    
    /**
     * Returns an array of harytlar all items offsetted by pages
     */
    public static function getDataListOfTable($table, $pag_page) {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pag_page = intval($pag_page);

        if ($table == 'tb_dorojka')
            $itemsPerPage = ITEMS_PER_PAGE * 10;
        else
            $itemsPerPage = ITEMS_PER_PAGE;

        $offset = ($pag_page - 1) * $itemsPerPage;

        try {
            $result = $db->prepare("SELECT * FROM " . $table . " ORDER BY id DESC LIMIT " . $itemsPerPage . " OFFSET " . $offset);
            $result->execute();
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $dataList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $dataList[$i] = $row;
                    // if($table=='tb_dorojka') {
                    //     $dataList[$i]['date'] = date('d-m-Y', strtotime($dataList[$i]['date']));
                    //     $dataList[$i]['date'] = date('d-m-Y', strtotime($dataList[$i]['until']));
                    // }
                    $i++;
                }
            }
            return $dataList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }
    
     /**
     * Returns single data item specified by id from given table
     * param integer id, param sting table name
     */
    public static function getDataById($id, $table) {
        $id = intval($id);

        if ($id) {
            $dataItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM ' . $table . ' WHERE id=:id LIMIT 1');
                $result->execute(array(':id' => $id));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $dataItem = $result->fetch(PDO::FETCH_ASSOC);

                return $dataItem;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns an array of review items of given product_id
     */
    public static function getReviews($product_id) {
        $product_id = intval($product_id);

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            if ($product_id != 0) {
                $result = $db->query('SELECT * FROM tb_teswirler WHERE confirm = "1" AND product_id=' . $product_id . ' ORDER BY date ASC LIMIT 5');

                //$result->execute(array(':unum' => $num));
                //$result -> setFetchMode(PDO::FETCH_NUM);
                $result->setFetchMode(PDO::FETCH_ASSOC);

                $teswirList = array();

                if (!empty($result)) {
                    $i = 0;
                    while ($row = $result->fetch()) {
                        $teswirList[$i] = $row;
                        $teswirList[$i]['date'] = date('d-m-Y', strtotime($teswirList[$i]['date']));
                        $i++;
                    }
                }

                return $teswirList;
            }
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    public static function regReview($product_id, $name, $email, $review_text) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $stmt = $db->prepare("INSERT INTO tb_teswirler(product_id,name,email,review)
                    VALUES(:uproduct_id,:uname,:uemail,:ureview_text)");
            $stmt->bindparam(":uproduct_id", $product_id, PDO::PARAM_INT);
            $stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":uemail", $email, PDO::PARAM_STR);
            $stmt->bindparam(":ureview_text", $review_text, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

     /**
     * Returns all items from given table 
     */
    public static function getImagesList($is_id) {
        $is_id = intval($is_id);
        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $result = $db->prepare("SELECT * FROM tb_dop_images WHERE link_id=". $is_id);
            $result->execute();
            
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $dataList = array();
            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $dataList[$i] = $row;
                    $i++;
                }
            }
            return $dataList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /*
     * Registrs new zapros  
     */

    public static function regRequest($request, $name, $email, $phone, $company, $mesage) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $stmt = $db->prepare("INSERT INTO tb_request(request,name,email,phone,company,mesage)
                    VALUES(:urequest,:uname,:uemail,:uphone,:ucompany,:umesage)");
            $stmt->bindparam(":urequest", $request, PDO::PARAM_INT);
            $stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":uemail", $email, PDO::PARAM_STR);
            $stmt->bindparam(":uphone", $phone, PDO::PARAM_STR);
            $stmt->bindparam(":ucompany", $company, PDO::PARAM_STR);
            $stmt->bindparam(":umesage", $mesage, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Returns an array of last 5 harytlar for dashboard
     */
    public static function getLast5HarytList($not_this_id) {

        $not_this_id = intval($not_this_id);

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $result = $db->prepare('SELECT * FROM tb_harytlar WHERE id!=:unot_this_id ORDER BY views DESC LIMIT 5');
            $result->bindparam(":unot_this_id", $not_this_id, PDO::PARAM_STR);
            $result->execute();


            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $totalharytlarList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $totalharytlarList[$i] = $row;
                    $totalharytlarList[$i]['date'] = date('d-m-Y', strtotime($totalharytlarList[$i]['date']));
                    $i++;
                }
            }
            return $totalharytlarList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns a number of count of harytlar ( cat = 0 - all items, else items of given catID 
     */
    public static function countHarytlar($cat = 0) {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $cat = intval($cat);

        try {
            if ($cat == 0) {
                $result = $db->prepare('SELECT code,id FROM tb_harytlar WHERE confirm=:uconfirm ORDER BY date DESC');
                $result->execute(array(':uconfirm' => '1'));
            } else {
                $result = $db->prepare('SELECT * FROM tb_harytlar WHERE cat_id=:ucat AND confirm=:uconfirm ORDER BY date DESC');
                $result->execute(array(':ucat' => $cat, ':uconfirm' => '1'));
            }
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $totalHarytlarList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $totalHarytlarList[$i] = $row;
                    $i++;
                }
            }

            return count($totalHarytlarList);
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }
    
    /**
     * Returns an array of harytlar all items to give pagination total number of rows 
     */
    public static function getDataListOfText($bolum, $num = 0) {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $bolum = intval($bolum);
        $num = intval($num);
        try {
                if($num==0) {
                $result = $db->prepare('SELECT * FROM tb_textler WHERE type=:ubolum AND status=:uconfirm ORDER BY date DESC');                
                } else {
                    $result = $db->prepare('SELECT * FROM tb_textler WHERE type=:ubolum AND status=:uconfirm ORDER BY date DESC LIMIT ' . $num);                
                }
                $result->execute(array(':ubolum' => $bolum, ':uconfirm' => '1'));

            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $dataList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $dataList[$i] = $row;
                    $i++;
                }
            }
            return $dataList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }


    /**
     * Returns an array of harytlar all items to give pagination total number of rows 
     */
    public static function getTotalHarytlarList($bolum, $cat = 0) {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $cat = intval($cat);
        $bolum = intval($bolum);
        try {
            if (($bolum == 0) && ($cat == 0)) {
                $result = $db->prepare('SELECT code,id FROM tb_harytlar WHERE confirm=1 ORDER BY date DESC');
                $result->execute();
            } elseif ($cat == 0) {
                $result = $db->prepare('SELECT * FROM tb_harytlar WHERE bolum=:ubolum AND confirm=:uconfirm ORDER BY date DESC');
                $result->execute(array(':ubolum' => $bolum, ':uconfirm' => '1'));
            } else {
                $result = $db->prepare('SELECT * FROM tb_harytlar WHERE cat_id=:ucat AND confirm=:uconfirm ORDER BY date DESC');
                $result->execute(array(':ucat' => $cat, ':uconfirm' => '1'));
            }

            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $totalHarytlarList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $totalHarytlarList[$i] = $row;
                    $i++;
                }
            }
            return $totalHarytlarList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /*
     * Updates tb_harytlar - views by ID, and if ok returns true
     */

    public static function reViews($id) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {
            $stmt = $db->prepare("UPDATE tb_harytlar SET views=views+1 WHERE id=:uid");
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Returns an array of harytlar ofsetted by FRONT_ITEMS_PER_PAGE
     */
    public static function getFrontHarytList($cat = 0, $pag_page) {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $cat = intval($cat);

        $pag_page = intval($pag_page);

        $offset = ($pag_page - 1) * FRONT_ITEMS_PER_PAGE;

        try {
            if ($cat == 0) {
                $result = $db->prepare('SELECT * FROM tb_harytlar ORDER BY date DESC LIMIT ' . $offset . ', ' . FRONT_ITEMS_PER_PAGE);
                $result->execute();
            } else {
                $result = $db->prepare('SELECT * FROM tb_harytlar WHERE cat_id=:ucat ORDER BY date DESC LIMIT ' . $offset . ', ' . FRONT_ITEMS_PER_PAGE);
                $result->execute(array(':ucat' => $cat));
            }

            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $harytlarList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $harytlarList[$i] = $row;
                    $i++;
                }
            }
            return $harytlarList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of harytlar ofsetted by FRONT_ITEMS_PER_PAGE
     */
    public static function getHarytlarList($cat = 0, $pag_page) {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $cat = intval($cat);

        $pag_page = intval($pag_page);

        $offset = ($pag_page - 1) * ITEMS_PER_PAGE;

        try {
            if ($cat == 0) {
                $result = $db->prepare('SELECT * FROM tb_harytlar ORDER BY date DESC LIMIT ' . $offset . ', ' . ITEMS_PER_PAGE);
                $result->execute();
            } else {
                $result = $db->prepare('SELECT * FROM tb_harytlar WHERE cat_id=:ucat ORDER BY date DESC LIMIT ' . $offset . ', ' . ITEMS_PER_PAGE);
                $result->execute(array(':ucat' => $cat));
            }

            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $harytlarList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $harytlarList[$i] = $row;
                    $i++;
                }
            }
            return $harytlarList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

/**
     * Returns Info About Company
     * param integer id
     */
    public static function getCompany($id) {
        $id = intval($id);

        if ($id) {
            $textItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM tb_company WHERE id=:id LIMIT 1');
                $result->execute(array(':id' => $id));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $textItem = $result->fetch(PDO::FETCH_ASSOC);

                return $textItem;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns single text item with specified id
     * param integer id
     */
    public static function getTextById($id) {
        $id = intval($id);

        if ($id) {
            $textItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM tb_textler WHERE id=:id LIMIT 1');
                $result->execute(array(':id' => $id));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $textItem = $result->fetch(PDO::FETCH_ASSOC);

                return $textItem;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns single haryt item with specified code
     * param str code
     */
    public static function getHarytByURL($code) {

        if ($code) {
            $harytItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM tb_harytlar WHERE seolink=:code LIMIT 1');
                $result->execute(array(':code' => $code));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $harytItem = $result->fetch(PDO::FETCH_ASSOC);

                return $harytItem;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns single haryt item with specified code
     * param str code
     */
    public static function getHarytByCODE($code) {

        if ($code) {
            $harytItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM tb_harytlar WHERE code=:code LIMIT 1');
                $result->execute(array(':code' => $code));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $harytItem = $result->fetch(PDO::FETCH_ASSOC);

                return $harytItem;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns single haryt item with specified code
     * param str code
     */
    public static function getHarytByID($code) {

        if ($code) {
            $harytItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM tb_harytlar WHERE id=:code LIMIT 1');
                $result->execute(array(':code' => $code));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $harytItem = $result->fetch(PDO::FETCH_ASSOC);

                return $harytItem;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns single text item with specified name
     * param varchar tname
     */
    public static function getTextByName($tname) {
        if ($tname) {
            $textItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM tb_textler WHERE name=:tname LIMIT 1');
                $result->execute(array(':tname' => $tname));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $textItem = $result->fetch(PDO::FETCH_ASSOC);

                return $textItem;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }
    
    /**
     * Returns all items from given table 
     */
    public static function getDataListAll($table) {
        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $result = $db->query('SELECT * FROM ' . $table);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $dataList = array();
            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $dataList[$i] = $row;
                    $i++;
                }
            }
            return $dataList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of isler items
     */
    public static function getDataList($table, $num = 8) {
        $num = intval($num);

        if ($num) {
            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $result = $db->query('SELECT * FROM ' . $table . ' WHERE status=1 ORDER BY date DESC LIMIT ' . $num);
                //$result->execute(array(':unum' => $num));
                //$result -> setFetchMode(PDO::FETCH_NUM);
                $result->setFetchMode(PDO::FETCH_ASSOC);

                $dataList = array();

                if (!empty($result)) {
                    $i = 0;
                    while ($row = $result->fetch()) {
                        $dataList[$i] = $row;
                        $i++;
                    }
                }
                return $dataList;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns an array of isler items
     */
    public static function getIslerList($num = 8) {
        $num = intval($num);

        if ($num) {
            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $result = $db->query('SELECT * FROM tb_isler WHERE confirm=1 ORDER BY date DESC LIMIT ' . $num);
                //$result->execute(array(':unum' => $num));
                //$result -> setFetchMode(PDO::FETCH_NUM);
                $result->setFetchMode(PDO::FETCH_ASSOC);

                $islerList = array();

                if (!empty($result)) {
                    $i = 0;
                    while ($row = $result->fetch()) {
                        $islerList[$i] = $row;
                        $i++;
                    }
                }
                return $islerList;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns single cat_id of given cat name
     * returning param integer id
     */
    public static function getCatID($cat_url) {


        if ($cat_url) {
            $catID = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT cat_id FROM tb_cats WHERE url=:url LIMIT 1');
                $result->execute(array(':url' => $cat_url));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $catID = $result->fetch(PDO::FETCH_ASSOC);

                return $catID['cat_id'];
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }

    /**
     * Returns single cat item of given cat id
     * returning param array
     */
    public static function getCatByID($cat) {


        if ($cat) {
            $catByID = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM tb_cats WHERE id=:cat LIMIT 1');
                $result->execute(array(':cat' => $cat));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $catByID = $result->fetch(PDO::FETCH_ASSOC);

                return $catByID;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
    }
    
    /**
     * Returns given N number of category items as array
     */
    public static function getLimitedCatList($num = 0) {
        $num = intval($num);

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            if ($num>0) {
            $result = $db->prepare('SELECT * FROM tb_cats WHERE sub_cat=0 ORDER BY sort_order DESC LIMIT ' . $num);
            
            } else { $result = $db->prepare('SELECT * FROM tb_cats WHERE sub_cat=0 ORDER BY sort_order DESC'); }
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->execute();
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $catList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $catList[$i] = $row;
                    $i++;
                }
            }
            return $catList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of category item of bolum department
     */
    public static function getCatList($cat = 0) {
        $cat = intval($cat);

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $result = $db->prepare('SELECT * FROM tb_cats WHERE sub_cat=:ucat ORDER BY sort_order ASC');
            $result->execute(array(':ucat' => $cat));
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $catList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $catList[$i] = $row;
                    $i++;
                }
            }
            return $catList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /*
     * Updates tb_harytlar - views by ID, and if ok returns true
     */

    public static function updateReview($id) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {
            $stmt = $db->prepare("UPDATE tb_harytlar SET views=views+1 WHERE id=:uid");
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}
