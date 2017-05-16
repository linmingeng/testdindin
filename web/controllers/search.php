<?php

class search {

    function __construct() {
        //var_dump($this->ad_module);
    }

    function get(){
        $this->search_view();
        return array(1,2,3);
    }
    function search_view(){
        location('index.php?/search/view');
    }
    
    function view_get(){

        return array(1,2,3);
    }
}