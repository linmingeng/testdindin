<?php
require_once BASE_PATH.'/modules/report_module.php';
require_once BASE_PATH.'/modules/order_module.php';
require_once BASE_PATH.'/modules/visit_module.php';
/**
 * 运营报表控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class report {
    
    function __construct(){
        $this->report_module = new report_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/report/list&page=1&appid=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'report:'.$appid.':'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->report_module->getReport($appid, $user_info['userid'], $page); 
        
        $order_module = new order_module;
        $traderOrderNum = $order_module->getTraderOrderNum($appid, $user_info['userid'], 0);   //今日订单 今日销售
        
        $visit_module = new visit_module;
        $uv = $visit_module->getUv($appid, $user_info['userid'], 0);                         //今日访问
        if(!is_array($data['results'])){
            $data['results'] = array();
        }
        array_unshift($data['results'], array(
            "reportid" => "0",
            "uv" => $uv,
            "orders" => $traderOrderNum['orders'],
            "money" => $traderOrderNum['money'],
            "add_at" => time()
        ));
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
}