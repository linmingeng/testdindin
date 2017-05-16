<?php
require_once BASE_PATH.'/modules/income_module.php';
/**
 * 收支记录控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class income {
    
    function __construct(){
        $this->income_module = new income_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/income/list&page=1&appid=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'income:'.$appid.':'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->income_module->getIncome($appid, $user_info['userid'], $page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
}