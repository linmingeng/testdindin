<?php

class newGoToPay {

    function __construct() {
        //var_dump($this->ad_module);
    }

    function get(){
        $this->newGoToPay_view();
        return array(1,2,3);
    }
    function buyAction_view(){
        location('index.php?/newGoToPay/view');
    }
    
    function view_get(){

        return array(1,2,3);
    }
}