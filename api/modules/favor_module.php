<?php
require_once BASE_PATH.'/modules/app_module.php';
/**
 * 应用收藏模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class favor_module {
    
    function __construct() {
        global $db;
        $this->db = $db;
       
        $this->pageSize = 10;
    }
    
    //获取列表
    function getFavor($page = 1){
        global $user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');    
        }
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`userid` = ".$user_info['userid']."");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_favor` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT `favorid`,`appid` FROM `the_favor` WHERE ".$where." ORDER BY `favorid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            if(count($results)){
                $appids = array();
                foreach($results as $favor){
                    array_push($appids, $favor["appid"]);
                }
                $appids = array_unique($appids);
                $app_module = new app_module();
                $apps = $app_module->getApps($appids);
                
                foreach($results as $key => $val){
                    $results[$key]["app"] = $apps[$val["appid"]];
                }
            }
            $data["results"] = $results;
        }
        return $data;
    }
    
    //收藏
    function addNow($appid){
        global $user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');    
        }
        
        if( ! $appid){
            return "收藏失败！请重试！"; 
        }
        
        $data = array(
            "appid" => $appid,
            "userid" => $user_info['userid'],
            "add_at" => time()
        );
        
        $favorid = $this->db->add('the_favor',$data);   //写入记录
        if( $favorid){
            return array("msg" => "收藏成功！", "favorid" => $favorid );    
        }else{
            return "收藏失败！请重试！";  
        }
    }
    
    //取消收藏
    function delNow($favorid){
        global $user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');    
        }
        
        if( ! $favorid){
            return "取消收藏失败！请重试！"; 
        }
        $stauts = $this->db->delX("DELETE FROM `the_favor` WHERE `favorid` = ".$favorid." AND `userid` = ".$user_info['userid']." ");
        if( $stauts){
            return array("msg" => "取消收藏成功！", "favorid" => 0 );    
        }else{
            return "取消收藏失败！请重试！";  
        }
    }
    
    //获取收藏状态
    function getFavorStatus(){
        global $user_info;
        $data = array('results' => array());
        if(!$user_info['userid']){
            return $data;    
        }
        
        $sql = "SELECT `favorid`,`appid` FROM `the_favor` WHERE `userid` = ".$user_info['userid']."  AND flag = 1 ";
        $res = $this->db->getX($sql);
        if(count($res)){
            foreach($res as $re){
                $data['results'][$re["appid"]] = $re["favorid"];
            }
        }
        return $data;
    }
    
}