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

class ZaprosController {

    public function actionIndex($dil = 'tm') {

        $dil = Functions::clearStr($dil);
        include ROOT . '/template/lang/' . $dil . '.php';

        if (isset($_POST['reqSubmit'])) {
            //registr a comment
            $zaprosname = $_POST['zaprosname'];
            $zaprosphone = $_POST['zaprosphone'];
            $zaprosmail = $_POST['zaprosmail'];
            $zaproscompany = $_POST['zaproscompany'];
            $zaprosmesage = $_POST['zaprosmesage'];

            $harytZapros = '<table>'
                    . '<tr>'
                    . '<th>' . $zap_kod . '</th>'
                    . '<th>' . $qty . '</th>';
            if (SHOW_PRICE > 0) {
                if (SHOW_PRICE == 1)
                    $currency = 'TMM';
                else
                    $currency = '$';
                $harytZapros .= '<th>' . $zap_price . '</th>' . '<th>' . $total . '</th>';
            }
            $harytZapros .= '</tr>';

            if (isset($_SESSION["products"]) && count($_SESSION["products"]) > 0) {
                $total_amount = 0;
                $total_qua = 0;
                foreach ($_SESSION["products"] as $product) { //Print each item, quantity and price.
                    $product_code = $product["product_code"];
                    $productItem = Esasy::getHarytByCODE($product_code);
                    $product_qty = $product["product_qty"];
                    if (SHOW_PRICE > 0) {
                        $product_price = $productItem["price"];
                        $subtotal = ($product_price * $product_qty); //Multiply item quantity * price
                        $total_amount = ($total_amount + $subtotal); //Add up to total price
                    }
                    $total_qua = ($total_qua + $product_qty);

                    $harytZapros .= '<tr>'
                            . '<td><p>' . $product_code . '</p></td>'
                            . '<td><p>' . $product_qty . '</p></td>';
                    if (SHOW_PRICE > 0) {
                        $harytZapros .= '<td><p>' . sprintf("%01.2f", $product_price) . $currency . '</p></td>'
                                . '<td><p>' . sprintf("%01.2f", $subtotal) . $currency . '</p></td>';
                    }
                    $harytZapros .= '</tr>';
                }
                $harytZapros .= '<tr>'
                        . '<td ><strong>' . $results . '</strong></td>'
                        . '<td><strong>' . $total_qua . '</strong></td>';
                if (SHOW_PRICE > 0) {
                    $harytZapros .= '<td></td>'
                            . '<td><strong>' . sprintf("%01.2f", $total_amount) . $currency . '</strong></td>';
                }
                $harytZapros .= '</tr>';
            }
            $harytZapros .='</table>';


            Esasy::regRequest($harytZapros, $zaprosname, $zaprosmail, $zaprosphone, $zaproscompany, $zaprosmesage);
            unset($_SESSION["products"]);
            
            $zaprosSuccess = $zaprosSent;
        }
        
        $catsListMenu = Esasy::getLimitedCatList();

        $shortAbout = Esasy::getTextById(1);

        $textAdres = Esasy::getTextByName('cont-adres');

        $mailTel = Esasy::getTextByName('mail-tel');

        $page = 'zapros';

        $title = ${$page};

        $catList = Esasy::getCatList();
        
        if (isset($zaprosSuccess)) $mess = $zaprosSuccess;  else  $mess = $cart_epty; 

        switch ($dil) {
            case "tm":
                $dil_full = "Türkmençe";
                break;
            case "ru":
                $dil_full = "Русский";
                break;
            case "en":
                $dil_full = "English";
                break;
            case "tr":
                $dil_full = "Türkçe";
                break;
        }

        require_once(ROOT . '/template/factory/zapros.tpl.php');
//factory
//default
        return true;
    }

}
