<?php
/**
 * 留言模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class leave_word_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
    }
    
    //添加
    function addNow($appid, $userid, $sortid, $content){
        $data = array(
            "appid" => $appid,
            "userid" => $userid,
            "sortid" => $sortid,
            "content" => $content,
            "add_at" => time()
        );
        
        $leave_wordid = $this->db->add('the_leave_word',$data);   //写入记录
        if( $leave_wordid){
            return array('alert' => '感谢您提供宝贵意见！');    
        }else{
            return "留言失败！请重试！";  
        }
    }
    
    
}