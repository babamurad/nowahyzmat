<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Functions {
    /*
     * translates link(string in cirilyc or turkmen) to seolink 
     */

    public static function trans_to_seo($str) {
        $tr = array(
            "А" => "a", "Б" => "b", "В" => "v", "Г" => "g",
            "Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i",
            "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
            "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
            "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch",
            "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "i", "Ь" => "",
            "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
            "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "",
            "ы" => "i", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
            "A" => "a", "B" => "b", "D" => "d", "E" => "e", "F" => "f",
            "G" => "g", "H" => "h", "I" => "i", "J" => "j", "K" => "k",
            "L" => "l", "M" => "m", "N" => "n", "O" => "o", "P" => "p",
            "R" => "r", "S" => "s", "D" => "d", "T" => "t", "U" => "u",
            "W" => "w", "Y" => "y", "Z" => "z", "Ž" => "j", "ž" => "j",
            "Ä" => "a", "ä" => "a", "Ç" => "c", "ç" => "c", "Ý" => "y",
            "ý" => "y", "Ü" => "u", "ü" => "u", "Ň" => "n", "ň" => "n",
            "Ö" => "o", "ö" => "o", "Ş" => "s", "ş" => "s", " " => "-",
            "ğ" => "g", "Ğ" => "g", "ı" => "i", "I" => "i",
            "." => "", "," => "-", "/" => "/", "#" => "", "--" =>"-");
        $new_str = strtr($str, $tr);
        while (strstr($new_str, '--')) {
            $new_str = str_replace('--', '-', $new_str);
        }
        return $new_str;
    }

    /*
     * gets id from seolink(last n chars until '-')
     */

    public static function get_id_from_seo($str) {
        $str_new = substr(strrchr($str, '-'), 1);
        return $str_new;
    }
    
    /*
     * gets link of topar controller 
     */

    public static function getToparLink($dil,$pagin,$cat) {
        if ($cat=='all'){
            $link_new = '/topar/' . $dil . '/' . $pagin . '.html';
        } else {
            $link_new = '/topar/' . $dil . '/' . $pagin . '/' . $cat . '.html';
        }
        
        return $link_new;
    }
    
    public static function getYalykLink($dil,$seolink) {
        
            $link_new = '/yalyk/' . $dil . '/' . $seolink . '.html';
        
        return $link_new;
    }
    
    public static function getStatus($str) {
        if ($str == '1' ) $result = '<span style="color:green;">Görkezilýär</span>'; 
         else $result = '<span style="color:red;">Görkezilmeýär</span>';
        return $result;
    }
    
    public static function shortText($str, $lenght = 25) {
        $str = self::clearStr($str);
        $str = substr($str, 0, $lenght);
        return $str;
    }
    /*
     * gets status for just Request
     */
    public static function getRequestStatus($str) {
        if ($str == '1' ) $result = '<span style="color:green;">Okaldy</span>'; 
         else $result = '<span style="color:red;">Okalmady</span>';
        return $result;
    }
    /*
     * gets catname by given id
     */
    public static function getCatName($id) {
     $result = Admin::getCatById($id);
        return $result['text_tm'];
    }
    /*
     * gets bolum of given cat_id
     */
    public static function getBolumOfCat($id) {
     $result = Admin::getCatById($id);
        return $result['bolum'];
    }
    
    /*
     * gets product name by given roduct_id
     */
    public static function getProductName($product_id) {
     $result = Admin::getHarytById($product_id);
        return $result['name_tr'];
    }

    /*
     * registers css at header
     */

    public static function regCSS($param) {
        $paramArray = explode(',', $param);
        $string = '';
        foreach ($paramArray as $item) {
            $string .= '<link rel="stylesheet" type="text/css" href="/template/adm/css/' . $item . '">';
        }
        return $string;
    }

    /*
     * registers js at header
     */

    public static function regJS($param) {
        $paramArray = explode(',', $param);
        $string = '';
        foreach ($paramArray as $item) {
            $string .= '<script src="/template/adm/js/' . $item . '"></script>';
        }
        return $string;
    }

    /*
     * registers document ready script at footer
     */

    public static function docReady($param) {
        $string = '';

        $string .= '<script type="text/javascript">
                        $(document).ready(function (e) {'
                . $param .
                '});
                    </script>';

        return $string;
    }

    /*
     * registers window load script at footer
     */

    public static function winLOAD($param) {
        $string = '';

        $string .= '<script type="text/javascript">//<![CDATA[
      $(window).load(function () {'
                . $param .
                '});//]]>
      </script>';

        return $string;
    }

    /*
     * checks if session of user is set or not and returns user_sess or false
     */

    public static function isLogged() {
        if (isset($_SESSION['user_sess'])) {
            return true;
        } else
            return false;
    }

    /*
     * logs out by destroying session
     */

    public static function logout() {
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_sess']);
        return true;
    }

    public static function clearStr($str) {

        $str = trim($str);
        $str = htmlspecialchars($str);
//$dil = mysql_real_escape_string($dil);
        return trim($str);
    }

    public static function reDirect($url) {
        $url = '/' . $url;

        header("Location: $url");
    }

    /*
     *  chages slashes(/) to (&) of the path and returns string
     */

    public static function deFormPath($path) {
        self::clearStr($path);
        $path_array = explode('/', $path);
        $path = implode('&', $path_array);
        return $path;
    }

    /*
     *  chages (&) to slashes(/) of the path and returns string
     */

    public static function formPath($path) {
        self::clearStr($path);
        $path_array = explode('&', $path);
        $path = implode('/', $path_array);
        return $path;
    }

    /*
     * Formats and gets time from given timestamp
     * param is string
     */

    public static function getTimeByDate($timestmp) {

        $timestmp = intval($timestmp);

        $timeResult = '';

        $timeDiff = time() - $timestmp;

        $minuts = floor($timeDiff / 60);

        $hours = floor($timeDiff / (60 * 60));

        $days = floor($timeDiff / (60 * 60 * 24));

        if ($days > 7) {
            $timeResult = date("d.m.y, H:i", $timestmp);
        } elseif ($days = 7) {
            $timeResult = 'a week ago, ' . date("H:i", $timestmp);
        } elseif ($days > 1) {
            $timeResult = $days . ' days ago, ' . date("H:i", $timestmp);
        } elseif ($days = 1) {
            $timeResult = 'Yesterday, ' . date("H:i", $timestmp);
        } elseif (($days == 0) & ($hours > 0)) {
            $timeResult = 'Today, ' . date("H:i", $timestmp);
        } elseif (($days == 0) & ($hours == 0) & ($minuts > 0)) {
            $timeResult = $minuts . ' minuts ago, ' . date("H:i", $timestmp);
        }
        return $timeResult;
    }

}
