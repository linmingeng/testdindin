<?php
require_once BASE_PATH.'/modules/trader_level_module.php';
/**
 * 分销商模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-11-24
 */
class trader_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`traderid`",
            "`money`", 
            "`income`",
            "`exp`",
            "`level`",
            "`status`",
            "`add_at`", 
        );
        
       $this->fileds = implode(",",$filedsArr);
       
       $this->pageSize = 15;
    }
    
    //申请加入分销商
    function addNow($appid, $userid, $inviter_uid, $money = 0, $exp = 0, $level = 0){
        $inviter_uid = (int)$inviter_uid;
        $lv = (int)$lv;
        if($lv < 1){
            $lv = 1;    
        }
        $data = array(
            "appid" => $appid,
            "userid" => $userid,
            "money" => $money,
            "income" => $money,
            "exp" => $exp,
            "level" => 1,
            "inviter_uid" => $inviter_uid,
            "status" => 0,
            "add_at" => time()
        );

        $traderid = $this->db->add('the_trader',$data);   //写入记录
        if($traderid){
            return array("alert" => "申请成功，请等待审核！","traderid" => $traderid, 'lv' => $lv);    
        }else{
            return "申请失败！请重试！";  
        }
    }
    
    //获取详情
    function getDetail($appid, $userid = 0){
        $sql = "SELECT ".$this->fileds." FROM `the_trader` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //获取分销商信息；可自动升级
    function getTraderDetail($appid, $userid){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_trader` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
            
            //自动升降级
            $trader_level_module = new trader_level_module;
            $traderLevels = $trader_level_module->getTraderLevels($appid);
            if(count($traderLevels)){
                $cur_level = $data['level'];                         //当前级别
                for($i = 1; $i< 9; $i++){
                    if(isset($traderLevels[$i]) && $traderLevels[$i]['auto_up'] && $data['exp'] >= $traderLevels[$i]['exp']){
                        $cur_level = $i;
                    }
                }
                $data['color'] = $traderLevels[$cur_level]['color'];
                $data['title'] = $traderLevels[$cur_level]['title'];
                $data['order_rate'] = $traderLevels[$cur_level]['order_rate'];
                $data['sub_rate'] = $traderLevels[$cur_level]['sub_rate'];
                $data['exp'] = $traderLevels[$cur_level]['exp'];
                if($cur_level != $data['level']){                   //级别有变化
                    //更新分销商信息
                    $sql = "UPDATE `the_trader` SET `level` = '".$cur_level."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
                    $this->db->updateX($sql);
                    $data['level'] = $cur_level;
                }
            }
        }
        return $data;
    }
    
    //获取分销商基本信息
    function getTraderData($appid, $userid){
        $appid = (int)$appid;
        $userid = (int)$userid;
        if($userid){
            $sql = "SELECT `inviter_uid`,`status` FROM `the_trader` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' ";
            $res = $this->db->getX($sql);
            if(count($res)){
                return $res[0];    
            }
        }
    }
    
    //获取分销商可提现余额
    function getTraderMoney($appid, $userid){
        $appid = (int)$appid;
        $userid = (int)$userid;
        if($userid){
            $sql = "SELECT `inviter_uid`,`status`,`money` FROM `the_trader` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' ";
            $res = $this->db->getX($sql);
            if(count($res)){
                return $res[0];    
            }
        }
    }
    
    //减少分销商可提现余额
    function reduceTraderMoney($appid, $userid, $money){
        $appid = (int)$appid;
        $userid = (int)$userid;
        $money = floatval($money);
        if($userid && $money){
            $sql = "UPDATE `the_trader` SET `money` = 0 WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' ";
            return $this->db->updateX($sql);
        }
    }
    
    //获取分销商伙伴列表
    function getList($appid, $userid, $page = 1){
        $fileds = '`userid`,`nickname`,`sex`,`add_at`';
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`inviter_uid` = ".$userid."");
        array_push($whereArr, "`status` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_trader` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT `the_trader`.`userid`,`the_trader`.`level`,`the_trader`.`add_at`,`the_user`.`nickname`,`the_user`.`sex` FROM `the_trader`,`the_user` ";
            $sql .= "WHERE `the_trader`.`appid` = ".$appid." AND `the_trader`.`inviter_uid` = ".$userid." AND `the_trader`.`status` =1 AND  `the_trader`.`userid` = `the_user`.`userid` ORDER BY `the_trader`.`traderid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //获取分销商客户列表
    function getCustomer($appid, $userid, $page = 1){
        $fileds = '`userid`,`nickname`,`sex`,`add_at`';
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`inviter_uid` = ".$userid."");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_app_user` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT `the_app_user`.`userid`,`the_app_user`.`level`,`the_app_user`.`add_at`,`the_user`.`nickname`,`the_user`.`sex`,`the_user`.`city` FROM `the_app_user`,`the_user` ";
            $sql .= "WHERE `the_app_user`.`appid` = ".$appid." AND `the_app_user`.`inviter_uid` = ".$userid." AND `the_app_user`.`flag` =1 AND `the_app_user`.`userid` = `the_user`.`userid` ORDER BY `the_app_user`.`app_userid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //给分销商发提成 
    function sendMoney($appid, $userid, $money = 0, $exp = 0){
        $appid = (int)$appid;
        $userid = (int)$userid;
        $money = floatval($money);
        $exp = (int)$exp;
        $setArr = array();
        if($money){
            $setArr[] = "`money` = `money` + ".$money."";
            $setArr[] = "`income` = `income` + ".$money."";
        }
        if($exp){
            $setArr[] = "`exp` = `exp` + ".$exp."";
        }
        if(!count($setArr)){
            return 0;    
        }
        $sql = "UPDATE `the_trader` SET ".implode(' , ', $setArr)." WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' AND `status` = 1 ";
        return $this->db->updateX($sql);
    }
}