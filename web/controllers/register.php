<?php

class register {

    function __construct() {
        //var_dump($this->ad_module);
    }

    function get(){
        $this->register_view();
        return array(1,2,3);
    }
    function register_view(){
        location('index.php?/register/view');
    }
    
    function view_get(){

        return array(1,2,3);
    }
}