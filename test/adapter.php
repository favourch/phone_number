<?php
/**
 * @author Ukor Jidechi E. << https://ukorjidechi.com || ukorjidechi@gmail.com >> .
 * Date: 12/8/16
 * Time: 6:26 AM
 */

require_once ("../src/class.phone.number.php");

if(isset($_POST['phone_number'])){
    $pn = htmlspecialchars($_POST['phone_number']);

    $v_pn = new \ukorJidechi\validate_phone_number();

    echo json_encode($v_pn->is_phone_number($pn));

}else{
    $v_pn = new \ukorJidechi\validate_phone_number();
    echo "<pre>";
    print_r ($v_pn->is_phone_number('80201220071234'));
}