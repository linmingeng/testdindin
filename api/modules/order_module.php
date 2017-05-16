<?php
require_once BASE_PATH.'/modules/order_goods_module.php';
require_once BASE_PATH.'/modules/address_module.php';
require_once BASE_PATH.'/modules/goods_module.php';
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/modules/app_module.php';
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/score_log_module.php';
require_once BASE_PATH.'/modules/reduce_module.php';
require_once BASE_PATH.'/modules/coupon_module.php';
require_once BASE_PATH.'/modules/score_conf_module.php';
require_once BASE_PATH.'/modules/income_module.php';
require_once BASE_PATH.'/modules/trader_module.php';
/**
 * 订单模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class order_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`orderid`",
            "`appid`", 
            "`app_name`", 
            "`app_logo`", 
            "`userid`",
            "`order_type`",
            "`order_number`",
            "`price`", 
            "`delivery_fee`",
            "`note`", 
            "`invoice`",
            "`payment`", 
            "`status`", 
            "`update_at`", 
            "`add_at`", 
        );
        
       $this->fileds = implode(",",$filedsArr);
       
       
       $moreFiledsArr = array(
            "`orderid`",
            "`appid`",
            "`app_name`",
            "`app_logo`",
            "`userid`",
            "`order_type`",
            "`order_number`",
            "`price`",
            "`delivery_fee`",
            "`payment`",
            "`pay_at`",
            "`reach_reduceid`",
            "`reach_consume`",
            "`reach_reduce`",
            "`newbie_reduceid`",
            "`newbie_reduce`",
            "`couponid`",
            "`coupon_consume`",
            "`coupon_money`",
            "`hongbaoid`",
            "`hongbao_consume`",
            "`hongbao_money`",
            "`note`",
            "`invoice`",
            "`delivery_company`",
            "`delivery_sn`",
            "`delivery_time`",
            "`star`",
            "`comment`",
            "`comment_status`",
            "`comment_time`",
            "`return_money`",
            "`return_status`",
            "`return_time`",
            "`status`",
            "`name`",
            "`sex`",
            "`phone`",
            "`area`",
            "`address`",
            "`zip`",
            "`inviter_uid`",
            "`prize_status`",
            "`prize_money`",
            "`prize_rate`",
            "`exp`",
            "`score`",
            "`score_status`",
            "`score_time`",
            "`modal`",
            "`flag`",
            "`update_at`",
            "`add_at`"
        );
        
       $this->moreFileds = implode(",",$moreFiledsArr);
       
       $this->statusArr = array("已过期","待付款","已付款，待发货","已发货，待收货","已收货，待评价","订单完成");
       $this->pageSize = 20;
    }
    
    //获取列表
    function getOrder($appid, $userid = 0, $page = 1){
        global $secret_key;
        
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        if($userid){
            array_push($whereArr, "`userid` = ".$userid."");
        }
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_order` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_order` WHERE ".$where." ORDER BY `orderid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            if(count($results)){
                $order_numbers = array();
                foreach($results as $key => $val){
                    array_push($order_numbers, $val["order_number"]);
                }
                $order_goods_module = new order_goods_module();
                $order_goods = $order_goods_module->getOrderGoods($order_numbers);
                
                foreach($results as $key => $val){
                    $results[$key]["order_goods"] = $order_goods[$val["order_number"]];
                    if($val["status"] == 1 && $userid){
                        $results[$key]["pay_token"] = md5($userid.' '.$appid.' '.$val["orderid"].' '.$secret_key);
                    }
                }
            }
            $data["results"] = $results;
        }
        return $data;
    }
    
    
    //获取最新的订单列表
    function getLatestOrder($appid, $userid = 0, $update_at = 0, $skip = 0){
        global $secret_key;
        $skip = (int)$skip;
        $skip = max(0,$skip);
        $data = array(
            "count" => 0,
            "update_at" => $update_at,
            "skip" => 0,
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        if($userid){
            array_push($whereArr, "`userid` = ".$userid."");
        }
        if($update_at){
            array_push($whereArr, "`update_at` >= '".$update_at."'");
        }
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_order` WHERE ".$where." ";    //计算总数
        $countRes = $this->db->getX($countSql);
        if(is_array($countRes)){
            $data["count"] = $countRes[0]["count"];
        }
        if(isset($_REQUEST['size'])){
            $pageSize = intval($_REQUEST['size']);
        }
        if($pageSize < $this->pageSize){
            $pageSize = $this->pageSize;
        }
        if($pageSize > 1000){
            $pageSize = 1000;
        }
        if($data["count"]){
            $limit = $skip.",".$pageSize;
            if($more){
                $sql = "SELECT ".$this->moreFileds." FROM `the_order` WHERE ".$where." ORDER BY `update_at` ASC LIMIT ".$limit;
            }else{
                $sql = "SELECT ".$this->fileds." FROM `the_order` WHERE ".$where." ORDER BY `update_at` ASC LIMIT ".$limit;
            }
            $results = $this->db->getX($sql);
            if(count($results)){
                $order_numbers = array();
                foreach($results as $key => $val){
                    array_push($order_numbers, $val["order_number"]);
                }
                $order_goods_module = new order_goods_module();
                $order_goods = $order_goods_module->getOrderGoods($order_numbers);
                
                foreach($results as $key => $val){
                    $results[$key]["order_goods"] = $order_goods[$val["order_number"]];
                    if($val["status"] == 1 && $userid){
                        $results[$key]["pay_token"] = md5($userid.' '.$appid.' '.$val["orderid"].' '.$secret_key);
                    }
                    if($results[$key]["update_at"] > $data["update_at"]){
                        $data["update_at"] = $results[$key]["update_at"];
                    }
                }
            }
            $data["results"] = $results;
        }
        if($data["update_at"] == $update_at){
            if(count($data["results"]) == $pageSize){
                $data["skip"] = $skip + $pageSize;
            }else{
                $data["skip"] = 0;      //最后一页
                $data["update_at"] ++;  //更新时间+1s
                if($data["update_at"] > time()){
                    $data["update_at"] = time();    
                }
            }
        }
        return $data;
    }
    
    //获取内容 todo: * 数据安全，按需读取字段
    function getDetail($appid, $userid, $orderid = 0){
        global $secret_key;
        
        $data = array();
        $sql = "SELECT * FROM `the_order` WHERE `orderid` = '".$orderid."' AND `appid` = '".$appid."'  AND `userid` = '".$userid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
            $order_goods_module = new order_goods_module();
            $order_goods = $order_goods_module->getOrderGoods($data["order_number"]);
            $data["order_goods"] = $order_goods[$data["order_number"]];
            if($data["status"] == 1){
                $data["pay_token"] = md5($userid.' '.$appid.' '.$orderid.' '.$secret_key);
            }
        }
        return $data;
    }
    
    //获取订单奖励信息
    function getPrizeData($appid, $userid, $orderid = 0){
        $data = array();
        $sql = "SELECT `orderid`,`order_number`,`return_money`,`return_status`,`inviter_uid`,`prize_money`,`prize_status`,`exp`,`score`,`score_status` FROM `the_order` WHERE `orderid` = '".$orderid."' AND `appid` = '".$appid."'  AND `userid` = '".$userid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //下单
    function addNow($appid, $userid){
        global $secret_key, $cache;
        
        $app_module = new app_module();
        $app = $app_module->getDetail($appid);
        if(empty($app)){
            return array("alert" => "当前商家不存在！下单失败！");
        }
        
        if($app["is_business"] == 0){
            return array("alert" => "当前商家未开业！下单失败！");
        }
        $payment = (int)request('payment');
        if( ! $app["payment"] && $payment ){
            return array("alert" => "当前商家不支持在线支付！请选择货到付款！");    
        }
        
        /* 营业时间的限制暂未启用
        $cur_date = date('Y-m-d');
        $now = time();
        $start_at = $app["start_at"]? strtotime($cur_date.' '.$app["start_at"].':00') : 0;
        $end_at = $app["end_at"]? strtotime($cur_date.' '.$app["end_at"].':00') : 0;
        
        if($start_at && $end_at ){
            if( !($now >= $start_at && $now <= $end_at) ){
                 return "当前商家营业时间为".$app["start_at"]."~".$app["end_at"]." ！";    
            }
        }
        */
        
        $addressid = (int)request('addressid');
        if( ! $addressid){
            error(406,'请选择一个收货地址！');    
        }
        
        $address_module = new address_module();
        $address = $address_module->getDetail($addressid);
        if(empty($address)){ 
             return array("alert" => "请添加新的收货地址！", "link" => "/tab/shopping/address/add");
        }
        
        $order_type = (int)request('order_type');
        $newbie = (int)request('newbie');
        $couponid = (int)request('couponid');
        $coupon_typeid = (int)request('coupon_typeid');
        $hongbaoid = (int)request('hongbaoid');
        $hongbao_typeid = (int)request('hongbao_typeid');
        $note = trim(request('note'));
        $invoice = trim(request('invoice'));
        $modal = trim(request('modal'));
        $inviter_uid = intval(request('inviter_uid'));
        
        $order_goods = request('goods');
        $order_goods = myjson_decode($order_goods);
        if(!count($order_goods)){
            return array("alert" => "购物车数据异常！", "link" => "/tab/shopping/cart");
        }
        
        $goodsids = array();
        foreach($order_goods as $tmp){
            array_push($goodsids,(int)$tmp['goodsid']);
        }
        $goodsids = array_unique($goodsids);
        if( ! count($goodsids)){
            return array("alert" => "购物车数据已过期！", "link" => "/tab/shopping/cart");
        }
        
        $goods_module = new goods_module();
        $goodsRes = $goods_module->getSaleGoods($goodsids);
        if(empty($goodsRes)){
            return array("alert" => "购买的商品不存在！");
        }
        $tmp_appid = 0;
        $goodsData = array();
        foreach($goodsRes as $tmp){
            $goodsData[$tmp['goodsid']] = $tmp;
            $tmp_appid = $tmp['appid'];
        }
        $goods = array();
        
        //计算订单金额
        $score_consume = 0;
        $total_price = 0;
        $return_money = 0;
        $modelsData = '';
        foreach($order_goods as $tmp){
            if($order_type == 10){      //积分换购时：验证商品是否支持积分换购
                if($goodsData[$tmp['goodsid']]['status'] == 10){
                    $score_consume = $goodsData[$tmp['goodsid']]['original_price'];     //记录消耗的积分
                    if($tmp['quantity'] > 1){
                        return array("alert" => "“".$tmp['name']."”一次只能购买一件！");
                    }
                }else{
                    return array("alert" => "“".$tmp['name']."”不支持换购！");
                }
            }
            $ing = 1;       //促销活动状态
            if($goodsData[$tmp['goodsid']]['active_at'] > time()){
                $ing = 0;   //未开始
            }else if($goodsData[$tmp['goodsid']]['active_at'] <= time() && time() < $goodsData[$tmp['goodsid']]['resume_at']){
                $ing = 1;   //进行中
            }else if(time() >= $goodsData[$tmp['goodsid']]['resume_at']){
                $ing = -1;  //已结束
            }
            if($goodsData[$tmp['goodsid']]['status'] == 5 || $goodsData[$tmp['goodsid']]['status'] == 6){  //5=预售 6=秒杀：未开始，无法购买
                if($ing == 0){
                    return array("alert" => "“".$tmp['name']."”的促销活动暂未开始！");
                }
            } 
            
            if($tmp['modelsid'] > 0){                                   //多型号
                if(!isset($goodsData[$tmp['goodsid']]) || !isset($goodsData[$tmp['goodsid']]['models_data']) || !isset($goodsData[$tmp['goodsid']]['models_data']['m_'.$tmp['modelsid']])){
                    return array("alert" => "“".$tmp['name']."”已下架！");
                }
                $modelsData = $goodsData[$tmp['goodsid']]['models_data']['m_'.$tmp['modelsid']]; 
                $goodsData[$tmp['goodsid']]['quantity'] = $tmp['quantity'];
                $goodsData[$tmp['goodsid']]['modelsid'] = $modelsData['modelsid'];
                if($modelsData['name']){
                    $goodsData[$tmp['goodsid']]['name'] = $modelsData['name'];
                }
                if($modelsData['image']){
                    $goodsData[$tmp['goodsid']]['image'] = $modelsData['image'];
                }
                if($modelsData['original_price']){
                    $goodsData[$tmp['goodsid']]['original_price'] = $modelsData['original_price'];
                }
                if($modelsData['price']){
                    $goodsData[$tmp['goodsid']]['price'] = $modelsData['price'];
                }
                if($modelsData['return_money']){
                    $goodsData[$tmp['goodsid']]['return_money'] = $modelsData['return_money'];
                }
                if($modelsData['store']){
                    $goodsData[$tmp['goodsid']]['store'] = $modelsData['store'];
                }
            }else{
                $goodsData[$tmp['goodsid']]['quantity'] = $tmp['quantity'];
                $goodsData[$tmp['goodsid']]['modelsid'] = 0;
            }
            if($goodsData[$tmp['goodsid']]['store'] < $tmp['quantity'] ){
                return array("alert" => "“".$tmp['name']."”库存不足！");
            }
            if($goodsData[$tmp['goodsid']]['status'] == 0 || $goodsData[$tmp['goodsid']]['status'] >= 5 || $goodsData[$tmp['goodsid']]['status'] <= 9 ){
                //是否限购
                if($goodsData[$tmp['goodsid']]['limit_num'] > 0 && $tmp['quantity'] > $goodsData[$tmp['goodsid']]['limit_num'] ){
                    return array("alert" => "“".$tmp['name']."”限购".$goodsData[$tmp['goodsid']]['limit_num']."件！");
                }
                if($goodsData[$tmp['goodsid']]['status'] == 0){
                     $goodsData[$tmp['goodsid']]['price'] = $goodsData[$tmp['goodsid']]['original_price'];      //默认原价出售
                }else if($goodsData[$tmp['goodsid']]['status'] == 8  ){
                     $goodsData[$tmp['goodsid']]['price'] = $goodsData[$tmp['goodsid']]['original_price'];      //返现必须是原价出售
                     if($ing != 1){
                        $goodsData[$tmp['goodsid']]['return_money'] = 0;                                        //活动不是进行中的：无返还
                     }
                }else{                                         
                     if($ing != 1){
                        $goodsData[$tmp['goodsid']]['price'] = $goodsData[$tmp['goodsid']]['original_price'];   //预售、秒杀、试吃/试用、特卖不是进行中的：原价出售
                     }
                }
            }
            if($tmp_appid != $goodsData[$tmp['goodsid']]['appid']){
                return array("alert" => "只能购买同一商家的商品！");
            }
            array_push($goods, $goodsData[$tmp['goodsid']]);
            
            if($order_type != 10){    //积分换购不需要计算 返现 和 价格
                $return_money += $goodsData[$tmp['goodsid']]['return_money'];
                $total_price += $goodsData[$tmp['goodsid']]['price']*$goodsData[$tmp['goodsid']]['quantity'];
            }
        }
        
        //计算优惠金额
        $reach_consume = 0;         //满减优惠消费额
        $reach_reduce = 0;          //满减优惠金额
        $reach_reduceid = 0;          
        $newbie_reduce = 0;         //新人首单优惠金额
        $newbie_reduceid = 0;         
        
        $reach_reduce_data = array();
        $newbie_reduce_data = array();
        if($order_type != 10){      //非积分换购时处理以下逻辑
            $reduce_module = new reduce_module();
            $reduces = $reduce_module->getList($appid);  //获取当前商家的优惠信息
            if(count($reduces)){
                foreach($reduces as $redu){
                    //检查优惠活动的时效性
                    if(($redu['start_at'] == 0 && $redu['end_at'] == 0) || ($redu['start_at'] > 0 && $redu['end_at'] > 0 && time() > $redu['start_at'] && time() < $redu['end_at'] ) ){
                        if($redu['type'] == 0 && $payment == 1){        //满减（在线支付专享）
                            if($total_price >= $redu['consume'] && $redu['consume'] >= $reach_consume){
                                $reach_consume = $redu['consume'];
                                $reach_reduce = $redu['money'];
                                $reach_reduceid = $redu['reduceid'];
                                $reach_reduce_data = $redu;
                            }
                        }else if($redu['type'] == 1 && $payment == 1){  //新人首单优惠（在线支付专享）
                            if($newbie == 1){
                                $newbie_reduce = $redu['money'];
                                $newbie_reduceid = $redu['reduceid'];
                                $newbie_reduce_data = $redu;
                            }
                        }
                    }
                }
            }
        }
        
        $reduce_data_arr = array();
        if(count($reach_reduce_data)){
            $reduce_data_arr[] = $reach_reduce_data;
        }
        if(count($newbie_reduce_data)){
            $reduce_data_arr[] = $newbie_reduce_data;
        }
        if(count($reduce_data_arr)){
            $reduce_data = json_encode($reduce_data_arr);
        }else{
            $reduce_data = '';    
        }
        
        //使用优惠券的优惠金额
        $coupon_consume = 0;
        $coupon_money = 0;
        if($couponid){
            $coupon_module = new coupon_module();
            $ures = $coupon_module->useCoupon($userid, $appid, $couponid, $total_price );
            if(is_array($ures)){
                if(isset($ures['message']) && $ures['message']){
                    return array("alert" => $ures['message']);
                }else if(isset($ures['consume']) && isset($ures['money']) ){
                    $coupon_consume = (int)$ures['consume'];
                    $coupon_money = (int)$ures['money'];
                }else{
                    $couponid = 0;
                }
            }
        }
        
        //使用红包的优惠金额
        $hongbao_consume = 0;
        $hongbao_money = 0;
        if($hongbaoid){
            $coupon_module = new coupon_module();
            $ures = $coupon_module->useCoupon($userid, $appid, $hongbaoid, $total_price , 1);
            if(is_array($ures)){
                if(isset($ures['message']) && $ures['message']){
                    return array("alert" => $ures['message']);
                }else if(isset($ures['consume']) && isset($ures['money']) ){
                    $hongbao_consume = (int)$ures['consume'];
                    $hongbao_money = (int)$ures['money'];
                }else{
                    $hongbaoid = 0;
                }
            }
        }
        
        //计算运费 
        $delivery_fee = $app["delivery_fee"];
        if($app["delivery_fee"] > 0 && $app["free_send_price"] > 0 && $total_price >= $app["free_send_price"] ){
            $delivery_fee = 0;
        }
        
        //计算订单金额
        $price = $total_price - $reach_reduce - $newbie_reduce - $coupon_money - $hongbao_money + $delivery_fee;
        $status = 1;    //待付款
        $order_number = date('YmdHis').rand(1000,9999);
        
        //获取应用配置
        $app_conf_module = new app_conf_module;
        $conf = $app_conf_module->getConf($appid, array('member','trader','reduce_late'));
        
        $app_user_module = new app_user_module;
        $app_user = $app_user_module->getAppUserDetail($appid, $userid);
        if(is_array($app_user) && isset($app_user['inviter_uid']) && $inviter_uid == 0 ){
            $inviter_uid = $app_user['inviter_uid'];   //设置默认的邀请人id
        }

        if($order_type == 10 && $score_consume > 0){            //积分换购时：扣除积分
            $key = 'profiles:'.$userid;
            $cache->del($key);                                  //删除缓存
            $key = 'score_log:list:'.$appid.':'.$userid.':1';
            $cache->del($key);                                  //删除缓存
            
            $state = $app_user_module->useScore($appid, $userid, $score_consume);
            if($state){
                $score_log_module = new score_log_module;
                $dt = array(
                    'appid' => $appid,
                    'userid' => $userid,
                    'type' => 0,      //{"array":[["系统扣除","-1"],["积分兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"]],"mysql":""}
                    'score' => $score_consume,
                    'data' => json_encode(array('order_number' => $order_number)),
                    'add_at' => time()
                );
                $score_log_module->addNow($dt);   //添加积分日志
            }else{
                $score_txt = '积分';
                if(isset($conf['member']) && isset($conf['member']['score_txt']) && $conf['member']['score_txt'] ){
                    $score_txt = $conf['member']['score_txt'];    
                }
                return array("alert" => $score_txt."不足！");
            }
        }
        
        $score = 0;
        $exp = 0;
        $score_data = array();
        if($order_type != 10 && $conf['member']['open'] == 1){  //非积分换购时：奖励积分
            $score_data = array('send_exp' => 0);
            $score_factor = 0;
            if(isset($conf['member'])){
                if(isset($conf['member']['score_factor']) && $conf['member']['score_factor']){
                    $score_factor = $conf['member']['score_factor'];    
                }
                if(isset($conf['member']['exp'])){
                    $score_data['send_exp'] = $conf['member']['exp'];   
                }
            }
            $score = floor($price * $score_factor);             //基础积分
            $score_data['price'] = $price;
            $score_data['score_factor'] = $score_factor;
            $score_data['score_conf'] = array();
            $conf_factor = 0;
            if($score > 0){
                $user_module = new user_module;
                $app_user['sex'] = $user_module->getSex($userid);
                if(is_array($app_user) && isset($app_user['level']) && isset($app_user['sex']) ){
                    //计算积分系数
                    $score_conf_module = new score_conf_module;
                    $score_conf = $score_conf_module->getConfs($appid);
                    if(count($score_conf)){
                        $time = time();
                        foreach($score_conf as $sconf){
                            //时效性
                            if(($sconf['start_at'] > 0 && $sconf['end_at'] > 0 && $sconf['start_at'] < $time && $sconf['end_at'] > $time) || ($sconf['start_at'] == 0 && $sconf['end_at'] == 0 )){
                                //特定等级
                                if($sconf['level'] == 0 || ($sconf['level'] > 0 && $sconf['level'] == $app_user['level']) ){
                                    //特定性别
                                    if($sconf['sex'] == 0 || ($sconf['sex'] > 0 && $sconf['sex'] == $app_user['sex']) ){
                                        $conf_factor += $sconf['factor'];
                                        $score_data['score_conf'][] = $sconf;
                                    }
                                }
                            }
                        }    
                    }
                }
            }
            if($conf_factor){
                $score = $score*$conf_factor;
            }
            $score_data['conf_factor'] = $conf_factor;
            $score_data['user_level'] = $app_user['level'];
            $score_data['user_sex'] = $app_user['sex'];
            if($score_data['send_exp']){
                $exp =  $score;   
            }
        }
        $prize_status = 0;
        $prize_money = 0;
        $prize_rate = 0;
        $is_trader = 0;
        //获取分销商信息
        if($inviter_uid > 0 && isset($conf['trader']) && isset($conf['trader']['open']) && $conf['trader']['open'] == 1){
            $trader_module = new trader_module;
            $traderData = $trader_module->getTraderDetail($appid, $inviter_uid);
            if(is_array($traderData) && isset($traderData['status']) && $traderData['status'] == 1){
                $prize_rate = (int)$traderData['order_rate'];
                $prize_money = floor(($price-$delivery_fee)*$prize_rate)/100;
                $is_trader = 1;
            }
        }
       
        if(count($score_data)){
            $score_data = json_encode($score_data);
        }else{
            $score_data = '';    
        }
        
        $goods_info = array(
            "reduce_late" => (int)$conf['reduce_late'],
            "reduce_goods" => array()
        );
        foreach($goods as $res){
            //$goods_info["reduce_goods"][$res['goodsid']] = $res['quantity'];
            $goods_info["reduce_goods"][] = array(
                'goodsid' => $res['goodsid'],
                'modelsid' => $res['modelsid'],
                'quantity' => $res['quantity'],
            );
        }
        $data = array(
            "userid" => $userid,
            "appid" => $appid,
            "app_name" => $app['name'],
            "app_logo" => $app['logo'],
            "goods_info" => json_encode($goods_info),
            "modal" => $modal,
            "order_type" => $order_type,
            "order_number" => $order_number,
            "price" => $price,                          //订单金额
            "delivery_fee" => $delivery_fee,            //运费
            "payment" => $payment,
            "reach_consume" => $reach_consume,          //满减消费额
            "reach_reduce" => $reach_reduce,            //满减金额
            "reach_reduceid" => $reach_reduceid,        
            "newbie_reduce" => $newbie_reduce,          //新用户首单优惠金额
            "newbie_reduceid" => $newbie_reduceid,
            "reduce_data" => $reduce_data,
            "couponid" => $couponid,
            "coupon_consume" => $coupon_consume,
            "coupon_money" => $coupon_money,
            "hongbaoid" => $hongbaoid,
            "hongbao_consume" => $hongbao_consume,
            "hongbao_money" => $hongbao_money,
            "note" => $note,
            "invoice" => $invoice,
            "return_money" => $return_money,
            "status" => $status,
            "exp" => $exp,
            "score" => $score,
            "score_data" => $score_data,
            "name" => $address['name'],
            "sex" => $address['sex'],
            "phone" => $address['phone'],
            "area" => $address['area'],
            "address" => $address['address'],
            "zip" => $address['zip'],
            "inviter_uid" => $inviter_uid,
            "prize_status" => $prize_status,
            "prize_money" => $prize_money,
            "prize_rate" => $prize_rate,
            "update_at" => time(),
            "add_at" => time()
        );
        $orderid = $this->db->add('the_order',$data);   //写入订单
        if( ! $orderid){
            if($couponid){
                //返还已使用的优惠券
                $this->db->updateX("UPDATE `the_coupon` SET `use` = 0, `use_at` = 0 WHERE `couponid` = '".$couponid."' ");
                //修正优惠券类型已使用数量
                $this->db->updateX("UPDATE `the_coupon_type` SET `use` = `use` - 1 WHERE `coupon_typeid` = '".$coupon_typeid."' ");
            }
            if($hongbaoid){
                //返还已使用的红包
                $this->db->updateX("UPDATE `the_coupon` SET `use` = 0, `use_at` = 0 WHERE `couponid` = '".$hongbaoid."' ");
                //修正红包类型已使用数量
                $this->db->updateX("UPDATE `the_coupon_type` SET `use` = `use` - 1 WHERE `coupon_typeid` = '".$hongbao_typeid."' ");
            }
            if($order_type == 10 && $score_consume > 0){      //积分换购时：返还积分
                $state = $app_user_module->addScore($appid, $userid, $score_consume);
                if($state){
                    $dt = array(
                        'appid' => $appid,
                        'userid' => $userid,
                        'type' => 8,      //{"array":[["系统扣除","-1"],["兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"],["系统返还","8"]],"mysql":""}
                        'score' => $score_consume,
                        'data' => json_encode(array('order_number' => $order_number)),
                        'add_at' => time()
                    );
                    $score_log_module->addNow($dt);   //添加积分日志
                }
            }
            return "下单失败！请重试！";  
        }
        if($couponid){
            $key = 'coupon:0:'.$appid.':'.$userid.':0:1';
            $cache->del($key);                                  //删除缓存
            $key = 'coupon:0:'.$appid.':'.$userid.':1:1';
            $cache->del($key);                                  //删除缓存
        }
        if($hongbaoid){
            $key = 'coupon:1:'.$appid.':'.$userid.':0:1';
            $cache->del($key);                                  //删除缓存
            $key = 'coupon:1:'.$appid.':'.$userid.':1:1';
            $cache->del($key);                                  //删除缓存
        }  
        //处理分销提成
        if($is_trader == 1){
            $income_module = new income_module;
            $dt = array(
                'appid' => $appid,
                'userid' => $inviter_uid,
                'orderid' => $orderid,
                'type' => 1,      //分类；0=未知收入; 1=订单提成; 2=下线提现奖励; 3=系统奖励; 7=提现到账户余额; 8=提现到支付宝; 9=提现到银行卡; 
                'money' => $prize_money,
                'status' => 0,    //状态；0=未完成; 1=完成; 
                'data' => json_encode(array('orderid' => $orderid, 'order_number' => $order_number)),
                'add_at' => time()
            );
            $income_module->addNow($dt);   //添加收入日志
        }
        foreach($goods as $res){
            $data = array(
                "userid" => $userid,
                "appid" => $appid,
                "goods_sn" => $res['goods_sn'],
                "order_number" => $order_number,
                "goodsid" => $res['goodsid'],
                "goods_data" => json_encode($res, 1),
                "name" => str_replace("'","\'",$res['name']),
                "image" => $res['image'],
                "price" => $res['price'],
                "return_money" => $res['return_money'],
                "modelsid" => $res['modelsid'],
                "quantity" => $res['quantity'],
                "add_at" => time()
            );
            $this->db->add('the_order_goods',$data);    //写入订单商品
            
            $upStr = '`orders` = `orders` + '.$res['quantity'].'';      //未发货的数量
            if($conf['reduce_late'] == 0){              //reduce_late: 0=下单时减库存，1=发货时减少库存，2=付款时减库存
                $upStr .= ', `store` = `store` - '.$res['quantity'].''; //库存量
            }
            if($res['modelsid'] > 0){
                $sql = 'UPDATE `the_models` SET '.$upStr.' WHERE `modelsid` = '.$res['modelsid'].'';
            }else{
                $sql = 'UPDATE `the_goods` SET '.$upStr.' WHERE `goodsid` = '.$res['goodsid'].'';
            }
            $this->db->updateX($sql);
        }
        
        if($newbie){
            $sql = 'UPDATE `the_app_user` SET `newbie` = 0, `update_at` = '.time().' WHERE `userid` = '.$userid.' AND `appid` = '.$appid.'';
            $this->db->updateX($sql);                   //更新用户状态：是否新用户首次购物
        }
        
        foreach($reduce_data_arr as $reduce){
            $sql = 'UPDATE `the_reduce` SET `use` = `use` + 1 WHERE `reduceid` = '.$reduce['reduceid'].' ';
            $this->db->updateX($sql);                   //更新优惠活动使用次数
        }
        
        $payToken = md5($userid.' '.$appid.' '.$orderid.' '.$secret_key);
        return array("msg" => "下单成功！","orderid" => $orderid, "pay_token" => $payToken);   
    }
    
    //删除（只能删除未支付或已过期的订单）
    function delNow($appid, $userid, $orderid){
        if( ! $appid || ! $userid || ! $orderid){
            return 0; 
        }
        $status = $this->db->updateX("UPDATE `the_order` SET `flag` = 0,`update_at` = '".time()."' WHERE `orderid` = ".$orderid." AND (`status` <= 1 OR `status` = 5) AND `appid` = ".$appid." AND `userid` = ".$userid." ");
        $ret = array(
            'status' => $status
        );
        if($status){
            $ret['msg'] = "删除成功！";
        }else{
            $ret['msg'] = "删除失败！";
        }
        return $ret;
    }
    
    //设置成已收货状态
    function received($appid, $userid, $orderid){
        if( ! $appid || ! $userid || ! $orderid ){
            return 0; 
        }
        return $this->db->updateX("UPDATE `the_order` SET `status` = 4,`comment_status`=1,`update_at` = '".time()."' WHERE `orderid` = ".$orderid." AND `status` = 3 AND `appid` = ".$appid." AND `userid` = ".$userid." ");
    }
    
    //评价订单
    function comment($appid, $userid, $orderid, $star, $comment){
        if( ! $appid || ! $userid || ! $orderid ){
            return 0; 
        }
        return $this->db->updateX("UPDATE `the_order` SET `status` = 5,`star`='".$star."',`comment_status`=2,`comment`='".$comment."',`comment_time`='".time()."',`update_at` = '".time()."' WHERE `orderid` = ".$orderid." AND `status` = 4 AND `appid` = ".$appid." AND `userid` = ".$userid." ");
    }
    
    //获取分销商订单列表 todo: 未使用
    function getTraderOrder($appid, $userid, $page = 1){
        $fileds = '`order_number`,`prize_money`,`add_at`,`prize_status`';
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
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_order` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$fileds." FROM `the_order` WHERE ".$where." ORDER BY `orderid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //获取分销商订单数量
    function getTraderOrderNum($appid, $userid, $days = 7){
        $days = max($days,0);
        $data = array(
            'orders' => 0,
            'money' => 0
        );
        $cur_date = strtotime(date('Y-m-d'));
        $add_at = $cur_date - 86400*$days;
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`inviter_uid` = ".$userid."");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $sql = "SELECT COUNT(*) AS `orders`, SUM(`price`) AS `money` FROM `the_order` WHERE ".$where." AND `add_at` > '".$add_at."' ";    //计算总数
        $res = $this->db->getX($sql);
        if(is_array($res)){
            $res[0]["money"] = round($res[0]["money"],2);
            $data['orders'] = $res[0]["orders"];
            $data['money'] = $res[0]["money"]?$res[0]["money"]:0;
        }
        return $data;
    }
    
    //是否已购买过产品（存在已付款的订单就算已购买）
    function hadBuy($appid, $userid){
        $whereArr = array();
        array_push($whereArr, "`userid` = ".$userid."");
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`status` > 1");  //订单状态；0=已过期; 1=待付款; 2=已付款，待发货; 3=已发货，待收货; 4=已收货，待评价; 5=订单完成; 
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $sql = "SELECT COUNT(*) AS `num` FROM `the_order` WHERE ".$where." ";    //计算总数
        $res = $this->db->getX($sql);
        return $res[0]['num'];
    }
    
    //更新
    function update($data){
        $data['update_at'] = time();
        return $this->db->add('the_order',$data);   //写入记录
    }
    
    //订单取消
    function cancel($appid, $userid, $orderid){
        $ret = array(
            'status' => 0
        );
        $sql = "SELECT * FROM `the_order` WHERE `orderid` = '".$orderid."' AND `appid` = '".$appid."'  AND `userid` = '".$userid."' ";
        $res = $this->db->getX($sql);
        if(!count($res)){
            $ret['msg'] = '订单不存在！';
            return $ret;
        }
        $orderData = $res[0];
        if($orderData['status'] == 0){
            $ret['msg'] = '该订单已取消！';
            return $ret;
        }else if($orderData['status'] != 1){
            $ret['msg'] = '只能取消未付款的订单！';
            return $ret;
        } 
        $audit = array();
        if($orderData['audit']){
            if( gettype(json_decode($orderData['audit'],1)) == 'array'){
                $audit = json_decode($orderData['audit'],1);
            }
        }
        $audit_data = array(
            'userid' => 0,
            'time' => time(),
            'type' => 6,
        );
        $audit[] = $audit_data;
        
        $orderData['audit'] = json_encode($audit);
        
        $sql = "UPDATE `the_order` SET `status` = 0, `inviter_uid` = 0, `prize_money` = 0, `prize_rate` = 0, `update_at`='".time()."', `audit` = '".$orderData['audit']."' WHERE `orderid` = '".$orderData['orderid']."' AND `status` = 1 ";
        $ok = $this->db->updateX($sql);
        if($ok){
            //更新分销商奖励
            if($orderData['inviter_uid'] > 0 && $orderData['prize_status'] == 0 && $orderData['prize_money'] > 0 ){
                $sql = 'UPDATE `the_income` SET `flag` = 0 WHERE `appid` = '.$orderData['appid'].' AND `userid` = '.$orderData['inviter_uid'].' AND `orderid` = '.$orderData['orderid'].'';
                $this->db->updateX($sql);
            }
            
            //添加日志
            $audit_data['appid'] = $orderData['appid'];
            $audit_data['orderid'] = $orderData['orderid'];
            $this->db->add('the_order_audit', $audit_data);
            
            $orderData['status'] = 0;
            if(isset($orderData["goods_info"]) && $orderData["goods_info"]){
                $goods_info = $orderData['goods_info'];
                if( gettype(json_decode($goods_info,1)) == 'array'){
                    $goods_info = json_decode($goods_info,1);
                    //0=下单时减库存，1=发货时减少库存，2=付款时减库存
                    foreach($goods_info["reduce_goods"] as $gd){
                        $upStr = '';
                        if($goods_info['reduce_late'] == 0){    //reduce_late: 0=下单时减库存，发货时加出售量，1=发货时减少库存同时加出货量，2=付款时减库存，发货时加出售量
                            $upStr = ' `store` = `store` + '.$gd['quantity'].', ';
                        }
                        $upStr .= ' `orders` = `orders` - '.$gd['quantity'].' ';    //减掉未发货的数量
                        if($upStr){
                            if($gd['modelsid']){
                                $sql = 'UPDATE `the_models` SET '.$upStr.' WHERE `modelsid` = '.$gd['modelsid'].'';
                            }else{
                                $sql = 'UPDATE `the_goods` SET '.$upStr.' WHERE `goodsid` = '.$gd['goodsid'].'';
                            }
                            $this->db->updateX($sql);                                     //更新商品订单占用量和销量
                        }
                    }
                }
            }
            $ret['msg'] = "成功取消订单！";
        }else{
            $ret['msg'] = "操作失败！";
        }
        return $ret;
    }
    
    //订单发货
    function delivery($appid, $userid, $orderid, $send_sms, $delivery_company, $delivery_sn){
        $ret = array(
            'status' => 0
        );
        $sql = "SELECT * FROM `the_order` WHERE `orderid` = '".$orderid."' AND `appid` = '".$appid."'  AND `userid` = '".$userid."' ";
        $res = $this->db->getX($sql);
        if(!count($res)){
            $ret['msg'] = '订单不存在！';
            return $ret;
        }
        $orderData = $res[0];
        if($orderData['status'] != 2){
            $ret['msg'] = '只能对已付款、未发货的订单进行发货！';
            return $ret;
        } 
        if(!$delivery_company || !$delivery_sn){
            $ret['msg'] = '请提交快递公司名称和快递单号！';
            return $ret;
        }
        $audit = array();
        if($orderData['audit']){
            if( gettype(json_decode($orderData['audit'],1)) == 'array'){
                $audit = json_decode($orderData['audit'],1);
            }
        }
        $audit_data = array(
            'userid' => 0,
            'time' => time(),
            'type' => 5,
        );
        $audit[] = $audit_data;
        
        $orderData['audit'] = json_encode($audit);
        
        $sql = "UPDATE `the_order` SET `delivery_company`= '".$delivery_company."' ,`delivery_sn`= '".$delivery_sn."' ,`delivery_time`= '".time()."' , `update_at`='".time()."' , `status` = 3, `audit` = '".$orderData['audit']."' WHERE `orderid` = '".$orderData['orderid']."' AND `status` = 2 ";
        $ok = $this->db->updateX($sql);
        if($ok){
            $audit_data['appid'] = $orderData['appid'];
            $audit_data['orderid'] = $orderData['orderid'];
            $this->db->add('the_order_audit', $audit_data);
                
            $orderData['delivery_company'] = $delivery_company;
            $orderData['delivery_sn'] = $delivery_sn;
            $orderData['delivery_time'] = time();
            $orderData['status'] = 3;
            
            if($send_sms){
                $sdt = array(
                    'order_number' => $orderData['order_number'],
                    'delivery_company' => $orderData['delivery_company'],
                    'delivery_sn' => $orderData['delivery_sn'],
                );
                
                //发送通知短信
                $sms_data = array(
                    "appid" => $orderData['appid'],
                    "phone" => $orderData['phone'],
                    "sort" => 7,                //短信分类；0=注册验证码; 1=重置密码验证码; 2=登录验证码; 3=注册密码; 4=申请分销商; 5=分销商审核通过; 6=分销商身份被冻结; 7=发货通知
                    "code" => $orderData['order_number'],
                    "data" => myjson_encode($sdt),
                    "add_at" => time()
                );
                $this->db->add('the_sms', $sms_data);
            }
                
            if(isset($orderData["goods_info"]) && $orderData["goods_info"]){
                $goods_info = $orderData['goods_info'];
                if( gettype(json_decode($goods_info,1)) == 'array'){
                    $goods_info = json_decode($goods_info,1);
                    //0=下单时减库存，1=发货时减少库存，2=付款时减库存
                    foreach($goods_info["reduce_goods"] as $gd){
                        $upStr = '';
                        if($goods_info['reduce_late'] == 1){ //reduce_late: 0=下单时减库存，发货时加出售量，1=发货时减少库存同时加出货量，2=付款时减库存，发货时加出售量
                            $upStr = ' `store` = `store` - '.$gd['quantity'].', ';
                        }
                        $upStr .= ' `orders` = `orders` - '.$gd['quantity'].', ';    //减掉未发货的数量
                        if($gd['modelsid']){
                            $sql = 'UPDATE `the_models` SET '.$upStr.' `sales` = `sales` + '.$gd['quantity'].' WHERE `modelsid` = '.$gd['modelsid'].'';
                        }else{
                            $sql = 'UPDATE `the_goods` SET '.$upStr.' `sales` = `sales` + '.$gd['quantity'].' WHERE `goodsid` = '.$gd['goodsid'].'';
                        }
                        $this->db->updateX($sql);                                     //更新商品订单占用量和销量
                    }
                }
            }
            $ret['msg'] = "成功设置为已发货！";
        }else{
            $ret['msg'] = "操作失败！";
        }
        return $ret;
    }
}