<?php
/**
 * 站内短信模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class msg_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`msgid`",
            "`userid`",
            "`title`",
            "`content`",
            "`from_userid`",
            "`from_nickname`",
            "`image`",
            "`voice`",
            "`video`",
            "`read`",
            "`add_at`", 
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 20;
    }
    
    //获取站内短信
    function getMsg($appid = 0, $userid = 0, $page = 1){
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
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_msg` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_msg` WHERE ".$where." ORDER BY `msgid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
            //全部设置成已读
            $sql = "UPDATE `the_msg` SET `read` = 1 WHERE ".$where."";
            $this->db->updateX($sql);
        }
        return $data;
    }
    
    //获取内容
    function getMsgDetail($userid = 0,$msgid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_msg` WHERE `msgid` = '".$msgid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            if($ret[0]['userid'] == $userid){
                $data = $ret[0];
            }
        }
        return $data;
    }
    
    //添加站内信息
    function addNow($data){
        return $this->db->add('the_msg',$data);   //写入记录
    }
    
    //获取未读的信息条数
    function getUnreadNums($appid = 0,$userid = 0){
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid.""); 
        array_push($whereArr, "`userid` = ".$userid.""); 
        array_push($whereArr, "`read` = 0"); 
        $where = implode(" AND ",$whereArr);
        $sql = "SELECT COUNT(*) AS `nums` FROM `the_msg` WHERE ".$where." ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            return $ret[0]['nums'];
        }
        return 0;
    }
    
}