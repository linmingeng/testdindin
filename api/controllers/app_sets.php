<?php
require_once BASE_PATH.'/modules/app_sets_module.php';

/**
 * 应用控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class app_sets {
    
    function __construct(){
        $this->app_sets_module = new app_sets_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/app/app_sets/appid/3
    function sets_get(){
        global $cache,$controller,$method;
        
        $appid = (int)request('appid');
        $key = 'app_sets:'.$appid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $result = $this->app_sets_module->getAppSets($appid);
        if(empty($result)){
            return ;
        }
        $cache->set($key,$result);                           //设置缓存，增加额外的header
        return $result;
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/app_sets/sets
    function sets_post(){
        global $cache,$controller,$method,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $appid = (int)request('appid');
        
        $fileds = array(
            "typeid",
            "ver",
            "typeid",
            "public_id",
            "public_name",
            "wechat",
            "wx_login",
            "wx_pay",
            "wx_share",
            "wx_appid",
            "wx_appsecret",
            "mchid",
            "mchkey",
            "sslcert",
            "sslkey",
            "mobile_login",
            "mobile_pay",
            "mobile_share",
            "mobile_appid",
            "mobile_appsecret",
            "mobile_mchid",
            "mobile_mchkey",
            "mobile_sslcert",
            "mobile_sslkey",
            "web_login",
            "web_appid",
            "web_appsecret",
            "jsapi_token",
            "jsapi_ticket",
            "ios_url",
            "android_url",
            "down_header",
            "down_logo",
            "down_footer",
            "sms_sign",
            "sms_tail",
            "jpush",
            "jpush_appkey",
            "jpush_secret",
            "apns_dev",
            "apns_dev_password",
            "apns_pro",
            "apns_pro_password",
        );
        $data = array();
        $setSqlArr = array();
        foreach($fileds as $filed){
            if(isset($_REQUEST[$filed])){
                $setSqlArr[] = "`".$filed."`='".request($filed)."'";
                $data[$filed] = request($filed);
            }
        }
        $result = $this->app_sets_module->updateAppSets($appid, $setSqlArr, $data);
        if($result){
            $key = 'app_sets:'.$appid;
            $cache->del($key);
            $key = 'app_info:'.$appid;
            $cache->del($key);
            if(!isset($_REQUEST['silence'])){
                return '设置成功！';
            }
        }
    }
}