<?php
require_once BASE_PATH.'/modules/visit_module.php';
require_once BASE_PATH.'/modules/app_module.php';
/**
 * 访问者控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class visit {
    
    function __construct(){
        $this->visit_module = new visit_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/visit/add&reqid=123232
    function add_get(){
        return $this->add();    
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/visit/add
    function add_post(){
        return $this->add();    
    }
    
    function init_post(){
        global $cache,$controller,$method;
        
        $this->visit_module->addNow($this->getData()); 
        
        $domain = request('domain');
        $domain = strtolower($domain);
        $domain = str_replace('www.','',$domain);
        $domain = str_replace('test.','',$domain);
        //获取appid
        $appid = getAppid($domain);
    
        $key = $controller.':'.$method.':'.$domain;
        $cache->get($key);                                          //获取缓存，处理304
        
        $app_module = new app_module;
        $result = $app_module->getDetailAndConf($domain, $appid);   //获取应用详情及配置信息
        if(empty($result)){
            return ;
        }
        $cache->set($key,$result);                                  //设置缓存，增加额外的header
        return $result;
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/visit/update
    function update_post(){
        return $this->update();    
    }
    
    function update(){
        if(!isset($_REQUEST['reqid'])){
            return ;    
        }
        return $this->visit_module->updateNow($this->getData()); 
    } 
    
    function add(){
        if(!isset($_REQUEST['reqid'])){
            return ;    
        }
        return $this->visit_module->addNow($this->getData()); 
    } 
    
    function getData(){
        $fields = array(
            'reqid',
            'userid',
            'phone',
            'sex',
            'cityid',
            'city',
            'districtid',
            'district',
            'communityid',
            'name',
            'address',
            'business',
            'lng',
            'lat',
            'tag',
            'domain',
            'version',
            'modal',
            'newbie',
            'device',
            'network',
            'platform',
            'user_agent',
            'cookie_enabled',
            'inviter_uid',
            'appid',
            'refer',
            'url',
            'width',
            'height',
            'charset',
            'in_time',
            'show_time',
            'seconds',
            'add_at',
            'flag',
        );
        $data = array(
            'ip' => getIp()
        );
        foreach($fields as $k){
            if(isset($_REQUEST[$k])){
                $data[$k] = $_REQUEST[$k];
            }
        }
        return $data; 
    } 
    
}