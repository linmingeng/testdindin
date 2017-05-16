<?php
require_once BASE_PATH.'/modules/score_log_module.php';
/**
 * 积分日志相关控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-20
 */
class score_log {
    
    //获取积分日志列表, 调用网址：[GET] http://localhost/api/index.php?/score_log/list&appid=1&userid=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $appid = (int)request('appid');
        $type = '';
        if(isset($_REQUEST['type'])){
            $type = (int)request('type');
        }
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'score_log:list:'.$appid.':'.$user_info['userid'].':'.$page.':'.$type;
        $cache->get($key);                                   //获取缓存，处理304
        
        $score_log_module = new score_log_module();
        $data = $score_log_module->getScoreLogs($appid, $user_info['userid'], $page, $type); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
}