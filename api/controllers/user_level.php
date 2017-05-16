<?php
require_once BASE_PATH.'/modules/user_level_module.php';
/**
 * 用户等级相关控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-20
 */
class user_level {
    
    //获取用户等级列表, 调用网址：[GET] http://localhost/api/index.php?/user_level/list&appid=1
    function list_get(){
        global $cache;
        $appid = (int)request('appid');
        $key = 'user_level_list:'.$appid;

        $cache->get($key);                                  //获取缓存，处理304
        $user_level_module = new user_level_module();
        $data = $user_level_module->getUserLevels($appid); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
}