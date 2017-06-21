<?php
/**
 * Created by PhpStorm.
 * User: ukorjidechi
 * Date: 12/8/16
 * Time: 6:26 AM
 */

require_once ("../src/class.phone.number.php");

if(isset($_POST['phone_number'])){
    $pn = htmlspecialchars($_POST['phone_number']);

    $v_pn = new \ukorJidechi\validate_phone_number($pn);

    echo json_encode($v_pn->is_phone_number());

}