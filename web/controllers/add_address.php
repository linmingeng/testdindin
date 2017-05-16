<?php
require_once BASE_PATH.'/modules/address_module.php';
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/modules/app_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/sms_module.php';
require_once BASE_PATH.'/libraries/checkChar.php';

class add_address {

    function __construct() {
        global $user_info;
        $this->address_module = new address_module();
    }

    function get(){
        global $user_info;
        if ($user_info["userid"]=="") {
            location('index.php?/user/login');
        }
       $this->add_address();
       return array(1,2,3);
    }
    function add_address(){
        location('index.php?/add_address/view');
    }
    
    function view_get(){
        global $user_info;
        if ($user_info["userid"]=="") {
            location('index.php?/user/login');
        }
        $addid=(int)request('addressid');
        return $this->address_module->getDetail($addid);

    }

    function view_post(){
        $userid =  $this->address_module->save();   //写入记录

        if($userid){
            return array('code'=>200);
        }else{
            error(406,"添加失败！请重试！");
        }
           
    }




}