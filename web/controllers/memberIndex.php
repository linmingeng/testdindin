<?php
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/libraries/checkChar.php';
class memberIndex {

    function __construct() {
        $this->user_module = new user_module();
    }

    function get(){
        $this->newGoToPay_view();
        return array(1,2,3);
    }
    function memberIndex_view(){
        location('index.php?/memberIndex/view');
    }
    
    function view_get(){
        global $user_info;
        return $this->user_module->getUsers($user_info["userid"]);
        
    }
}