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
include_once ROOT . '/models/Admin.php';
include_once ROOT . '/components/Functions.php';

class AdminController {

    public function actionEsasy() {

        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        // $headerCSS = Functions::regCSS('style.css');

        $actionPage = 'esasy';

        require_once(ROOT . '/template/adm/adm_index.tpl.php');
        return true;
    }

    public function actionLogin() {

        if (isset($_POST['btn-login'])) {
            $umail = $_POST['email'];
            $upass = $_POST['password'];

            if (Admin::login($umail, $upass)) {

                //$path = '/admin/esasy';
                header("Location:/admin/slider/0/1");
                // Functions::reDirect($path);
// require_once(ROOT . '/' . $path);
            } else {
                $error = "Ýalňyş Maglumat!";
            }
        }

        require_once(ROOT . '/template/adm/login.tpl.php');

        return true;
    }

    public function actionLogout() {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        Functions::logout();

        require_once(ROOT . '/template/adm/login.tpl.php');

        return true;
    }

    /*
     * Registers new admin users
     */

    public function actionUlanyjy($id = 0, $pagin = 1) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $id = intval($id);
        $pagin = intval($pagin);
        $page = 'Ulanyjylar';
        $actionPage = 'ulanyjy';

        if (isset($_POST['submit-update'])) {
            $uname = trim($_POST['name']);
            //$umail = trim($_POST['email']);
            $upass = trim($_POST['pass']);
            $upass_confirm = trim($_POST['pass_confirm']);
            $uphone = trim($_POST['phone']);
            $ustatus = $_POST['status'];
            $updated_by = $userItem['user_id'];

            $error = '';
            if (!empty($upass)OR ! empty($upass_confirm)) {
                if (strlen($upass) < 6) {
                    $error .= "Açarsöz iňaz 6 simwol bolmaly";
                } else if ($upass != $upass_confirm) {
                    $error .= "Açarsöz tassyklanmady";
                }
            }

            if (empty($error)) {
                Admin::updateUser($id, $uname, $upass, $uphone, $ustatus, $updated_by);
                $id = 0;
            }
        }

        if (isset($_POST['submit-add'])) {
            $uname = trim($_POST['name']);
            $umail = trim($_POST['email']);
            $upass = trim($_POST['pass']);
            $upass_confirm = trim($_POST['pass_confirm']);
            $uphone = trim($_POST['phone']);
            $updated_by = $userItem['user_id'];
            

//echo 'name - '. $uname .' : pass - '. $upass . ': pas2 - '. $upass_confirm.' : email - '. $umail.' : phone - '. $uphone.'<br>';
            $error = '';
            if ($umail == "") {
                $error .= "Provide email";
            } else if (!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
                $error .= 'Please enter a valid email address';
            } else if ($upass == "") {
                $error .= "Provide password";
            } else if (strlen($upass) < 6) {
                $error .= "Password must be atleast 6 characters";
            } else if ($upass != $upass_confirm) {
                $error .= "Password and Confirmation are not the same";
            }
            if (empty($error)) {
                $error = Admin::checkUser($umail, $upass, $uname, $uphone, $updated_by, $error);
//Functions::reDirect('/admin/');
            }
        }
        if (isset($_POST['delete'])) {
            Admin::delUser($id, 'tb_users');
            $id = 0;
        }

        $usersList = Admin::getUserNames();

        $dataList = Admin::getUsersPagin($pagin);

        if (count($usersList) % ITEMS_PER_PAGE > 0)
            $totalPagPages = floor(count($usersList) / ITEMS_PER_PAGE) + 1;
        else
            $totalPagPages = floor(count($usersList) / ITEMS_PER_PAGE);

        if ($id > 0)
            $userUpdate = Admin::getUserID($id);

        require_once(ROOT . '/template/adm/adm_users.tpl.php');

        return true;
    }

    /*
     * Registers new admin users
     */

    public function actionSurat($link_id, $id = 0) {
//authorisation checking begins
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }
// auth checking ends
        $link_id = intval($link_id); 
        $id = intval($id);        
        $page = 'Goşmaça Suratlar';
        $actionPage = 'surat';
        $imageFormat = '480px x 320px';  
        
        
        if (isset($_POST['submit-update'])) {
            
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];
            

            Admin::saveDopPhoto($id, $link_id, $image, $status, $updated_by);
            $id = 0;
        } 
        
        if (isset($_POST['submit-add'])) {            
            
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];
            

            Admin::regDopPhoto($link_id, $image, $status, $updated_by);
        }
        
        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_dop_images');
            $id = 0;
        } 
        $usersList = Admin::getUserNames();
        $dopImagesList = Admin::getDopImages($link_id);
        
        if ($id > 0) {             
            $dopImageByID = Admin::getDataById($id, 'tb_dop_images');
            $image = $dopImageByID['image'];
        }
        
        require_once(ROOT . '/template/adm/adm_dop_images.tpl.php');

        return true;
    }
    
    /*
     * Registers new admin users
     */

    public function actionSettings() {
        if (isset($_POST['btn-update'])) {
            //$id = $_POST['id'];
            $front_items_per_page = intval($_POST['front_items_per_page']);
            $items_per_page = intval($_POST['items_per_page']);
            $image_size = intval($_POST['image_size']);
            $image_quality = intval($_POST['image_quality']);
            $show_review = isset($_POST['show_review']) ? 1 : 0;
            $show_english = isset($_POST['show_english']) ? 1 : 0;
            $show_turkish = isset($_POST['show_turkish']) ? 1 : 0;
            $show_blog = isset($_POST['show_blog']) ? 1 : 0;
            $show_dorojka = isset($_POST['show_dorojka']) ? 1 : 0;
            $display_errors = isset($_POST['display_errors']) ? 1 : 0;

//echo 'name - '. $uname .' : pass - '. $upass . ': pas2 - '. $upass_confirm.' : email - '. $umail.' : phone - '. $uphone.'<br>';
            $error = '';

            if (empty($error)) {
                $error = Admin::updateSettings($front_items_per_page, $items_per_page, $image_size, $image_quality, $show_review, $show_dorojka, $display_errors, $show_english, $show_turkish, $show_blog);
//Functions::reDirect('/admin/');
            }
        }

        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $configs = Admin::getConfigs();

        $page = 'Sazlamalar';
        $actionPage = 'settings';

        require_once(ROOT . '/template/adm/adm_settings.tpl.php');

        return true;
    }

    /*
     * Change company data
     */

    public function actionCompany() {
        if (isset($_POST['btn-update'])) {
            //$id = $_POST['id'];
            $name = trim($_POST['name']);
            $full_name_tm = trim($_POST['full_name_tm']);
            $full_name_ru = trim($_POST['full_name_ru']);


            $adres_tm = trim($_POST['adres_tm']);
            $adres_ru = trim($_POST['adres_ru']);
            if (SHOW_ENGLISH) {
                $full_name_en = trim($_POST['full_name_en']);
                $adres_en = trim($_POST['adres_en']);
            } else {
                $full_name_en = '';
                $adres_en = '';
            }
            if (SHOW_TURKISH) {
                $full_name_tr = trim($_POST['full_name_tr']);
                $adres_tr = trim($_POST['adres_tr']);
            } else {
                $full_name_tr = '';
                $adres_tr = '';
            }
            $telephone1 = trim($_POST['telephone1']);
            $telephone2 = trim($_POST['telephone2']);
            $email1 = trim($_POST['email1']);
            $email2 = trim($_POST['email2']);

            //  $show_dorojka = isset($_POST['show_dorojka']) ? 1 : 0; 
//echo 'name - '. $uname .' : pass - '. $upass . ': pas2 - '. $upass_confirm.' : email - '. $umail.' : phone - '. $uphone.'<br>';
            $error = '';

            if (empty($error)) {
                $error = Admin::updateCompanyData($name, $full_name_tm, $full_name_ru, $full_name_en, $full_name_tr, $adres_tm, $adres_ru, $adres_en, $adres_tr, $telephone1, $telephone2, $email1, $email2);
//Functions::reDirect('/admin/');
            }
        }

        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $companyData = Admin::getDataById(1, 'tb_company');

        $page = 'Firma Maglumatlary';
        $actionPage = 'company';

        require_once(ROOT . '/template/adm/adm_company.tpl.php');

        return true;
    }

    /*
     * Registers new admin users
     */

    public function actionProfile() {
        if (isset($_POST['btn-update'])) {
            //$id = $_POST['id'];
            $uname = trim($_POST['name']);
            $umail = trim($_POST['email']);
            $upass = trim($_POST['pass']);
            $upass_confirm = trim($_POST['pass_confirm']);
            $uphone = trim($_POST['phone']);

//echo 'name - '. $uname .' : pass - '. $upass . ': pas2 - '. $upass_confirm.' : email - '. $umail.' : phone - '. $uphone.'<br>';
            $error = '';
            if ((!empty($upass)) && (strlen($upass) < 6)) {
                $error .= "Password must be atleast 6 characters";
            } else if ((!empty($upass)) && ($upass != $upass_confirm)) {
                $error .= "Password and Confirmation are not the same";
            }
            if (empty($error)) {
                $error = Admin::updateUser($umail, $upass, $uname, $uphone);
//Functions::reDirect('/admin/');
            }
        }

        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $headerCSS = Functions::regCSS('style.css');

        $page = 'Ulanyjy Profili';

        require_once(ROOT . '/template/adm/adm_profile.tpl.php');

        return true;
    }

    public function actionSlider($id = 0, $pagin = 1) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $page = 'Slaýdlar';
        $actionPage = 'slider';
        $id = intval($id);
        $pagin = intval($pagin);
        $imageFormat = '1200px x 800px';


        if (isset($_POST['add_product'])) {
            $new = 'new';
            require_once(ROOT . '/template/adm/adm_record_post.tpl.php');
            return true;
        }

        if (isset($_POST['submit-add'])) {
            $title_ru = $_POST['title_ru'];
            $title_tm = $_POST['title_tm'];
            if (SHOW_ENGLISH) {
                $title_en = $_POST['title_en'];
                $text_en = $_POST['text_en'];
            } else {
                $title_en = '';
                $text_en = '';
            }
            if (SHOW_TURKISH) {
                $title_tr = $_POST['title_tr'];
                $text_tr = $_POST['text_tr'];
            } else {
                $title_tr = '';
                $text_tr = '';
            }
            $text_tm = $_POST['text_tm'];            
            $text_ru = $_POST['text_ru'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::regSlide($title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['submit-update'])) {
            $title_ru = $_POST['title_ru'];
            $title_tm = $_POST['title_tm'];
            
            if (SHOW_ENGLISH) {
                $title_en = $_POST['title_en'];
                $text_en = $_POST['text_en'];
            } else {
                $title_en = '';
                $text_en = '';
            }
            if (SHOW_TURKISH) {
                $title_tr = $_POST['title_tr'];
                $text_tr = $_POST['text_tr'];
            } else {
                $title_tr = '';
                $text_tr = '';
            }
            
            $text_tm = $_POST['text_tm'];            
            $text_ru = $_POST['text_ru'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::saveSlide($id, $title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_slider');
            $id = 0;
        }

        if ($id > 0) {

            $dataItem = Admin::getDataById($id, 'tb_slider');

            $title_ru = $dataItem['title_ru'];
            $title_tm = $dataItem['title_tm'];
            $title_en = $dataItem['title_en'];
            $text_tm = $dataItem['text_tm'];
            $text_en = $dataItem['text_en'];
            $text_ru = $dataItem['text_ru'];
            $image = $dataItem['image'];
            $status = $dataItem['status'];

            require_once(ROOT . '/template/adm/adm_record_post.tpl.php');
            return true;
        } else {

            $usersList = Admin::getUserNames();

            $totalRecords = Admin::getDataList('tb_slider');

            if (count($totalRecords) % ITEMS_PER_PAGE > 0)
                $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE) + 1;
            else
                $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE);

            $recordList = Admin::getDataListOfTable('tb_slider', $pagin);

            require_once(ROOT . '/template/adm/adm_record.tpl.php');
            return true;
        }
    }

    public function actionHyzmatlar($id = 0, $pagin = 1) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $page = 'Hyzmatlar';
        $actionPage = 'hyzmatlar';
        $id = intval($id);
        $pagin = intval($pagin);


        if (isset($_POST['add_product'])) {
            $new = 'new';
            require_once(ROOT . '/template/adm/adm_slider_post.tpl.php');
            return true;
        }

        if (isset($_POST['submit-add'])) {
            $title_ru = $_POST['title_ru'];
            $title_tm = $_POST['title_tm'];
            $title_en = $_POST['title_en'];
            $title_tr = $_POST['title_tr'];
            $text_tr = $_POST['text_tr'];
            $text_tm = $_POST['text_tm'];
            $text_en = $_POST['text_en'];
            $text_ru = $_POST['text_ru'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];

            Admin::regHyzmat($title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status);
            $id = 0;
        }

        if (isset($_POST['submit-update'])) {
            $title_ru = $_POST['title_ru'];
            $title_tm = $_POST['title_tm'];
            $title_en = $_POST['title_en'];
            $title_tr = $_POST['title_tr'];
            $text_tr = $_POST['text_tr'];
            $text_tm = $_POST['text_tm'];
            $text_en = $_POST['text_en'];
            $text_ru = $_POST['text_ru'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];

            Admin::saveHyzmat($id, $title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status);
            $id = 0;
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_hyzmatlar');
            $id = 0;
        }

        if ($id > 0) {

            $dataItem = Admin::getDataById($id, 'tb_hyzmatlar');

            $title_ru = $dataItem['title_ru'];
            $title_tm = $dataItem['title_tm'];
            $title_en = $dataItem['title_en'];
            $title_tr = $dataItem['title_tr'];
            $text_tr = $dataItem['text_tr'];
            $text_tm = $dataItem['text_tm'];
            $text_en = $dataItem['text_en'];
            $text_ru = $dataItem['text_ru'];
            $image = $dataItem['image'];
            $status = $dataItem['status'];

            require_once(ROOT . '/template/adm/adm_slider_post.tpl.php');
            return true;
        } else {

            $totalSliderler = Admin::getDataList('tb_hyzmatlar');

            $sliderList = Admin::getDataListOfTable('tb_hyzmatlar', $pagin);

            $pagination = new Pagination();

            $paginate = $pagination->calculate_pages(count($totalSliderler), ITEMS_PER_PAGE, "/admin/hyzmatlar/0/", $pagin);

            require_once(ROOT . '/template/adm/adm_slider.tpl.php');
            return true;
        }
    }

    public function actionText($id = 0) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $page = 'Tekstler';
        $actionPage = 'text';
        $id = intval($id);
        $imageFormat = '480px x 320px';  

        if (isset($_POST['add_product'])) {
            $new = 'new';
            require_once(ROOT . '/template/adm/adm_text_post.tpl.php');
            return true;
        }

        if (isset($_POST['submit-add'])) {
            $type = $_POST['type'];
            $name = Functions::trans_to_seo($_POST['title_tm']);
            $title_tm = $_POST['title_tm'];
            $title_ru = $_POST['title_ru'];
            
            if (SHOW_ENGLISH) {
                $title_en = $_POST['title_en'];
                $text_en = $_POST['text_en'];
            } else {
                $title_en = '';
                $text_en = '';
            }
            if (SHOW_TURKISH) {
                $title_tr = $_POST['title_tr'];
                $text_tr = $_POST['text_tr'];
            } else {
                $title_tr = '';
                $text_tr = '';
            }
            $text_tm = $_POST['text_tm'];
            $text_ru = $_POST['text_ru'];            
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::regText($type, $name, $title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['submit-update'])) {
            $type = $_POST['type'];
            //$name = $_POST['name'];
            $title_tm = $_POST['title_tm'];
            $title_ru = $_POST['title_ru'];
            if (SHOW_ENGLISH) {
                $title_en = $_POST['title_en'];
                $text_en = $_POST['text_en'];
            } else {
                $title_en = '';
                $text_en = '';
            }
            if (SHOW_TURKISH) {
                $title_tr = $_POST['title_tr'];
                $text_tr = $_POST['text_tr'];
            } else {
                $title_tr = '';
                $text_tr = '';
            }
            $text_tm = $_POST['text_tm'];            
            $text_ru = $_POST['text_ru'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::saveText($id, $type, $title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_textler');
            $id = 0;
        }

        if ($id > 0) {

            $dataItem = Admin::getDataById($id, 'tb_textler');

            $type = $dataItem['type'];
            $name = $dataItem['name'];
            $title_tm = $dataItem['title_tm'];
            $title_ru = $dataItem['title_ru'];
            $title_en = $dataItem['title_en'];
            $title_tr = $dataItem['title_tr'];
            $text_tm = $dataItem['text_tm'];
            $text_en = $dataItem['text_en'];
            $text_ru = $dataItem['text_ru'];
            $text_tr = $dataItem['text_tr'];
            $image = $dataItem['image'];
            $status = $dataItem['status'];
            
            if ($type == 3){
                
                $dopImagesList = Admin::getDopImages($id);
                
            }

            require_once(ROOT . '/template/adm/adm_text_post.tpl.php');
            return true;
        } else {

            $usersList = Admin::getUserNames();

            $mainTextsList = Admin::getTextsList(1);
            $serviceTextsList = Admin::getTextsList(2);
            $dutyTextsList = Admin::getTextsList(3);
            $partnersTextsList = Admin::getTextsList(4);

            require_once(ROOT . '/template/adm/adm_text.tpl.php');
            return true;
        }
    }

    public function actionGatnaw($id = 0) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $page = 'Gatnawlaryň rejesi';
        $actionPage = 'gatnaw';
        $id = intval($id);

        $imageFormat = '1200px x 900px';


        if (isset($_POST['add_product'])) {
            $new = 'new';
            require_once(ROOT . '/template/adm/adm_schedule_post.tpl.php');
            return true;
        }

        if (isset($_POST['submit-add'])) {
            $type = $_POST['type'];
            $flight = $_POST['flight'];
            $flight_code = $_POST['flight_code'];
            $week_day = $_POST['week_day'];
            $company = $_POST['company'];
            $text_tm = $_POST['text_tm'];
            $text_en = $_POST['text_en'];
            $text_ru = $_POST['text_ru'];
            $departure = $_POST['departure'];
            $landing = $_POST['landing'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::regTablo($type, $company, $flight, $flight_code, $week_day, $departure, $landing, $text_tm, $text_ru, $text_en, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['submit-update'])) {
            $type = $_POST['type'];
            $flight = $_POST['flight'];
            $flight_code = $_POST['flight_code'];
            $week_day = $_POST['week_day'];
            $company = $_POST['company'];
            $text_tm = $_POST['text_tm'];
            $text_en = $_POST['text_en'];
            $text_ru = $_POST['text_ru'];
            $departure = $_POST['departure'];
            $landing = $_POST['landing'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::saveTablo($id, $type, $company, $flight, $flight_code, $week_day, $departure, $landing, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_shedule');
            $id = 0;
        }

        if ($id > 0) {

            $dataItem = Admin::getDataById($id, 'tb_shedule');

            $type = $dataItem['type'];
            $flight = $dataItem['flight'];
            $flight_code = $dataItem['flight_code'];
            $week_day = $dataItem['week_day'];
            $company = $dataItem['company'];
            $text_tm = $dataItem['text_tm'];
            $text_en = $dataItem['text_en'];
            $text_ru = $dataItem['text_ru'];
            $departure = $dataItem['departure'];
            $landing = $dataItem['landing'];
            $status = $dataItem['status'];

            require_once(ROOT . '/template/adm/adm_schedule_post.tpl.php');
            return true;
        } else {

            $usersList = Admin::getUserNames();

            $mainDepFlightsList = Admin::getFlightsList('0');
            $mainLandFlightsList = Admin::getFlightsList('1');
            $foreignDepFlightsList = Admin::getFlightsList('2');
            $foreignLandFlightsList = Admin::getFlightsList('3');

            require_once(ROOT . '/template/adm/adm_schedule.tpl.php');
            return true;
        }
    }

    public function actionTazelik($id = 0, $pagin = 1) {

        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $page = 'Täzelikler';
        $actionPage = 'tazelik';
        $id = intval($id);
        $pagin = intval($pagin);
        $imageFormat = '480px x 320px';


        if (isset($_POST['add_product'])) {
            $new = 'new';
            require_once(ROOT . '/template/adm/adm_record_post.tpl.php');
            return true;
        }

        if (isset($_POST['submit-add'])) {
            $title_ru = $_POST['title_ru'];
            $title_tm = $_POST['title_tm'];
            $name = Functions::trans_to_seo($_POST['title_tm']);
            if (SHOW_ENGLISH) {
                $title_en = $_POST['title_en'];
                $text_en = $_POST['text_en'];
            } else {
                $title_en = '';
                $text_en = '';
            }
            if (SHOW_TURKISH) {
                $title_tr = $_POST['title_tr'];
                $text_tr = $_POST['text_tr'];
            } else {
                $title_tr = '';
                $text_tr = '';
            }            
            $text_tm = $_POST['text_tm'];            
            $text_ru = $_POST['text_ru'];            
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::regTazelik($title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by, $name);
            $id = 0;
        }

        if (isset($_POST['submit-update'])) {
            $title_ru = $_POST['title_ru'];
            $title_tm = $_POST['title_tm'];
           if (SHOW_ENGLISH) {
                $title_en = $_POST['title_en'];
                $text_en = $_POST['text_en'];
            } else {
                $title_en = '';
                $text_en = '';
            }
            if (SHOW_TURKISH) {
                $title_tr = $_POST['title_tr'];
                $text_tr = $_POST['text_tr'];
            } else {
                $title_tr = '';
                $text_tr = '';
            }
            $text_tm = $_POST['text_tm'];           
            $text_ru = $_POST['text_ru'];            
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::saveTazelik($id, $title_tm, $title_ru, $title_en, $title_tr, $image, $text_tm, $text_ru, $text_en, $text_tr, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_news');
            $id = 0;
        }

        if ($id > 0) {

            $dataItem = Admin::getDataById($id, 'tb_news');

            $title_ru = $dataItem['title_ru'];
            $title_tm = $dataItem['title_tm'];
            $title_en = $dataItem['title_en'];
            $text_tm = $dataItem['text_tm'];
            $text_en = $dataItem['text_en'];
            $text_ru = $dataItem['text_ru'];
            $image = $dataItem['image'];
            $name = $dataItem['name'];
            $status = $dataItem['status'];

            require_once(ROOT . '/template/adm/adm_record_post.tpl.php');
            return true;
        } else {

            $usersList = Admin::getUserNames();

            $totalRecords = Admin::getDataList('tb_news');

            if (count($totalRecords) % ITEMS_PER_PAGE > 0)
                $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE) + 1;
            else
                $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE);

            $recordList = Admin::getDataListOfTable('tb_news', $pagin);


            require_once(ROOT . '/template/adm/adm_record.tpl.php');
            return true;
        }
    }

    public function actionGalereya($id = 0, $pagin = 1) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $page = 'Galereýa Suratlary';
        $actionPage = 'galereya';
        $id = intval($id);
        $pagin = intval($pagin);

        $imageFormat = '400px x 300px';

        if (isset($_POST['submit-add'])) {
            $title_tm = $_POST['title_tm'];
            $title_ru = $_POST['title_ru'];
            $title_en = $_POST['title_en'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::regGalPhoto('tb_gal_images', $title_tm, $title_ru, $title_en, $image, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['submit-update'])) {
            $title_tm = $_POST['title_tm'];
            $title_ru = $_POST['title_ru'];
            $title_en = $_POST['title_en'];
            //$id = $_POST['id'];
            $status = $_POST['status'];
            $image = $_POST['uploadedPath'];
            $updated_by = $userItem['user_id'];

            Admin::saveGalPhotos('tb_gal_images', $title_tm, $title_ru, $title_en, $id, $image, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_gal_images');
            $id = 0;
        }

        $usersList = Admin::getUserNames();

        $dataList = Admin::getDataListOfTable('tb_gal_images', $pagin);

        $totalRecords = Admin::getDataList('tb_gal_images');

        if (count($totalRecords) % ITEMS_PER_PAGE > 0)
            $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE) + 1;
        else
            $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE);

        if ($id > 0)
            $suratUpdate = Admin::getDataById($id, 'tb_gal_images');

        require_once(ROOT . '/template/adm/adm_galereya.tpl.php');
        return true;
    }

    public function actionUgur($id = 0, $pagin = 1) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $page = 'Ugurlaryň Suratlary';
        $actionPage = 'ugur';
        $id = intval($id);
        $pagin = intval($pagin);

        $imageFormat = '400px x 300px';

        if (isset($_POST['submit-add'])) {
            $title_tm = $_POST['title_tm'];
            $title_ru = $_POST['title_ru'];
            $title_en = $_POST['title_en'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::regGalPhoto('tb_fly_images', $title_tm, $title_ru, $title_en, $image, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['submit-update'])) {
            $title_tm = $_POST['title_tm'];
            $title_ru = $_POST['title_ru'];
            $title_en = $_POST['title_en'];
            //$id = $_POST['id'];
            $status = $_POST['status'];
            $image = $_POST['uploadedPath'];
            $updated_by = $userItem['user_id'];

            Admin::saveGalPhotos('tb_fly_images', $title_tm, $title_ru, $title_en, $id, $image, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_fly_images');
            $id = 0;
        }

        $usersList = Admin::getUserNames();

        $dataList = Admin::getDataListOfTable('tb_fly_images', $pagin);

        $totalRecords = Admin::getDataList('tb_fly_images');

        if (count($totalRecords) % ITEMS_PER_PAGE > 0)
            $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE) + 1;
        else
            $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE);

        if ($id > 0)
            $suratUpdate = Admin::getDataById($id, 'tb_fly_images');

        require_once(ROOT . '/template/adm/adm_galereya.tpl.php');
        return true;
    }

    public function actionPartner($id = 0, $pagin = 1) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $page = 'Partnerler';
        $actionPage = 'partner';
        $id = intval($id);
        $pagin = intval($pagin);

        $headerCSS = Functions::regCSS('style.css');

        if (isset($_POST['update'])) {
            $name = $_POST['name'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];

            Admin::savePartner($id, $name, $image, $status);
            $id = 0;
        } else if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $image = $_POST['uploadedPath'];
            $status = $_POST['status'];

            Admin::regPartner($name, $image, $status);
            $id = 0;
        } else if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_partner');
            $id = 0;
        }

        if ($id > 0)
            $dataItem = Admin::getDataById($id, 'tb_partner');


        $totalPartners = Admin::getDataList('tb_partner');

        $partnerList = Admin::getDataListOfTable('tb_partner', $pagin);

        $pagination = new Pagination();

        $paginate = $pagination->calculate_pages(count($totalPartners), ITEMS_PER_PAGE, "/admin/partner/0/", $pagin);

        require_once(ROOT . '/template/adm/adm_partnerlar.tpl.php');
        return true;
    }

    /*
     * Begovaya dorojka controller
     */

    public function actionHyazgy($id = 0, $pagin = 1) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $id = intval($id);
        $pagin = intval($pagin);
        $page = 'Hereketli Ýazgy';
        $actionPage = 'hyazgy';

        if (isset($_POST['submit-update'])) {
            $until = $_POST['until'];
            $hyazgy_tm = trim($_POST['text_tm']);
            $hyazgy_ru = trim($_POST['text_ru']);
            $hyazgy_en = trim($_POST['text_en']);
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::updateHyazgy($id, $until, $hyazgy_tm, $hyazgy_ru, $hyazgy_en, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['submit-add'])) {
            $until = $_POST['until'];
            $hyazgy_tm = trim($_POST['text_tm']);
            $hyazgy_ru = trim($_POST['text_ru']);
            $hyazgy_en = trim($_POST['text_en']);
            $status = $_POST['status'];
            $updated_by = $userItem['user_id'];

            Admin::regHyazgy($until, $hyazgy_tm, $hyazgy_ru, $hyazgy_en, $status, $updated_by);
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_dorojka');
            $id = 0;
        }

        $usersList = Admin::getUserNames();

        $activeBildiris = Admin::getActiveHyazgy();

        $totalRecords = Admin::getDataList('tb_dorojka');

        if (count($totalRecords) % (ITEMS_PER_PAGE * 10) > 0)
            $totalPagPages = floor(count($totalRecords) / (ITEMS_PER_PAGE * 10) + 1);
        else
            $totalPagPages = floor(count($totalRecords) / (ITEMS_PER_PAGE * 10));

        $bildirisList = Admin::getDataListOfTable('tb_dorojka', $pagin);

        if ($id > 0)
            $yazgyUpdate = Admin::getHyazgy($id);

        require_once(ROOT . '/template/adm/adm_bildiris.tpl.php');
        return true;
    }

    /*
     * Review controller
     */

    public function actionTeswir($id = 0, $pagin = 1) {
        if (Functions::isLogged()) {
            $userItem = Admin::checkUserSESS($_SESSION['user_sess']);
        }
        if (empty($userItem)) {
            require_once(ROOT . '/template/adm/login.tpl.php');
            return true;
        }

        $id = intval($id);
        $pagin = intval($pagin);
        $page = 'Teswirler';
        $actionPage = 'teswir';

        if (isset($_POST['submit-update'])) {
            $name = $_POST['name'];
            $status = $_POST['status'];
            $review_tm = $_POST['review_tm'];
            $review_ru = $_POST['review_ru'];
            $review_en = $_POST['review_en'];
            $email = $_POST['email'];
            $updated_by = $userItem['user_id'];

            Admin::updateReview($id, $name, $email, $review_tm, $review_ru, $review_en, $status, $updated_by);
            $id = 0;
        }

        if (isset($_POST['submit-add'])) {
            $name = $_POST['name'];
            $review_tm = $_POST['review_tm'];
            $review_ru = $_POST['review_ru'];
            $review_en = $_POST['review_en'];
            $email = $_POST['email'];
            $updated_by = $userItem['user_id'];

            Admin::regReview($name, $email, $review_tm, $review_ru, $review_en, $status, $updated_by);
        }

        if (isset($_POST['delete'])) {
            Admin::delRecord($id, 'tb_teswirler');
            $id = 0;
        }

        $usersList = Admin::getUserNames();

        $reviewList = Admin::getReviews($pagin);

        $totalRecords = Admin::getDataList('tb_teswirler');

        if (count($totalRecords) % ITEMS_PER_PAGE > 0)
            $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE) + 1;
        else
            $totalPagPages = floor(count($totalRecords) / ITEMS_PER_PAGE);

        if ($id > 0)
            $reviewUpdate = Admin::getReviewID($id);

        require_once(ROOT . '/template/adm/adm_reviews.tpl.php');
        return true;
    }

}
