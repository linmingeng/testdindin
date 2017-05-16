<?php
require_once BASE_PATH.'/modules/app_module.php';
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/app_sets_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/type_module.php';
require_once BASE_PATH.'/modules/goods_module.php';
require_once BASE_PATH.'/modules/group_module.php';
require_once BASE_PATH.'/modules/reduce_module.php';
require_once BASE_PATH.'/modules/special_module.php';
require_once BASE_PATH.'/modules/news_module.php';
require_once BASE_PATH.'/modules/page_module.php';

/**
 * 应用控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class app {
    
    function __construct(){
        $this->app_module = new app_module();
    }
    
    //[未使用]调用网址：[GET] http://localhost/api/index.php?/app/list/tid/3&page=1
    function list_get(){
        global $cache,$controller,$method;
        
        $tid = (int)request('tid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = $controller.':'.$method.':'.$tid.':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->app_module->getApp($tid,$page); 
        
        $data["types"] = array(
            array("typeid" => 0, "name" => "全部")
        );
        
        $type_module = new type_module();
        $allType = $type_module->getAllType();
        if(is_array($allType)){
            $data["types"] = array_merge($data["types"],$allType);
        }
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/app/detail/aid/3
    function detail_get(){
        global $cache,$controller,$method;
        
        $aid = (int)request('aid');
        $key = $controller.':'.$method.':'.$aid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $result = $this->app_module->getDetail($aid);
        if(empty($result)){
            return ;
        }
        $reduce_module = new reduce_module();
        $result['activitis'] = $reduce_module->getList($aid);   //获取优惠信息
        
        $group_module = new group_module();
        $result['groups'] = $group_module->getGroups($aid);     //获取商品目录
        
        $cache->set($key,$result);                              //设置缓存，增加额外的header
        return $result;
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/app/home/aid/3
    function home_get(){
        global $cache,$controller,$method;
        
        $aid = (int)request('aid');
        $key = $controller.':'.$method.':'.$aid;
        $cache->get($key);                                      //获取缓存，处理304
        
        $result = array();
        
        $app_conf_module = new app_conf_module;
        $conf = $app_conf_module->getConf($aid, 'home');
        if(!is_array($conf['home']) || !is_array($conf['home']['goods']) ){
            return $result;
        }
        
        $goods_module = new goods_module();
        foreach($conf['home']['goods'] as $k => $v){
            $v['items'] = $goods_module->getSomeGoods($aid,$v['groupid'],$v['sub_groupid'],$v['num']);
            $result['sort_goods'][$k] = $v;
        }
        
        $cache->set($key,$result);                              //设置缓存，增加额外的header
        return $result;
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/app/conf/aid/3
    function conf_get(){
        global $cache,$controller,$method;
        
        $aid = (int)request('aid');
        $key = $controller.':'.$method.':'.$aid;
        $cache->get($key);                                  //获取缓存，处理304
        $app_conf_module = new app_conf_module;
        $result = $app_conf_module->getConf($aid);
        if(empty($result)){
            return ;
        }
        return $result;
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/app/groups/aid/3
    function groups_get(){
        global $cache,$controller,$method;
        
        $aid = (int)request('aid');
        $key = $controller.':'.$method.':'.$aid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $group_module = new group_module();
        $result = $group_module->getGroups($aid);
        if(empty($result)){
            return ;
        }
        $cache->set($key,$result);                          //设置缓存，增加额外的header
        return $result;
    }
        
    //调用网址：[GET] http://localhost/api/index.php?/app/goods/aid/3
    function goods_get(){
        global $cache,$controller,$method;
        
        $aid = (int)request('aid');
        $gid = (int)request('gid');
        $sub_gid = (int)request('sub_gid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = $controller.':'.$method.':'.$aid.':'.$gid.':'.$sub_gid.':'.$page;
        $cache->get($key);                                      //获取缓存，处理304
        
        $goods_module = new goods_module();
        $result = $goods_module->getGoods($aid,$gid,$sub_gid,$page);
        if(empty($result)){
            return ;    
        }
        $cache->set($key,$result);                             //设置缓存，增加额外的header
        return $result;
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/app/special/specialid/3
    function special_get(){
        global $cache,$controller,$method;
        
        $specialid = (int)request('specialid');
        $key = $controller.':'.$method.':'.$specialid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $special_module = new special_module();
        $special = $special_module->get($specialid);
        if(!is_array($special) ){
            return ;
        }
        
        $items = array();
        if(isset($special['ids'])){
            $goods_module = new goods_module();
            $items = $goods_module->getSaleGoods(explode(',', $special['ids']));
        }
        $special['items'] = $items;
        $cache->set($key,$special);                          //设置缓存，增加额外的header
        return $result;
    }
       
    //调用网址：[GET] http://localhost/api/index.php?/app/local/cid/3
    function local_get(){
        global $cache,$controller,$method;
        
        $cid = (int)request('cid');
        $key = $controller.':'.$method.':'.$cid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $result = $this->app_module->getLocalApp($cid);
        if(empty($result)){
            return ;
        }
        $cache->set($key,$result);                          //设置缓存，增加额外的header
        return $result;
    }
       
    //调用网址：[GET] http://localhost/api/index.php?/app/hot
    function hot_get(){
        global $cache,$controller,$method;
        
        $key = $controller.':'.$method.':hotApp';
        $cache->get($key);                                  //获取缓存，处理304
        
        $apps = $this->app_module->getHotApp();
        $res = array("apps" => $apps);
        $cache->set($key,$res);                             //设置缓存，增加额外的header
        return $res;
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/app/search
    function search_post(){
        $keyword = trim(request('keyword'));
        if( ! $keyword){
            error(406,'请输入搜索关键字！');    
        }
        
        $apps = $this->app_module->search($keyword);
        
        $goods_module = new goods_module();
        $goods = $goods_module->search($keyword);
        return array("apps" => $apps,"goods" => $goods);
    }
    
    //[portal]调用网址：[POST] http://localhost/api/index.php?/app/add
    function add_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $appid = (int)request('appid');
        $key = 'myapp:'.$user_info['userid'];
        $cache->del($key);                                  //清除缓存
        //判断是否可以创建新APP
        $app_user_module = new app_user_module;
        $levelData = $app_user_module->getAppUserLevel($appid, $user_info['userid']);
        $user_level = 1;
        if(is_array($levelData) && isset($levelData['level'])){
            $user_level = $levelData['level'];
        }
        if($user_level < 1){
            $user_level = 1;    
        }
        $countData = $this->app_module->countApps($user_info['userid']);
        $num = 0;
        if(is_array($countData) && isset($countData['num'])){
            $num = $countData['num'];
        }
        if($num+1 > $user_level){
            return array('alert' => '对不起，您最多只能创建'.$user_level.'个应用！');
        }
        return $this->app_module->addNow($user_info['userid']); 
    } 
    
    //[portal]调用网址：[GET] http://localhost/api/index.php?/app/myapps
    function myapps_get(){
        global $cache,$controller,$method,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $key = 'myapp:'.$user_info['userid'];
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->app_module->getMyApps(); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //[portal]调用网址：[GET] http://localhost/api/index.php?/app/info/appid/3
    function info_get(){
        global $cache,$controller,$method;
        
        $appid = (int)request('appid');
        $key = 'app_info:'.$appid;
        $cache->get($key);                                   //获取缓存，处理304
        
        $result = $this->app_module->getInfo($appid);
        if(empty($result)){
            return ;
        }
        $cache->set($key,$result);                           //设置缓存，增加额外的header
        return $result;
    }
    
    //[portal]调用网址：[POST] http://localhost/api/index.php?/app/info
    function info_post(){
        global $cache,$controller,$method,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $appid = (int)request('appid');
        
        $domain = request('domain');
        if($domain){
            if($this->app_module->checkDomain($domain, $appid)){
                return '当前域名已被使用，请更换！';
            }
            $app_sets_module = new app_sets_module();
            $setSqlArr = array("`domain`='".$domain."'");
            $data = array('domain' => $domain);
            $result = $app_sets_module->updateAppSets($appid, $setSqlArr, $data);           //设置独立域名
        }
        
        $name = removeSpecialChar(request('name'));
        if($name){
            if($this->app_module->checkName($name, $appid)){
                return '当前名称已被使用，请更换！';
            }
            $app_sets_module = new app_sets_module();
            $setSqlArr = array("`name`='".$name."'");
            $data = array('name' => $name);
            $result = $app_sets_module->updateAppSets($appid, $setSqlArr, $data, $name);   //设置应用名称
        }
        $fileds = array('name','domain','industry','description','logo','default_face','loading_image','icon','splash','company','country','province','city','district','zip','address','linkman','sex','tel');
        $setSqlArr = array();
        foreach($fileds as $filed){
            if(isset($_REQUEST[$filed])){
                if($filed == 'name'){
                    $setSqlArr[] = "`".$filed."`='".removeSpecialChar(request($filed))."'";
                }else{
                    $setSqlArr[] = "`".$filed."`='".request($filed)."'";
                }
            }
        }
        $result = $this->app_module->updateInfo($appid, $setSqlArr);
        if($result){
            if($name || $domain){
                $key = 'app_sets:'.$appid;
                $cache->del($key); 
            }
            
            $key = 'app_info:'.$appid;
            $cache->del($key);
            
            $key = 'myapp:'.$user_info['userid'];
            $cache->del($key);
            
            return '设置成功！';
        }
    }
}