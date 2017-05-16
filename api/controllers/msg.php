<?php
require_once BASE_PATH.'/modules/msg_module.php';
/**
 * 站内短信控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class msg {
    
    function __construct(){
        $this->msg_module = new msg_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/msg/list&sid=1&page=1
    function list_get(){
        global $cache,$controller,$method,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
         
        $res = $this->msg_module->getMsg($appid, $user_info['userid'], $page); 
        return $res;
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/msg/detail/msgid/3
    function detail_get(){
        global $cache,$controller,$method,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $msgid = (int)request('msgid');
        
        $res = $this->msg_module->getMsgDetail($user_info['userid'], $msgid);  
        return $res;
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/msg/unread
    function unread_get(){
        global $cache,$controller,$method,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        
        $nums = $this->msg_module->getUnreadNums($appid, $user_info['userid']);  
        return array('nums' => $nums);
    } 
    
}