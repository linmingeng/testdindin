<?php
require_once BASE_PATH.'/modules/address_module.php';
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/modules/sms_module.php';
require_once BASE_PATH.'/libraries/checkChar.php';
class receiveAddress {

    function __construct() {
        global $user_info;
        $this->address_module = new address_module();
    }

    function get(){
        global $user_info;
        if ($user_info["userid"]=="") {
            location('index.php?/user/login');
        }
        $this->receiveAddress_view();
        return array(1,2,3);
    }
    function receiveAddress_view(){
        location('index.php?/receiveAddress/view');
    }
    
    function view_get(){
       global $user_info;
       if ($user_info["userid"]=="") {
            location('index.php?/user/login');
        } 
       
       return $this->address_module->getAddress(1,$user_info["userid"]);
        
    }

    function del_get(){
        global $user_info;
        $addid=(int)request('addressid');
        $this->address_module->delNow(1,$user_info["userid"],$addid);
        location('index.php?/receiveAddress/view'); 
    }


}