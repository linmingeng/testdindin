<?php
require_once BASE_PATH.'/modules/coupon_type_module.php';
/**
 * 优惠券模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-09-01
 */
class coupon_module {
    
    function __construct() {
        global $db;
        $this->db = $db;
       
        $this->pageSize = 20;
    }
    
    //获取列表
    function getCoupon($page = 1, $hongbao = 0, $appid = 0, $consume = 0, $type = 0){
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
        array_push($whereArr, "`appid` = '".$appid."' ");
        array_push($whereArr, "`type` = '".$hongbao."' ");
        array_push($whereArr, "`flag` = 1");
        if($type == 1){         //已使用
            array_push($whereArr, "`use` = 1");
        }else if($type == 2){   //已过期
            array_push($whereArr, "`use` = 0");
            array_push($whereArr, "`expires_at` < '".time()."'");
        }else{                  //未使用
            array_push($whereArr, "`use` = 0");
            array_push($whereArr, "`expires_at` >= '".time()."'");
        }
        if($consume){
            array_push($whereArr, "`consume` <= '".$consume."'");
        }
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_coupon` WHERE ".$where." ";    //计算总数
        
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
            $sql = "SELECT `couponid`,`coupon_typeid`,`add_at`,`expires_at` FROM `the_coupon` WHERE ".$where." ORDER BY `couponid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            if(count($results)){
                $coupon_typeids = array();
                foreach($results as $coupon){
                    array_push($coupon_typeids, $coupon["coupon_typeid"]);
                }
                $coupon_typeids = array_unique($coupon_typeids);
                $coupon_type_module = new coupon_type_module();
                $coupon_types = $coupon_type_module->getCouponTypes($coupon_typeids);
                
                foreach($results as $key => $val){
                    $results[$key]["add_at"] = date('Y-m-d',$results[$key]["add_at"]);
                    $results[$key]["expires_at"] = date('Y-m-d',$results[$key]["expires_at"]);
                    $results[$key]["coupon_type"] = $coupon_types[$val["coupon_typeid"]];
                }
            }
            $data["results"] = $results;
        }
        return $data;
    }
    
    //是否有未使用的优惠券
    function ifHasCoupon($userid, $appid){
        $sql = "SELECT COUNT(*) AS `count` FROM `the_coupon` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' AND `use` = 0 AND `type` = 0 AND `expires_at` > '".time()."'";
        $res = $this->db->getX($sql);
        if(is_array($res)){
            return $res[0]["count"];
        }
        return 0;
    }
    
    //是否有未使用的红包
    function ifHasHongbao($userid, $appid){
        $sql = "SELECT COUNT(*) AS `count` FROM `the_coupon` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' AND `use` = 0 AND `type` = 1 ";
        $res = $this->db->getX($sql);
        if(is_array($res)){
            return $res[0]["count"];
        }
        return 0;
    }
    
    //领取优惠券
    function addCoupon($userid, $coupon, $quantity = 1){
        if($coupon['type'] == 1){           //0：优惠券 1：红包
            $coupon['expires'] = 36500;     //红包100年后过期
        }
        $data = array(
            "appid" => $coupon['appid'],
            "userid" => $userid,
            "type" => $coupon['type'],
            "coupon_typeid" => $coupon['coupon_typeid'],
            "consume" => $coupon['consume'],
            "use" => 0,
            "expires_at" => time()+ $coupon['expires'] * 86400,
            "add_at" => time(),
            "flag" => 1
        );
        $quantity = max(1,$quantity);
        for($i = 0; $i < $quantity; $i++){
            $couponid = $this->db->add('the_coupon',$data);   //写入记录
        }
        return $couponid;
    }
    
    //使用优惠券
    function useCoupon($userid, $appid, $couponid, $consume, $type = 0){
        $txt = '优惠券';
        if($type ==1){
            $txt = '红包';
        }
        $userid = (int)$userid;
        $appid = (int)$appid;
        $couponid = (int)$couponid;
        if($userid && $appid && $couponid){
            $sql = "SELECT `coupon_typeid`,`use`,`expires_at` FROM `the_coupon` WHERE `couponid` = '".$couponid."' AND `userid` = '".$userid."' AND `flag` = 1";
            $res = $this->db->getX($sql);
            if(count($res)){
                if($res){
                    if($res[0]['use']){
                        return array('message' => '当前'.$txt.'已被使用！');    
                    }
                    if($res[0]['expires_at'] < time()){
                        return array('message' => '当前'.$txt.'已过期！');    
                    }
                    
                    $tsql = "SELECT `appid`,`consume`,`money` FROM `the_coupon_type` WHERE `coupon_typeid` = '".$res[0]['coupon_typeid']."' AND `flag` = 1";
                    $tres = $this->db->getX($tsql);
                    if(count($tres)){
                        if($tres[0]['consume'] > $consume){
                            return array('message' => '金额未满'.$tres[0]['consume'].'，无法使用当前'.$txt.'！');    
                        }
                        if($tres[0]['appid'] && $appid != $tres[0]['appid']){
                            return array('message' => '无法使用当前'.$txt.'！');    
                        }
                        //使用'.$txt.'
                        $this->db->updateX("UPDATE `the_coupon` SET `use` = 1, `use_at` = '".time()."' WHERE `couponid` = '".$couponid."' ");
                        $this->db->updateX("UPDATE `the_coupon_type` SET `use` = `use` + 1 WHERE `coupon_typeid` = '".$res[0]['coupon_typeid']."' ");
                        return array('consume' => $tres[0]['consume'], 'money' => $tres[0]['money']);
                    }else{
                        return array('message' => '当前'.$txt.'不存在！');       
                    }
                }
            }else{
                return array('message' => '当前'.$txt.'不存在！');       
            }
            
        }else{
            return array('message' => '当前'.$txt.'不存在！');             
        }
    }
    
}