<?php
/**
 * @author Ukor Jidechi E. << http://www.ukorjidechi.com || ukorjidechi@gmail.com >>
 * Date: 12/4/16
 * Time: 2:27 AM
 */

namespace ukorJidechi;

class validate_phone_number {

    /** The phone number */
    private $pn;

    const version = "0.3";


    private $phone_number_length;


    /** Holds the list of all network provider */
    private $network_provider = array(
        'Aitel_NG', 'MTN_NG', 'Etisalat_NG', 'GLO_NG', 'VISAFONE', 'MULTILINKS', 'STARCOMMS', 'ZOOM MOBILE', 'MTEL'
    );



    /** Network provider id as key and the network provider name as value e.g. '0802' => 'AIRTEL_NG' */
    private $np_id = array  (
        '0803' => 'MTN_NG', '0806' => 'MTN_NG', '0703' => 'MTN_NG', '0706' => 'MTN_NG', '0906' => 'MTN_NG',
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



    /**
     * validate_phone_number constructor.
     * @param $phone_number
     */
    function __construct($phone_number) {
        return $this->is_phone_number($phone_number);
    }



    /**
     * strips all special and numeric character from the phone number
     * @param $pn
     * @return mixed
     */
    private function remove_char($pn){
        /** removes white space and non numeric characters from the phone number */
        return preg_replace("/[\D\s]/", '', $pn);
    }


    /**
     * @param $pn : phone number to validate
     * @return array : The length and clean phone number
     */
    private function get_length($pn){
        //remove special chars and alphabets from phone numbers
        $this->pn = $this->remove_char($pn);

        $this->result_set[0]['phone_number_length'] = strlen($this->pn);
        $this->result_set[0]['clean_pn'] = $this->pn;

        return $this->result_set;
    }


    
    /**
     * @param array $pn_and_length
     * @return array
     */
    private function check_format(array $pn_and_length){
        $this->pn = $pn_and_length[0]['clean_pn'];
        $this->phone_number_length = $pn_and_length[0]['phone_number_length'];

        switch ($this->phone_number_length){
            case 11:

                /** check if the number begins with 0 */
                if (preg_match("/^0/", $this->pn)){
                    $this->result_set[0]['np_id'] = substr($this->pn, 0, 4);
                    /** replace the leading zero with 234 */
                    $this->result_set[0]['phone_number'] = '234'.substr($this->pn, 1,10);
                    $this->result_set[0]['isError'] = false;
                }else{
                    $this->result_set[0]['isError']  = true;
                    #todo:: remove redundant error message and code
                    $this->result_set[0]['msg'] = "Number should begin with zero";
                    $this->result_set[0]['code'] = "9xx";
                }

                break;
            case 13:
                /** Check if phone number begin with Nigeria country code and is preceded by either 7,8 or 9 */
                if (preg_match("/234(8|7|9)/", $this->pn)){
                    /**
                     * extract network provider id (np_id) then append 0 to it
                     * (0802 = network provider id (np_id).
                     */
                    $this->result_set[0]['np_id'] = '0'.substr($this->pn, 3, 3);
                    $this->result_set[0]['phone_number'] = $this->pn;
                    $this->result_set[0]['isError'] = false;
                }else{
                    /** error */
                    $this->result_set[0]['isError']  = true;
                    #todo:: remove redundant error message and code
                    $this->result_set[0]['msg'] = "Number should begin with zero after country code (234)";
                    $this->result_set[0]['code'] = "9xx";
                }

                break;
            case 14:

                /** verify number contains country code (cc) */
                if(preg_match("/234[0-9]{11}/", $this->pn, $matches)){

                    if(preg_match("/234[0]/", $matches[0])){
                        /**
                         * extract network provider id (np_id) then append 0 to it
                         * (0802 = network provider id (np_id).
                         */
                        $this->result_set[0]['np_id'] = substr($this->pn, 3, 4);
                        $this->result_set[0]['phone_number'] = preg_replace("/^2340/", '234', $matches[0]) ;
                        $this->result_set[0]['isError'] = false;
                    }else{
                        $this->result_set[0]['isError']  = true;
                        #todo:: remove redundant error message and code
                        $this->result_set[0]['msg'] = "Number should begin with zero after country code (234)";
                        $this->result_set[0]['code'] = "9xx";
                    }
                }else{
                    $this->result_set[0]['isError']  = true;
                    #todo:: remove redundant error message and code
                    $this->result_set[0]['msg'] = "Phone number is invalid";
                    $this->result_set[0]['code'] = "9xx";
                }

                break;

            default:
                $this->result_set[0]['msg'] = "Phone number is not a valid number";
                break;

        }

        return $this->result_set;
    }


    /**
     * @param $pn
     * @return array
     */
    private function identify_np($pn){
        $pn = $this->get_length($pn);
        $this->pn = $this->check_format($pn);

        if($this->pn[0]['isError'] !== true){
            //no error was found
            foreach ($this->np_id as $code => $np){
                if($this->pn[0]['np_id'] === $code){
                    $this->result_set[0]['code'] = 200;
                    $this->result_set[0]['msg'] = "Number is a valid number...";
                    $this->result_set[0]['phone_number'] = $this->pn[0]['phone_number'];
                    $this->result_set[0]['network_provider'] = $np;
                    $this->result_set[0]['isError']  = False;

                    //get out of the loop, if match was found...
                    break;
                }else{
                    $this->result_set[0]['code'] = 404;
                    $this->result_set[0]['msg'] = "Network provider not found...";
                    $this->result_set[0]['phone_number'] = $pn[0]['clean_pn'];
                    $this->result_set[0]['network_provider'] = Null;
                    $this->result_set[0]['isError']  = True;
                }
            }

        }
        else{
           $this->result_set = $this->pn;
        }

        return $this->result_set;
    }


    /**
     * @param $pn
     * @return array
     */
    public function is_phone_number($pn){
        return $this->identify_np($pn);
    }

}

