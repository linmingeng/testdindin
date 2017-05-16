<?php
/**
 * 应用打包发布记录模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-07-07
 */
class release_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`releaseid`",
            "`appid`", 
            "`userid`",
            "`typeid`",
            "`ver`",
            "`modal`",
            "`step`",
            "`process`",
            "`size`", 
            "`status`", 
            "`step`", 
            "`seconds`", 
            "`ready_at`", 
            "`end_at`", 
            "`update_at`", 
            "`add_at`", 
        );
        
       $this->fileds = implode(",",$filedsArr);
        
       $this->pageSize = 20;
    }

    //[portal]获取列表
    function getRelease($appid, $userid, $page = 1){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`userid` = ".$userid."");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_release` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_release` WHERE ".$where." ORDER BY `releaseid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //[portal]添加
    function addNow($data){
        return $this->db->add('the_release',$data);
    }
    
    //[portal]获取详情
    function getDetail($releaseid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_release` WHERE `releaseid` = '".$releaseid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //[portal]获取打包发布详情
    function getReleaseStatus($appid, $userid, $modal){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_release` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `modal` = '".$modal."' AND `flag` = 1 ORDER BY `releaseid` DESC ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
}