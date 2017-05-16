<?php
require_once BASE_PATH.'/modules/province_module.php';
require_once BASE_PATH.'/modules/city_module.php';
require_once BASE_PATH.'/modules/district_module.php';
/**
 * 社区模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class community_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`cityid`", 
            "`districtid`",
            "`communityid`",
            "`name`",
            "`address`", 
            "`business`", 
            "`lng`", 
            "`lat`", 
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 10;
    }
    
    //获取列表
    function getList($cityid = 0, $districtid = 0, $page = 1){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        if($cityid){
            array_push($whereArr, "`cityid` = ".$cityid.""); 
        }
        if($districtid){
            array_push($whereArr, "`districtid` = ".$districtid.""); 
        }
        array_push($whereArr, "`check` = 1");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_community` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_community` WHERE ".$where." ORDER BY `ordernum` DESC,`communityid` DESC LIMIT ".$limit;
            $res = $this->db->getX($sql);
            $results = array();
            if(is_array($res) && count($res)){
                $cityid = (int)$res[0]['cityid'];
                $districtid = (int)$res[0]['districtid'];
                
                $city_module = new city_module();
                $city = $city_module->get($cityid);
                
                $district_module = new district_module();
                $district = $district_module->get($districtid);
                foreach($res as $re){
                    $re['city'] = $city;
                    $re['district'] = $district;
                    $re['address'] = str_replace($re['city'].'市','',$re['address']);
                    $re['address'] = str_replace($re['city'],'',$re['address']);
                    $results[] = $re;
                }
            }
            $data["results"] = $results;
        }
        return $data;
        
    }
}