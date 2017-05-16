<?php
require_once BASE_PATH.'/modules/leave_word_module.php';
/**
 * 留言控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-23
 */
class leave_word {
    
    function __construct(){
        $this->leave_word_module = new leave_word_module();
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/leave_word/add
    function add_post(){
        global $user_info;
        $userid = (int)$user_info['userid'];
        $appid = (int)request('appid');
        $sortid = (int)request('sortid');
        $content = request('content');
        if( ! $content){
            error(406,'请填写投诉意见！');
        }
        return $this->leave_word_module->addNow($appid, $userid, $sortid, $content); 
    } 
    
}