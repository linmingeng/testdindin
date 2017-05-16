<?php
require_once BASE_PATH.'/libraries/GeoLocation.php';
/**
 * 连锁店模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class sub_shop_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`sub_shopid`",
            "`name`", 
            "`notice`",
            "`lng`", 
            "`lat`", 
            "`address`",
            "`tel`",
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 20;
        
    }
    
    //获取列表
    function getSubShop($appid = 0, $page = 1){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        if($appid){
            array_push($whereArr, "`appid` = ".$appid.""); 
        }
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_sub_shop` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_sub_shop` WHERE ".$where." ORDER BY `sub_shopid` DESC LIMIT ".$limit;
            $data["results"] = $this->db->getX($sql);
        }
        return $data;
    }
    
    //获取内容
    function getDetail($sub_shopid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_sub_shop` WHERE `sub_shopid` = '".$sub_shopid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //获取附近连锁店
    function getNearSubShops($appid, $lat, $lng){
        $GeoLocation = new GeoLocation;
        $point = $GeoLocation->fromDegrees($lat, $lng);
        $coordinates = $point->boundingCoordinates(0.1, 'km');    //30米以内
        
        $min_lat =  $coordinates[0]->getLatitudeInDegrees();
        $min_lng =  $coordinates[0]->getLongitudeInDegrees();
        
        $max_lat =  $coordinates[1]->getLatitudeInDegrees();
        $max_lng =  $coordinates[1]->getLongitudeInDegrees();
        
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid.""); 
        array_push($whereArr, "`lng` >= ".$min_lng.""); 
        array_push($whereArr, "`lng` <= ".$max_lng.""); 
        array_push($whereArr, "`lat` >= ".$min_lat.""); 
        array_push($whereArr, "`lat` <= ".$max_lat.""); 
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        
        $sql = "SELECT ".$this->fileds." FROM `the_sub_shop` WHERE ".$where." ";
        $results = $this->db->getX($sql);
        if(count($results)){
            return $results[0];    
        }
    }
}