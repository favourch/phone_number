<?php
/**
 * Created by PhpStorm.
 * User: ukorjidechi
 * Date: 12/4/16
 * Time: 2:27 AM
 */

namespace ukorJidechi;


class validate_phone_number {

    /** The phone number */
    private $pn;


    /** Holds the list of all network provider */
    private $network_provider = array(
        'Aitel_NG', 'MTN_NG', 'Etisalat_NG', 'GLO_NG', 'VISAFONE', 'MULTILINKS', 'STARCOMMS', 'ZOOM MOBILE', 'MTEL'
    );



    /** Network provider id as key and the network provider name as value e.g. '0802' => 'AIRTEL_NG' */
    private $np_id = array(
        '0803' => 'MTN_NG', '0806' => 'MTN_NG', '0703' => 'MTN_NG', '0706' => 'MTN_NG',
        '0813' => 'MTN_NG', '0816' => 'MTN_NG', '0810' => 'MTN_NG', '0814' => 'MTN_NG', '0903' => 'MTN_NG',

        '0705' => 'GLO_NG', '0815' => 'GLO_NG', '0805' => 'GLO_NG', '0811' => 'GLO_NG', '0905' => 'GLO_NG',

        '0708' => 'AIRTEL_NG', '0812' => 'AIRTEL_NG', '0802' => 'AIRTEL_NG',
        '0902' => 'AIRTEL_NG', '0808' => 'AIRTEL_NG', '0701' => 'AIRTEL_NG',

        '0809' => 'ETISALAT_NG', '0817' => 'ETISALAT_NG', '0818' => 'ETISALAT_NG',
        '0909' => 'ETISALAT_NG', '0908' => 'ETISALAT_NG',

        '0704' => 'VISAFONE', '07025' => 'VISAFONE', '07026' => 'VISAFONE',

        '0709' => 'MULTILINKS', '07027' => 'MULTILINKS',

        '0819' => 'STARCOMMS', '07028' => 'STARCOMMS', '07029' => 'STARCOMMS',

        '0707' => 'ZOOM MOBILE',

        '0804' => 'MTEL'
    );



    /** Holds the result */
    protected $result_set = array();

    function __construct($phone_number) {
        return $this->is_phone_number($phone_number);
    }

    /**
     * strips all special and numeric character from the phone number
     * @param $pn
     * @return mixed
     */
    function remove_char($pn){
        /** removes white space and non numeric characters from the phone number */
        return preg_replace("/[\D\s]/", '', $pn);
    }

    function check_format($pn){
        $this->pn = $this->remove_char($pn);
        if(strlen($this->pn) === 14){

            /** verify number contains country code (cc) */
            if(preg_match("/234[0-9]{11}/", $this->pn, $matches)){

                if(preg_match("/234[0]/", $matches[0])){
                    $this->result_set[0]['np_id'] = substr($this->pn, 3, 4);
                    $this->result_set[0]['phone_number'] = $matches[0];
                    $this->result_set[0]['isError'] = false;
                }else{
                    $this->result_set[0]['isError']  = true;
                    $this->result_set[0]['msg'] = "Number should begin with zero after country code (234)";
                    $this->result_set[0]['code'] = "9xx";
                }
            }else{
                $this->result_set[0]['isError']  = true;
                $this->result_set[0]['msg'] = "Number is not a valid phone number";
                $this->result_set[0]['code'] = "9xx";
            }
        }
        elseif (strlen($this->pn) === 11){

            if (preg_match("/^0/", $this->pn)){
                $this->result_set[0]['np_id'] = substr($this->pn, 0, 4);
                /** replace the leading zero with 234 */
                $this->result_set[0]['phone_number'] = '234'.substr($this->pn, 1,10);
                $this->result_set[0]['isError'] = false;
            }else{
                $this->result_set[0]['isError']  = true;
                $this->result_set[0]['msg'] = "Number should begin with zero";
                $this->result_set[0]['code'] = "9xx";
            }
        }
        /** validate if number contains country code and it is preceded by either 7 0r 8 0r 9*/
        elseif (strlen($this->pn) === 13){

            if (preg_match("/234(8|7|9)/", $this->pn)){
                /** number meet condition, append zero to the beginning */
                $this->result_set[0]['np_id'] = '0'.substr($this->pn, 3, 3);
                $this->result_set[0]['phone_number'] = $this->pn;
                $this->result_set[0]['isError'] = false;
            }else{
                /** error */
                $this->result_set[0]['isError']  = true;
                $this->result_set[0]['msg'] = "Number should begin with zero after country code (234)";
                $this->result_set[0]['code'] = "9xx";
            }
        }

        return $this->result_set;
    }

    private function is_cc_present($pn){}

    private function identify_np($pn){
        $this->pn = $this->check_format($pn);

        if($this->pn[0]['isError'] !== true){
            //no error was found
            foreach ($this->np_id as $code => $np){
                if($this->pn[0]['np_id'] === $code){
                    $this->result_set[0]['code'] = 200;
                    $this->result_set[0]['msg'] = "Number is a valid number...";
                    $this->result_set[0]['phone_number'] = $this->pn[0]['phone_number'];
                    $this->result_set[0]['network_provider'] = $np;
                    $this->result_set[0]['isError']  = false;

                    //get out of the loop, if match was found...
                    break;
                }else{
                    $this->result_set[0]['code'] = 404;
                    $this->result_set[0]['msg'] = "Number is not a valid number...";
                    $this->result_set[0]['phone_number'] = $this->pn[0]['phone_number'];
                    $this->result_set[0]['network_provider'] = null;
                    $this->result_set[0]['isError']  = true;
                }
            }

        }
        else{
           $this->result_set = $this->pn;
        }

        return $this->result_set;
    }

    function is_phone_number($pn){
        return $this->identify_np($pn);
    }

}
 $m = new validate_phone_number();
var_dump ($m->is_phone_number('09196754332'));
