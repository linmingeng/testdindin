<?php
/**
 * 脚印模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-11-24
 */
class footprint_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
        $this->pageSize = 10;
    }
    
    //添加
    function addNow($data){
        if(!is_array($data)){
            return ;
        }
        $data['add_at'] = time();
        $data['flag'] = 1;
        $footprintid = $this->db->add('the_footprint',$data);   //写入记录
        return array("footprintid" => $footprintid );
    }
    
}