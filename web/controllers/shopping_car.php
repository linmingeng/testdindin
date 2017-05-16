<?php

class shopping_car {

    function __construct() {
        //var_dump($this->ad_module);
    }

    function get(){
        $this->shopping_car_view();
        return array(1,2,3);
    }
    function shopping_car_view(){
        location('index.php?/shopping_car/view');
    }
    
    function view_get(){

        return array(1,2,3);
    }
}