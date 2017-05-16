<?php

class order_history {

    function __construct() {
        //var_dump($this->ad_module);
    }

    function get(){
        $this->order_history_view();
        return array(1,2,3);
    }
    function order_history_view(){
        location('index.php?/order_history/view');
    }
    
    function view_get(){

        return array(1,2,3);
    }
}