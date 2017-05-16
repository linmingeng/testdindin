<?php
require_once BASE_PATH.'/modules/footprint_module.php';
/**
 * 脚印控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class footprint {
    
    function __construct(){
        $this->footprint_module = new footprint_module();
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/footprint/add
    function add_post(){
        $fields = array(
            'appid',
            'reqid',
            'userid',
            'data',
        );
        $data = array();
        foreach($fields as $k){
            if(isset($_REQUEST[$k])){
                $data[$k] = $_REQUEST[$k];
            }
        }
        return $this->footprint_module->addNow($data); 
    } 
    
}