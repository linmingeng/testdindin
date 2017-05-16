<?php
require_once BASE_PATH.'/modules/models_module.php';
/**
 * 商品模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class goods_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`goodsid`",
            "`appid`",
            "`groupid`",
            "`sub_groupid`",
            "`goods_sn`",
            "`name`", 
            "`slogan`", 
            "`image`", 
            "`image1`", 
            "`image2`", 
            "`image3`", 
            "`info`", 
            "`base_info`", 
            "`status`", 
            "`delivery_at`", 
            "`active_at`", 
            "`resume_at`", 
            "`return_money`", 
            "`limit_num`", 
            "`original_price`",
            "`origin_place`",
            "`store_place`",
            "`price`",
            "`store`",
            "`sales`",
            "`models`", 
            "`row_name`",
            "`brand`",
            "`weight`",
            "`storeid`",
            //"`country`",
            //"`area`",
            "`add_at`",
        );
        $this->fileds = implode(",",$filedsArr);
        $this->pageSize = 10;
    }
    
    function makeWhereStr($appid = 0, $groupid = 0, $sub_groupid = 0){
        $whereArr = array();
        $orderStr = '';
        if($appid){
            array_push($whereArr, "`appid` = ".$appid.""); 
        }
        if($groupid == 0){
            if($sub_groupid == -10){         //积分兑换 按照添加时间降序排列
                array_push($whereArr, "`status` = 10 "); 
                $orderStr = 'ORDER BY `add_at` DESC';
            }else if($sub_groupid == -9){   //特卖 按照ordernum降序排列
                array_push($whereArr, "`status` = 9 "); 
                $orderStr = 'ORDER BY `ordernum` DESC';
            }else if($sub_groupid == -8){   //返现 按照ordernum降序排列
                array_push($whereArr, "`status` = 8 "); 
                array_push($whereArr, "`resume_at` > ".time()." "); 
                array_push($whereArr, "`return_money` > 0 "); 
                $orderStr = 'ORDER BY `ordernum` DESC';
            }else if($sub_groupid == -7){   //试吃 按照ordernum降序排列
                array_push($whereArr, "`status` = 7 "); 
                array_push($whereArr, "`resume_at` > ".time()." "); 
                $orderStr = 'ORDER BY `ordernum` DESC';
            }else if($sub_groupid == -6){   //秒杀 按照ordernum降序排列
                array_push($whereArr, "`status` = 6 "); 
                array_push($whereArr, "`resume_at` > ".time()." "); 
                $orderStr = 'ORDER BY `ordernum` DESC';
            }else if($sub_groupid == -5){   //预购 按照ordernum降序排列
                array_push($whereArr, "`status` = 5 "); 
                array_push($whereArr, "`resume_at` > ".time()." "); 
                $orderStr = 'ORDER BY `ordernum` DESC';
            }else if($sub_groupid == -4){   //推荐 按照更新时间降序排列
                array_push($whereArr, "`best` = 1"); 
                $orderStr = 'ORDER BY `update_at` DESC';
            }else if($sub_groupid == -3){   //热卖 按照销量降序排列
                $orderStr = 'ORDER BY `sales` DESC';
            }else if($sub_groupid == -2){   //新品 按照添加时间降序排列
                $orderStr = 'ORDER BY `add_at` DESC';
            }else if($sub_groupid == -1){   //全部 按照ordernum降序排列
                $orderStr = 'ORDER BY `ordernum` DESC';
//            }else if($sub_groupid == 0){    //全部进口食品
//                array_push($whereArr, "`country` >= 100");
//                $orderStr = 'ORDER BY `ordernum` DESC';
            }else if($sub_groupid > 0){     //具体进口区域的食品
                array_push($whereArr, "`country` = ".$sub_groupid.""); 
                $orderStr = 'ORDER BY `ordernum` DESC';
            }else if($sub_groupid == -11){//猜你喜欢 商品倒序
                $orderStr = 'ORDER BY `goodsid` DESC';
            }else if($sub_groupid == -12){//店铺推荐 商品倒序
                array_push($whereArr, "`is_recommend` = 1 "); 
                $orderStr = 'ORDER BY `goodsid` DESC';
            }else if($sub_groupid == -13){//已结束 商品倒序
                array_push($whereArr, "`status` = 6 ");
                array_push($whereArr, "`resume_at` < ".time()." ");
                $orderStr = 'ORDER BY `goodsid` DESC';
            }else if($sub_groupid == -14){//未开始 商品倒序
                array_push($whereArr, "`status` = 6 ");
                array_push($whereArr, "`active_at` > ".time()." ");
                $orderStr = 'ORDER BY `goodsid` DESC';
            }
        }else if($groupid > 0){             //具体分组 按照ordernum降序排列
            array_push($whereArr, "`groupid` = ".$groupid.""); 
            if($sub_groupid > 0){
                array_push($whereArr, "`sub_groupid` = ".$sub_groupid.""); 
            }
            $orderStr = 'ORDER BY `ordernum` DESC';
        }
        array_push($whereArr, "`check` = 1");
        array_push($whereArr, "`flag` = 1");
        $whereStr =  implode(" AND ",$whereArr);
        
        return array('whereStr' => $whereStr, 'orderStr' => $orderStr);
    }
    
    //获取特定数量的商品
    function getSomeGoods($appid = 0, $groupid = 0, $sub_groupid = 0, $num = 4){
        $res = $this->makeWhereStr($appid, $groupid, $sub_groupid);
        $where = $res['whereStr'];
        $orderStr = $res['orderStr'];
        $limit = '0,'.$num;
        
        $sql = "SELECT ".$this->fileds." FROM `the_goods` WHERE ".$where." ".$orderStr." LIMIT ".$limit;
        $res = $this->db->getX($sql);
        if(count($res)){
            $res = $this->getGoodsModles($res);
        }
        return $res;
    }
    
    //获取商品
    function getGoods($appid = 0, $groupid = 0, $sub_groupid = 0, $page = 1,$storeid = 0,$recommend=0){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        
        $res = $this->makeWhereStr($appid, $groupid, $sub_groupid);
        $where = $res['whereStr'];
        $orderStr = $res['orderStr'];
        
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_goods` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_goods` WHERE ".$where." ".$orderStr." LIMIT ".$limit;
            $res = $this->db->getX($sql);
            if(count($res)){
                $res = $this->getGoodsModles($res);
            }
            $data["results"] = $res;
        }
        return $data;
    }

    //获取店铺商品
    function getStoreGoods($storeid,$recommend=0,$gid = 0,$page = 1){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr[] = ' `check` = 1 ';
        $whereArr[] = ' `flag` = 1 ';
        $whereArr[] = ' `storeid` = '.$storeid;
        if($recommend>0){
            $whereArr[] = ' `is_recommend` = '.$recommend;
        }
        if($gid>0){
            $whereArr[] = ' `groupid` = '.$gid;
        }
        $where = implode(' AND ',$whereArr);
        $orderStr = ' ORDER BY `ordernum` DESC';

        $countSql = "SELECT COUNT(*) AS `count` FROM `the_goods` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_goods` WHERE ".$where." ".$orderStr." LIMIT ".$limit;
            $res = $this->db->getX($sql);
            if(count($res)){
                $res = $this->getGoodsModles($res);
            }
            $data["results"] = $res;
        }
        return $data;
    }
    
    //单个获取商品
    function getOneGoods($goodsid){
        $sql = "SELECT ".$this->fileds." FROM `the_goods` WHERE `goodsid` = ".$goodsid." AND `check` = 1 AND `flag` = 1 ";
        $res = $this->db->getX($sql);
        if(count($res)){
            $res = $this->getGoodsModles($res);
            return $res[0];    
        }
    }
   
    //获取多个出售中的商品
    function getSaleGoods($goodsids, $appid = 0, $checked = 1){
        $whereStr = "";
        if($appid){
            $whereStr = "`appid` = '".$appid."' AND";
        }
        if(!is_array($goodsids)){
            $goodsids = array($goodsids); 
        }
        if(count($goodsids) == 0){
            return ;
        }else if(count($goodsids) == 1){
            $whereStr .= "`goodsid` = '".$goodsids[0]."' AND";
        }else{
            $whereStr .= "`goodsid` IN (".implode(',',$goodsids).") AND";
        }
        if($checked){
            $whereStr .= "`check` = 1 AND";
        }
        $sql = "SELECT ".$this->fileds." FROM `the_goods` WHERE ".$whereStr." `flag` = 1 ";
        $res = $this->db->getX($sql);
        if(count($res)){
            $res = $this->getGoodsModles($res);
        }
        return $res;
    }
    
    //获取推荐的商品
    function getBestGoods($gid, $goodsid = 0){
        $whereStr = "";
        if($gid){
            $whereStr = "`groupid` = '".$gid."' AND";
        }
        if($goodsid){
            $whereStr .= "`goodsid` != '".$goodsid."' AND";
        }
        $sql = "SELECT ".$this->fileds." FROM `the_goods` WHERE ".$whereStr." `best` = 1  AND `check` = 1 AND `flag` = 1 ORDER BY `ordernum` DESC LIMIT 4";
        $res = $this->db->getX($sql);
        if(count($res)){
            $res = $this->getGoodsModles($res);
        }
        return array('results' => $res);
    }
    
    //搜索应用  todo: 未使用
    function search($keyword){
        $sql = "SELECT ".$this->fileds." FROM `the_goods` WHERE `name` LIKE '%".$keyword."%' AND `check` = 1 AND `flag` = 1 ORDER BY `ordernum` DESC,`appid` DESC LIMIT 20";
        $res = $this->db->getX($sql);
        if(count($res)){
            $res = $this->getGoodsModles($res);
        }
        return $res;
    }
    
    //获取商品的多型号
    function getGoodsModles($goods){
        if(is_array($goods)){
            $models_module = new models_module;
            $goodsid = array();
            foreach($goods as $good){
                if($good['models']){    //有多型号
                    $goodsid[] = $good['goodsid'];
                }
            }
            if(count($goodsid)){
                $rets = array();
                $models = $models_module->getModels($goodsid);
                foreach($goods as $good){
                    if(isset($models[$good['goodsid']])){
                        $good['models_data'] = $models[$good['goodsid']];
                        $store = 0;
                        $orders = 0;
                        $sales = 0;
                        foreach($good['models_data'] as $k => $v){  //补齐多型号的价格信息
                            $store += $v['store'];
                            $orders += $v['orders'];
                            $sales += $v['sales'];
                            if($v['original_price'] <= 0){
                                $good['models_data'][$k]['original_price'] = $good['original_price'];
                            }
                            if($v['price'] <= 0){
                                $good['models_data'][$k]['price'] = $good['price'];
                            }
                            if($v['return_money'] <= 0){
                                $good['models_data'][$k]['return_money'] = $good['return_money'];
                            }
                            if(!$v['image']){
                                $good['models_data'][$k]['image'] = $good['image'];
                            }
                            $good['models_data'][$k]['active_at'] = $good['active_at'];
                            $good['models_data'][$k]['resume_at'] = $good['resume_at'];
                            $good['models_data'][$k]['limit_num'] = $good['limit_num'];
                            $good['models_data'][$k]['status'] = $good['status'];
                        }
                        $good['store'] = $store;
                        $good['orders'] = $orders;
                        $good['sales'] = $sales;
                    }
                    $rets[] = $good;
                }
                return $rets;
            }
            return $goods;
        }
    }
}