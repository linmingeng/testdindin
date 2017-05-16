<?php
require_once BASE_PATH.'/modules/type_module.php';
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/app_sets_module.php';
require_once BASE_PATH.'/modules/app_key_module.php';
require_once BASE_PATH.'/modules/release_module.php';
require_once BASE_PATH.'/modules/group_module.php';
require_once BASE_PATH.'/modules/reduce_module.php';
/**
 * 应用模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class app_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`appid`",
            "`name`", 
            "`domain`",
            "`logo`", 
            "`district`",
            "`notice`",
            "`free_send_price`", 
            "`send_price`",
            "`delivery_fee`",
            "`payment`", 
            "`invoice`",
            "`is_business`",
            "`sales`", 
            "`country`",
            "`province`",
            "`city`",
            "`cityid`",
            "`district`",
            "`business`",
            "`address`",
            "`zip`",
            "`lng`", 
            "`lat`", 
            "`linkman`",
            "`sex`",
            "`tel`",
            "`default_face`",
            "`loading_image`",
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 20;
        
    }
    
    //[未使用]获取列表
    function getApp($typeid = 0, $page = 1){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        if($typeid){
            array_push($whereArr, "`typeid` = ".$typeid.""); 
        }
        array_push($whereArr, "`check` = 1");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_app` WHERE ".$where." ";    //计算总数
        $countRes = $this->db->getX($countSql);
        if(is_array($countRes)){
            $data["count"] = $countRes[0]["count"];
        }
        $page = min($page,ceil($data["count"]/$this->pageSize));
        if($data["count"]){
            if($data["count"] > $this->pageSize*$page){
                $data["next"] = $page+1;
            }
            if($page > 1){
                $data["previous"] = $page-1;
            }
            $limit = $this->pageSize*($page-1).",".$this->pageSize;
            $sql = "SELECT ".$this->fileds." FROM `the_app` WHERE ".$where." ORDER BY `ordernum` DESC,`appid` DESC LIMIT ".$limit;
            $data["results"] = $this->db->getX($sql);
        }
        return $data;
    }
    
    //获取内容
    function getDetail($appid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_app` WHERE `appid` = '".$appid."' AND `check` = 1 AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //获取应用名称
    function getAppName($appid = 0){
        $sql = "SELECT `name` FROM `the_app` WHERE `appid` = '".$appid."' AND `check` = 1 AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            return $ret[0]['name'];
        }
    }
	
    //获取应用配置信息 TODO: cache
    function getDetailAndConf($domain = '', $appid = 0){
        if(!$appid && !$domain){
            return array();
        }
        $whereStr = '';
        if($appid){
            $whereStr = "`appid` = '".$appid."'";
        }else{
            $whereStr = "`domain` = '".$domain."'";
        }
        $sql = "SELECT ".$this->fileds." FROM `the_app` WHERE  ".$whereStr." AND `check` = 1 AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
            if(count(explode('.', $data['domain'])) == 2){
                $data['domain'] ='www.'.$data['domain'];    
            }
        }else{
            return array();    
        }
        
        $app_conf_module = new app_conf_module;
        $data['conf'] = $app_conf_module->getConf($data['appid']);
        
        $app_sets_module = new app_sets_module;
        $data['conf']['tonggles'] = $app_sets_module->getTonggles($data['appid']);
        
        $reduce_module = new reduce_module();
        $data['activitis'] = $reduce_module->getList($data['appid']);   //获取优惠信息
        
        $group_module = new group_module();
        $data['groups'] = $group_module->getGroups($data['appid']);     //获取商品目录
        
        return $data;
    }
    
    //获取多条应用内容
    function getApps($appids){
        if(is_array($appids)){
            $whereStr = "`appid` IN (".implode(",",$appids).") ";
        }else{
            $whereStr = "`appid` = '".intval($appids)."'";
        }
        $sql = "SELECT ".$this->fileds." FROM `the_app` WHERE ".$whereStr." AND `check` = 1 AND `flag` = 1 ";
        $apps = $this->db->getX($sql);
        if(count($apps)){
            $data = array();
            foreach($apps as $app){
                $data[$app["appid"]] = $app;
            }    
            return $data;
        }
    }
    
    //搜索应用
    function search($keyword){
        $sql = "SELECT ".$this->fileds." FROM `the_app` WHERE `name` LIKE '%".$keyword."%' AND `flag` = 1 ORDER BY `ordernum` DESC,`appid` DESC LIMIT 20";
        return $this->db->getX($sql);
    }
    
    //获取附近的应用列表
    function getLocalApp($cid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_app` WHERE `communityid` = '".$cid."' AND `check` = 1 AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //[portal]添加
    function addNow($userid){
        global $default_app_typeid,$default_app_ver;
        $name = removeSpecialChar(request('name'));
        if( ! $name){
            error(406,'请输入应用名称！');    
        }
        if($this->checkName($name)){
            error(406,'当前名称已被使用，请更换！');    
        }
        $industry = request('industry');
        $import_db = (int)request('import_db');
        $typeid = (int)request('typeid');
        if($typeid){
            $type_module = new type_module;
            $res = $type_module->getDetail($typeid);
            if(is_array($res) && isset($res['ver'])){
                $ver = $res['ver'];
            }
        }else{
            $typeid = $default_app_typeid;
        }
        if(!$ver){
            $ver = $default_app_ver;
        }
        $dt = array(
            "userid" => $userid,
            "name" => $name,
            "industry" => $industry,
            "add_at" => time(),
            "check" => 1,
            "flag" => 1
        );
        $appid = $this->db->add('the_app',$dt);                     //添加应用
        if($appid){
            $app_sets_module = new app_sets_module;
            $adata = array(
                "userid" => $userid,
                "appid" => $appid,
                "typeid" => $typeid,
                "ver" => $ver,
                "name" => $name,
                "update_at" => time(),
                "add_at" => time(),
                "flag" => 1
            );
            $app_setsid = $app_sets_module->addNow($adata);         //添加配置信息
            
            $app_key_module = new app_key_module;
            $kdata = array(
                "userid" => $userid,
                "appid" => $appid,
                "appkey" => md5($userid.$appid),
                "secretkey" => md5($userid.$appid.$typeid.$ver.time()),
                "add_at" => time(),
            );
            $app_keyid = $app_key_module->addNow($kdata);           //添加应用密钥
            
            $release_module = new release_module;
            $data = array(
                "userid" => $userid,
                "appid" => $appid,
                "typeid" => $typeid,
                "ver" => $ver,
                "process" => 0,
                "size" => 0,
                "status" => 0,
                "add_at" => time(),
                "flag" => 1
            );
            $modals = array('h5','android');    //todo: 支持ios
            foreach($modals as $modal){
                $data['modal'] = $modal;
                if($import_db && $modal == 'h5'){
                    $data['import_db'] = 1;
                }else{
                    $data['import_db'] = 0;
                }
                $releaseid = $release_module->addNow($data);        //添加打包发布任务
            }
            return array("alert" => "恭喜，应用创建成功！<br>正在生成APP，耗时大约3分钟！","appid" => $appid);    
        }else{
            return "创建创建失败！请重试！";  
        }
    }
    
    //[portal]获取热门应用(应用展示)
    function getHotApp(){
        $sql = "SELECT `appid`,`name`,`domain`,`logo`,`industry`,`add_at` FROM `the_app` WHERE `best` = 1 AND `check` = 1  AND `flag` = 1 ORDER BY `ordernum` DESC,`appid` DESC LIMIT 10";
        return $this->db->getX($sql);
    }
    
    //[portal]获取我的应用列表
    function getMyApps(){
        global $user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $data = array('results' => array());
        $sql = "SELECT `appid`,`name`,`domain`,`logo`,`industry`,`check`,`add_at` FROM `the_app` WHERE `userid` = '".$user_info['userid']."' AND `flag` = 1 ORDER BY `appid` DESC";
        $data['results'] = $this->db->getX($sql);
        return $data;
    }
    
    //[portal]获取配置信息
    function getInfo($appid){
        global $user_info;
        $userid = $user_info['userid'];
        if(!$userid){
            return ;
        }
        $data = array();
        $sql = "SELECT `appid`,`name`,`domain`,`description`,`logo`,`default_face`,`loading_image`,`icon`,`splash`,`industry`,`country`,`province`,`city`,`district`,`zip`,`address`,`company`,`linkman`,`sex`,`tel` FROM `the_app` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //[portal]修改资料
    function updateInfo($appid, $setSqlArr){
        global $user_info;
        if(!$user_info['userid'] || !$appid || !is_array($setSqlArr) || !count($setSqlArr)){
            return 0;    
        }
        $sql = "UPDATE `the_app` SET ".implode(',',$setSqlArr)." WHERE `appid` = '".$appid."' AND `userid` = '".$user_info['userid']."' ";
        return $this->db->updateX($sql);
    }
    
    //[portal]验证域名是否被占用
    function checkDomain($domain, $appid){
        $limitDomains = array(
            'baidu.com',
            'sina.com',
            'sohu.com',
            'qq.com',
            'taobao.com',
            'dindin.com',
            'metapplication.com',
            'dindin.com',
        );
        $domain = strtolower($domain);
        if(preg_match('/'.implode('|',$limitDomains).'/i',$domain)){    //受限的域名
            return 1;
        }
        if($appid){
            $ret = $this->db->getX("SELECT 1 FROM `the_app` WHERE `domain` = '".$domain."' AND `appid` != ".$appid." " );
        }else{
            $ret = $this->db->getX("SELECT 1 FROM `the_app` WHERE `domain` = '".$domain."' " );
        }
        return count($ret);
    }
    
    //[portal]验证名称是否被占用
    function checkName($name, $appid){
        if($appid){
            $ret = $this->db->getX("SELECT 1 FROM `the_app` WHERE `name` = '".$name."' AND `appid` != ".$appid." " );
        }else{
            $ret = $this->db->getX("SELECT 1 FROM `the_app` WHERE `name` = '".$name."' " );
        }
        return count($ret);
    }
    
    //[portal]获取用户已创建的APP数量
    function countApps($userid){
        $ret = $this->db->getX("SELECT COUNT(*) AS `num` FROM `the_app` WHERE `userid` = '".$userid."' AND `flag` = 1 " );
        if(count($ret)){
            return $ret[0];
        }
    }
}