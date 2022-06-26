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
class Admin {

    /**
     * Returns single text item with specified id
     * param integer id
     */
    public static function checkUserSESS($sess) {
        //$id = intval($id);

        if ($sess) {
            $userItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM tb_users WHERE sess=:sess LIMIT 1');
                $result->execute(array(':sess' => $sess));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $userItem = $result->fetch(PDO::FETCH_ASSOC);

                if (empty($userItem)) {

                    unset($_SESSION['user_id']);
                    unset($_SESSION['user_name']);
                    unset($_SESSION['user_sess']);
                    session_destroy();
                }

                return $userItem;
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
     * Returns all items from given table 
     */
    public static function getDopImages($id) {
        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $result = $db->query('SELECT * FROM tb_dop_images WHERE link_id=' . $id);
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
     * Returns all items from given table 
     */
    public static function getDataList($table) {
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

    /*
     * Gets all users allowed to change data from admin
     */

    public static function getUsers() {

        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare("SELECT * FROM tb_users");
            $stmt->execute();

            $usersList = array();

            if (!empty($stmt)) {
                $i = 0;
                while ($row = $stmt->fetch()) {
                    $usersList[$i] = $row;
                    $usersList[$i]['reg_date'] = date('d-m-Y', strtotime($usersList[$i]['reg_date']));
                    $i++;
                }
            }
            return $usersList;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Gets all users allowed to change data from admin
     */

    public static function getUserNames() {

        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare("SELECT user_id,name FROM tb_users");
            $stmt->execute();

            $usersList = array();

            if (!empty($stmt)) {
                $i = 0;
                while ($row = $stmt->fetch()) {
                    $usersList[$row['user_id']] = $row['name'];
                    $i++;
                }
            }
            return $usersList;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getConfigs() {
        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $result = $db->query('SELECT * FROM tb_configs WHERE id=1 LIMIT 1');

            //$result -> setFetchMode(PDO::FETCH_NUM); 
            //$result -> setFetchMode(PDO::FETCH_ASSOC); 

            $configs = $result->fetch(PDO::FETCH_ASSOC);

            return $configs;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    public static function checkUser($umail, $upass, $uname, $uphone, $updated_by, $error = '') {

        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare("SELECT name, email FROM tb_users WHERE email=:umail");
            $stmt->execute(array(':umail' => $umail));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['email'] == $umail) {
                $error .= "Bu email salgy ulgamda, başga saýlaň!";
                return $error;
            } else
                return self::register($umail, $upass, $uname, $uphone, $updated_by);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Registers new admin user
     */

    public static function register($mail, $pass, $name, $phone, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $new_password = password_hash($pass, PASSWORD_DEFAULT);
            $sess = md5(microtime());

            $stmt = $db->prepare("INSERT INTO tb_users(name,email,password,phone,sess,updated_by) 
                                                       VALUES(:uname, :umail, :upass, :uphone, :usess, :updated_by)");
            $stmt->bindparam(":uname", $name);
            $stmt->bindparam(":umail", $mail);
            $stmt->bindparam(":upass", $new_password);
            $stmt->bindparam(":uphone", $phone);
            $stmt->bindparam(":usess", $sess);
            $stmt->bindparam(":updated_by", $updated_by);
            $stmt->execute();

            /* $_SESSION['user_id'] = $userRow['user_id'];
              $_SESSION['user_name'] = $userRow['name'];
              $_SESSION['user_sess'] = $userRow['sess']; */

            return 'success';
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates existing settings in tb_congigs table
     */

    public static function updateSettings($front_items_per_page, $items_per_page, $image_size, $image_quality, $show_review, $show_dorojka, $display_errors, $show_english, $show_turkish, $show_blog) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare("UPDATE tb_configs SET c_front_items_per_page=:front_items_per_page, c_items_per_page=:items_per_page, c_image_size=:image_size, c_image_quality=:image_quality, c_show_review=:show_review, c_show_dorojka=:ushow_dorojka, с_display_errors=:display_errors, c_show_blog=:ushow_blog, c_english_lang=:english_lang, c_turkish_lang=:turkish_lang WHERE id=1");
            $stmt->bindparam(":front_items_per_page", $front_items_per_page);
            $stmt->bindparam(":items_per_page", $items_per_page);
            $stmt->bindparam(":image_size", $image_size);
            $stmt->bindparam(":image_quality", $image_quality);
            $stmt->bindparam(":show_review", $show_review);
            $stmt->bindparam(":ushow_dorojka", $show_dorojka);
            $stmt->bindparam(":display_errors", $display_errors);
            $stmt->bindparam(":ushow_blog", $show_blog);
            $stmt->bindparam(":english_lang", $show_english);
            $stmt->bindparam(":turkish_lang", $show_turkish);
            $stmt->execute();

            return 'success';
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    /*
     * Updates existing company data in tb_company table
     */

    public static function updateCompanyData($name, $full_name_tm, $full_name_ru, $full_name_en, $full_name_tr, $adres_tm, $adres_ru, $adres_en, $adres_tr, $telephone1, $telephone2, $email1, $email2) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare("UPDATE tb_company SET name=:name, full_name_tm=:full_name_tm, full_name_ru=:full_name_ru, full_name_en=:full_name_en, full_name_tr=:full_name_tr, adres_tm=:adres_tm, adres_ru=:adres_ru, adres_en=:adres_en, adres_tr=:adres_tr, telephone1=:telephone1, telephone2=:telephone2, email1=:email1, email2=:email2 WHERE id=1");
            $stmt->bindparam(":name", $name);
            $stmt->bindparam(":full_name_tm", $full_name_tm);
            $stmt->bindparam(":full_name_ru", $full_name_ru);
            $stmt->bindparam(":full_name_en", $full_name_en);
            $stmt->bindparam(":full_name_tr", $full_name_tr);
            $stmt->bindparam(":adres_tm", $adres_tm);
            $stmt->bindparam(":adres_ru", $adres_ru);
            $stmt->bindparam(":adres_en", $adres_en);
            $stmt->bindparam(":adres_tr", $adres_tr);
            $stmt->bindparam(":telephone1", $telephone1);
            $stmt->bindparam(":telephone2", $telephone2);
            $stmt->bindparam(":email1", $email1);
            $stmt->bindparam(":email2", $email2);
            $stmt->execute();

            return 'success';
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates existing admin user
     */

    public static function updateUser($id, $uname, $upass, $uphone, $usatatus, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            if (!empty($upass)) {
                $new_password = password_hash($upass, PASSWORD_DEFAULT);
                $sess = md5(microtime());

                $stmt = $db->prepare("UPDATE tb_users SET name=:uname,password=:upass,phone=:uphone,sess=:usess,status=:usatatus,updated_by=:updated_by WHERE user_id=:uid");
                $stmt->bindparam(":uname", $uname);
                $stmt->bindparam(":uid", $id);
                $stmt->bindparam(":upass", $new_password);
                $stmt->bindparam(":uphone", $uphone);
                $stmt->bindparam(":usatatus", $usatatus);
                $stmt->bindparam(":updated_by", $updated_by);
                $stmt->bindparam(":usess", $sess);
                $stmt->execute();

                $_SESSION['user_sess'] = $sess;
            } else {
                $stmt = $db->prepare("UPDATE tb_users SET name=:uname,phone=:uphone,status=:usatatus,updated_by=:updated_by WHERE user_id=:uid");
                $stmt->bindparam(":uname", $uname);
                $stmt->bindparam(":uid", $id);
                $stmt->bindparam(":uphone", $uphone);
                $stmt->bindparam(":usatatus", $usatatus);
                $stmt->bindparam(":updated_by", $updated_by);
                $stmt->execute();
            }

            return 'success';
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function login($umail, $upass) {
        $db = DB:: getConnection();
        try {
            $stmt = $db->prepare("SELECT * FROM tb_users WHERE email=:umail AND status='1' LIMIT 1");
            $stmt->bindparam(":umail", $umail);
            $stmt->execute();
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {
                if (password_verify($upass, $userRow['password'])) {
                    $_SESSION['user_id'] = $userRow['user_id'];
                    $_SESSION['user_name'] = $userRow['name'];
                    $_SESSION['user_sess'] = $userRow['sess'];
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_slider by ID, and if ok returns true
     */

    public static function saveSlide($id, $title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_slider SET title_tm=:utitle_tm,title_ru=:utitle_ru,title_en=:utitle_en,title_tr=:utitle_tr,"
                    . "image=:uimg, text_tm=:utext_tm,text_tr=:utext_tr,"
                    . "text_ru=:utext_ru, text_en=:utext_en,"
                    . "status=:ustatus,updated_by=:uupdated_by WHERE id=:uid");
            $stmt->bindparam(":utitle_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tr", $title_tr, PDO::PARAM_STR);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->bindparam(":uimg", $image, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tr", $text_tr, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_slider by ID, and if ok returns true
     */

    public static function saveHyzmat($id, $title_tm, $title_ru, $title_en, $title_tr, $img, $text_tm, $text_ru, $text_en, $text_tr, $status) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_textler SET title_tm=:utitle_tm,title_ru=:utitle_ru,title_en=:utitle_en,"
                    . "title_tr=:utitle_tr, image=:uimg, text_tm=:utext_tm,"
                    . "text_ru=:utext_ru, text_en=:utext_en,"
                    . "text_tr=:utext_tr, status=:ustatus WHERE id=:uid");
            $stmt->bindparam(":utitle_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tr", $title_tr, PDO::PARAM_STR);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->bindparam(":uimg", $img, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tr", $text_tr, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_partner by ID, and if ok returns true
     */

    public static function savePartner($id, $name, $img, $status) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_partner SET name=:uname, image=:uimg, status=:ustatus WHERE id=:uid");
            $stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->bindparam(":uimg", $img, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_harytlar by ID, and if ok returns true
     */

    public static function saveHaryt($id, $name_ru, $name_en, $code, $image, $description_ru, $description_en, $cat_id, $seo_description, $seo_keywords, $bolum, $confirm) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_harytlar SET name_ru=:uname_ru,name_en=:uname_en,"
                    . "code=:ucode, image=:uimg, description_ru=:udescription_ru,"
                    . "description_en=:udescription_en, cat_id=:ucat_id,"
                    . "seo_description=:useo_description, seo_keywords=:useo_keywords, bolum=:ubolum, confirm=:uconfirm WHERE id=:uid");
            $stmt->bindparam(":uname_ru", $name_ru, PDO::PARAM_STR);
            $stmt->bindparam(":uname_en", $name_en, PDO::PARAM_STR);
            $stmt->bindparam(":ucode", $code, PDO::PARAM_STR);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->bindparam(":uimg", $image, PDO::PARAM_STR);
            $stmt->bindparam(":udescription_ru", $description_ru, PDO::PARAM_STR);
            $stmt->bindparam(":udescription_en", $description_en, PDO::PARAM_STR);
            $stmt->bindparam(":ucat_id", $cat_id, PDO::PARAM_STR);
            $stmt->bindparam(":useo_description", $seo_description, PDO::PARAM_STR);
            $stmt->bindparam(":useo_keywords", $seo_keywords, PDO::PARAM_STR);
            $stmt->bindparam(":ubolum", $bolum, PDO::PARAM_STR);
            $stmt->bindparam(":uconfirm", $confirm, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_textler by ID, and if ok returns true
     */

    public static function saveText($id, $type, $title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_textler SET type=:utype,title_tm=:utitle_tm,title_ru=:utitle_ru,"
                    . "title_en=:utitle_en, title_tr=:utitle_tr, image=:uimage,text_tm=:utext_tm,text_ru=:utext_ru,"
                    . "text_en=:utext_en, text_tr=:utext_tr, status=:ustatus,updated_by=:uupdated_by WHERE id=:uid");
            $stmt->bindparam(":utype", $type, PDO::PARAM_STR);
            //$stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tr", $title_tr, PDO::PARAM_STR);
            $stmt->bindparam(":uimage", $image, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tr", $text_tr, PDO::PARAM_STR);
            $stmt->bindparam(":utype", $type, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_shedule by ID, and if ok returns true
     */

    public static function saveTablo($id, $type, $title_tm, $title_ru, $title_en, $image, $text_tm, $text_ru, $text_en,  $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_shedule SET type=:utype,company=:ucompany,flight=:uflight,flight_code=:uflight_code,"
                    . "week_day=:uweek_day,departure=:udeparture,landing=:ulanding,text_tm=:utext_tm,text_ru=:utext_ru,"
                    . "text_en=:utext_en,  status=:ustatus,updated_by=:uupdated_by WHERE id=:uid");
            $stmt->bindparam(":utype", $type, PDO::PARAM_STR);
            $stmt->bindparam(":ucompany", $company, PDO::PARAM_STR);
            $stmt->bindparam(":uflight", $flight, PDO::PARAM_STR);
            $stmt->bindparam(":uflight_code", $flight_code, PDO::PARAM_STR);
            $stmt->bindparam(":uweek_day", $week_day, PDO::PARAM_STR);
            $stmt->bindparam(":udeparture", $departure, PDO::PARAM_STR);
            $stmt->bindparam(":ulanding", $landing, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);

            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_news by ID, and if ok returns true
     */

    public static function saveTazelik($id, $title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
        $id = intval($id);
        
        try {

            $stmt = $db->prepare("UPDATE tb_news SET title_tm=:utitle_tm,title_ru=:utitle_ru,"
                    . "title_en=:utitle_en, title_tr=:utitle_tr, text_tm=:utext_tm,text_ru=:utext_ru,"
                    . "text_en=:utext_en, text_tr=:utext_tr, image=:uimg,status=:ustatus,updated_by=:uupdated_by WHERE id=:uid");
            $stmt->bindparam(":utitle_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tr", $title_tr, PDO::PARAM_STR);            
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tr", $text_tr, PDO::PARAM_STR);
            $stmt->bindparam(":uimg", $image, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->execute();
            

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_dop_images by ID, and if ok returns true
     */

    public static function saveGalPhotos($table, $title_tm, $title_ru, $title_en, $id, $image, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE " . $table . " SET title_tm=:title_tm,title_ru=:title_ru,title_en=:title_en,image=:uimg, status=:ustatus, updated_by=:updated_by WHERE id=:uid");
            $stmt->bindparam(":title_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":title_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":title_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":updated_by", $updated_by, PDO::PARAM_INT);
            $stmt->bindparam(":uimg", $image, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    /*
     * Updates tb_dop_images by ID, and if ok returns true
     */

    public static function regDopPhoto($link_id, $image, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $link_id = intval($link_id);
        try {

            $stmt = $db->prepare("INSERT INTO tb_dop_images (link_id,image,status,updated_by) "
                    . "VALUES (:ulink_id,:uimg,:ustatus,:updated_by)");
            
            $stmt->bindparam(":updated_by", $updated_by, PDO::PARAM_INT);
            $stmt->bindparam(":uimg", $image, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":ulink_id", $link_id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    /*
     * Updates tb_dop_images by ID, and if ok returns true
     */

    public static function saveDopPhoto($id, $link_id, $image, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        $link_id = intval($link_id);
        try {

            $stmt = $db->prepare("UPDATE tb_dop_images SET link_id=:ulink_id, image=:uimg, status=:ustatus, updated_by=:updated_by WHERE id=:uid");
            
            $stmt->bindparam(":updated_by", $updated_by, PDO::PARAM_INT);
            $stmt->bindparam(":uimg", $image, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":ulink_id", $link_id, PDO::PARAM_INT);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Register tb_shedule by ID, and if ok returns true
     */

    public static function regText($title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $stmt = $db->prepare("INSERT INTO tb_textler(type,name,title_tm,title_ru,title_en, title_tr, image,text_tm,text_ru,text_en,updated_by,status)
                    VALUES(:utype,:uname,:utitle_tm,:utitle_ru,:utitle_en, :utitle_tr,:uimage,:utext_tm,:utext_ru,:utext_en,:uupdated_by,:ustatus)");
            $stmt->bindparam(":utype", $type, PDO::PARAM_STR);
            $stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tr", $title_tr, PDO::PARAM_STR);
            $stmt->bindparam(":uimage", $image, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tr", $text_tr, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();
            $lastID = $db->lastInsertId();
            $strID = '-' . $lastID;
            $stmt = $db->prepare("UPDATE tb_textler SET name = CONCAT(name, $strID) WHERE id=" . $lastID);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Register tb_shedule by ID, and if ok returns true
     */

    public static function regTablo($type, $company, $flight, $flight_code, $week_day, $departure, $landing, $text_tm, $text_ru, $text_en, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $stmt = $db->prepare("INSERT INTO tb_shedule(type,company,flight,flight_code,week_day,departure,landing,text_tm,text_ru,text_en,updated_by,status)
                    VALUES(:utype,:ucompany,:uflight,:uflight_code,:uweek_day,:udeparture,:ulanding,:utext_tm,:utext_ru,:utext_en,:uupdated_by,:ustatus)");
            $stmt->bindparam(":utype", $type, PDO::PARAM_STR);
            $stmt->bindparam(":ucompany", $company, PDO::PARAM_STR);
            $stmt->bindparam(":uflight", $flight, PDO::PARAM_STR);
            $stmt->bindparam(":uflight_code", $flight_code, PDO::PARAM_STR);
            $stmt->bindparam(":uweek_day", $week_day, PDO::PARAM_STR);
            $stmt->bindparam(":udeparture", $departure, PDO::PARAM_STR);
            $stmt->bindparam(":ulanding", $landing, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Register tb_news by ID, and if ok returns true
     */

    public static function regTazelik($title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by, $name) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $stmt = $db->prepare("INSERT INTO tb_news(title_tm,title_ru,title_en,title_tr,text_tm,text_ru,text_en,text_tr,updated_by,image,status,name)
                    VALUES(:utitle_tm,:utitle_ru,:utitle_en,:utitle_tr,:utext_tm,:utext_ru,:utext_en,:utext_tr,:uupdated_by,:uimg,:ustatus,:uname)");
            $stmt->bindparam(":utitle_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tr", $title_tr, PDO::PARAM_STR);
            $stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tr", $text_tr, PDO::PARAM_STR);
            $stmt->bindparam(":uimg", $image, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();
            $lastID = $db->lastInsertId();
            $strID = '-' . $lastID;
            $stmt = $db->prepare("UPDATE tb_news SET name = CONCAT(name, $strID) WHERE id=" . $lastID);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Register tb_dop_images by ID, and if ok returns true
     */

    public static function regGalPhoto($table, $title_tm, $title_ru, $title_en, $image, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $stmt = $db->prepare("INSERT INTO " . $table . " (title_tm, title_ru, title_en, image, status, updated_by)
                    VALUES(:title_tm, :title_ru, :title_en, :uimg, :ustatus, :uupdated_by)");
            $stmt->bindparam(":title_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":title_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":title_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uimg", $image, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Register tb_slider by ID, and if ok returns true
     */

    public static function regSlide($title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $db->prepare("INSERT INTO tb_slider (title_tm,title_ru,title_en,title_tr,image,text_tm,text_ru,text_en,text_tr,status,updated_by) "
                    . "VALUES (:utitle_tm,:utitle_ru,:utitle_en,:utitle_tr,:uimg,:utext_tm,:utext_ru,:utext_en,:utext_tr,:ustatus,:uupdated_by)");
            $stmt->bindparam(":utitle_tm", $title_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_ru", $title_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_en", $title_en, PDO::PARAM_STR);
            $stmt->bindparam(":utitle_tr", $title_tr, PDO::PARAM_STR);
            $stmt->bindparam(":uimg", $img, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $text_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_ru", $text_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $text_en, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tr", $text_tr, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Register tb_partner by ID, and if ok returns true
     */

    public static function regPartner($name, $img, $status) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $db->prepare("INSERT INTO tb_partner (name,image,status) "
                    . "VALUES (:uname,:uimg,:ustatus)");
            $stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":uimg", $img, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
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
     * Returns single data item specified by id from given table
     * param integer id, param sting table name
     */
    public static function getImagesById($id, $table) {
        $id = intval($id);

        if ($id) {
            $dataItem = array();

            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $result = $db->prepare('SELECT * FROM ' . $table . ' WHERE is_id=:id');
                $result->execute(array(':id' => $id));

                //$result -> setFetchMode(PDO::FETCH_NUM); 
                //$result -> setFetchMode(PDO::FETCH_ASSOC); 

                $dataItem = array();

                if (!empty($result)) {
                    $i = 0;
                    while ($row = $result->fetch()) {
                        $dataItem[$i] = $row;
                        $i++;
                    }
                }

                return $dataItem;
            } catch (PDOException $e) {
                echo 'Ошибка: ' . $e->getMessage();
            }
            die();
        }
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

    /*
     * deletes from any table by id
     */

    public static function delRecord($id, $table) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {
            $stmt = $db->query("DELETE FROM " . $table . " WHERE id=" . $id);
            /* $stmt->bindparam(":utable", $table, PDO::PARAM_STR);
              $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
              $stmt->execute(); */

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * deletes from any users table by user_id
     */

    public static function delUser($user_id, $table) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_id = intval($user_id);
        try {
            $stmt = $db->query("DELETE FROM " . $table . " WHERE user_id=" . $user_id);
            /* $stmt->bindparam(":utable", $table, PDO::PARAM_STR);
              $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
              $stmt->execute(); */

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Returns an array of review items of given product_id
     */
    public static function getActiveHyazgy() {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $result = $db->query("SELECT * FROM tb_dorojka WHERE status='1' AND until>NOW() ORDER BY date DESC LIMIT 1");

            //$result->execute(array(':unum' => $num));
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $row = $result->fetch();

            $row['date'] = date('d-m-Y', strtotime($row['date']));


            return $row;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of flights 
     */
    public static function getTextsList($type) {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $result = $db->prepare('SELECT * FROM tb_textler WHERE type=:utype ORDER BY date DESC');
            $result->execute(array(':utype' => $type));

            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $textsList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $textsList[$i] = $row;
                    $i++;
                }
            }
            return $textsList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of flights 
     */
    public static function getFlightsList($type) {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $result = $db->prepare('SELECT * FROM tb_shedule WHERE type=:utype ORDER BY departure DESC');
            $result->execute(array(':utype' => $type));

            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $flightsList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $flightsList[$i] = $row;
                    $i++;
                }
            }
            return $flightsList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of review items of given product_id
     */
    public static function getHyazgy($id = 0) {
        $id = intval($id);

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            if ($id == 0) {
                $result = $db->query("SELECT * FROM tb_dorojka ORDER BY date DESC");

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
            } else {
                $result = $db->query('SELECT * FROM tb_dorojka WHERE id=' . $id . ' LIMIT 1');

                //$result->execute(array(':unum' => $num));
                //$result -> setFetchMode(PDO::FETCH_NUM);
                $result->setFetchMode(PDO::FETCH_ASSOC);

                $teswirList = $result->fetch();
            }
            return $teswirList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of review items of given product_id
     */
    public static function getUsersPagin($pag_page = 1) {
        $pagin = intval($pag_page);

        $offset = ($pag_page - 1) * ITEMS_PER_PAGE;

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $result = $db->query("SELECT * FROM tb_users ORDER BY reg_date DESC LIMIT " . ITEMS_PER_PAGE . " OFFSET " . $offset);

            //$result->execute(array(':unum' => $num));
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $teswirList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $teswirList[$i] = $row;
                    $teswirList[$i]['reg_date'] = date('d-m-Y', strtotime($teswirList[$i]['reg_date']));
                    $i++;
                }
            }

            return $teswirList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of review items of given product_id
     */
    public static function getReviews($pag_page = 1) {
        $pagin = intval($pag_page);

        $offset = ($pag_page - 1) * ITEMS_PER_PAGE;

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $result = $db->query("SELECT * FROM tb_teswirler ORDER BY id DESC LIMIT " . ITEMS_PER_PAGE . " OFFSET " . $offset);

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
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of review items of given product_id
     */
    public static function getUserID($id) {
        $id = intval($id);

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $result = $db->query('SELECT * FROM tb_users WHERE user_id=' . $id . ' LIMIT 1');

            //$result->execute(array(':unum' => $num));
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $userList = $result->fetch();

            return $userList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of review items of given product_id
     */
    public static function getReviewID($id) {
        $id = intval($id);

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $result = $db->query('SELECT * FROM tb_teswirler WHERE id=' . $id . ' LIMIT 1');

            //$result->execute(array(':unum' => $num));
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $teswirList = $result->fetch();

            return $teswirList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /**
     * Returns an array of unсonfirmed review items 
     */
    public static function getConfirmReviews() {

        $db = DB::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $result = $db->query("SELECT * FROM tb_teswirler WHERE confirm='0' ORDER BY date DESC");

            //$result->execute(array(':unum' => $num));
            //$result -> setFetchMode(PDO::FETCH_NUM);
            $result->setFetchMode(PDO::FETCH_ASSOC);

            $teswirList = array();

            if (!empty($result)) {
                $i = 0;
                while ($row = $result->fetch()) {
                    $teswirList[$i] = $row;
                    $i++;
                }
            }

            return $teswirList;
        } catch (PDOException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
        die();
    }

    /*
     * Register tb_dorojka and if ok returns true
     */

    public static function regHyazgy($until, $hyazgy_tm, $hyazgy_ru, $hyazgy_en, $status, $updated_by) {

        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $db->prepare("INSERT INTO tb_dorojka(until,text_tm,text_ru,text_en,status,updated_by)
                    VALUES(:uuntil,:utext_tm,:utext_ru,:utext_en,:ustatus,:uupdated_by)");
            $stmt->bindparam(":utext_ru", $hyazgy_ru, PDO::PARAM_STR);
            $stmt->bindparam(":utext_tm", $hyazgy_tm, PDO::PARAM_STR);
            $stmt->bindparam(":utext_en", $hyazgy_en, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uuntil", $until, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Register tb_teswirler and if ok returns true
     */

    public static function regReview($name, $email, $review_tm, $review_ru, $review_en, $product_id, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $db->prepare("INSERT INTO tb_teswirler(name,email,review_tm,review_ru,review_en,status,updated_by)
                    VALUES(:uname,:uemail,:ureview_tm,ureview_ru,:ureview_en,:ustatus,:uupdated_by)");
            $stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":uemail", $email, PDO::PARAM_STR);
            $stmt->bindparam(":ureview_ru", $review_ru, PDO::PARAM_STR);
            $stmt->bindparam(":ureview_tm", $review_tm, PDO::PARAM_STR);
            $stmt->bindparam(":ureview_en", $review_en, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_dorojka by ID, and if ok returns true
     */

    public static function updateHyazgy($id, $until, $hyazgy_tm, $hyazgy_ru, $hyazgy_en, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_dorojka SET text_tm=:uhyazgy_tm, text_ru=:uhyazgy_ru, text_en=:uhyazgy_en,"
                    . "until=:uuntil, status=:ustatus, updated_by=:uupdated_by WHERE id=:uid");
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->bindparam(":uhyazgy_tm", $hyazgy_tm, PDO::PARAM_STR);
            $stmt->bindparam(":uhyazgy_ru", $hyazgy_ru, PDO::PARAM_STR);
            $stmt->bindparam(":uhyazgy_en", $hyazgy_en, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uuntil", $until, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_teswirler by ID, and if ok returns true
     */

    public static function updateReview($id, $name, $email, $review_tm, $review_ru, $review_en, $status, $updated_by) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_teswirler SET name=:uname, email=:uemail, "
                    . "review_tm=:ureview_tm, review_ru=:ureview_ru, review_en=:ureview_en, status=:ustatus, updated_by=:uupdated_by WHERE id=:uid");
            $stmt->bindparam(":uname", $name, PDO::PARAM_STR);
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->bindparam(":uemail", $email, PDO::PARAM_STR);
            $stmt->bindparam(":ureview_tm", $review_tm, PDO::PARAM_STR);
            $stmt->bindparam(":ureview_ru", $review_ru, PDO::PARAM_STR);
            $stmt->bindparam(":ureview_en", $review_en, PDO::PARAM_STR);
            $stmt->bindparam(":ustatus", $status, PDO::PARAM_STR);
            $stmt->bindparam(":uupdated_by", $updated_by, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Updates tb_request by ID, confirms readed
     */

    public static function updateRequest($id) {
        $db = DB:: getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = intval($id);
        try {

            $stmt = $db->prepare("UPDATE tb_request SET confirm=1 WHERE id=:uid");
            $stmt->bindparam(":uid", $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}
