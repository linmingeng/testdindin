<?php
require_once BASE_PATH.'/libraries/checkChar.php';
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/score_log_module.php';
require_once BASE_PATH.'/modules/user_level_module.php';
/**
 * 应用会员模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class app_user_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`app_userid`",
            "`appid`", 
            "`userid`", 
            "`money`",
            "`score`",
            "`exp`",
            "`level`",
            "`receive`",
            "`newbie`",
            "`trader`",
            "`modal`",
            "`inviter_uid`",
            "`cityid`",
            "`deduct_at`", 
            "`active_at`", 
            "`update_at`",  
            "`add_at`", 
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 20;
    }
    
    //添加
    function addNow($appid, $userid, $score, $exp, $level, $modal, $inviter_uid, $cityid){
        $data = array(
            "appid" => $appid,
            "userid" => $userid,
            "money" => 0,
            "score" => $score,
            "exp" => $exp,
            "level" => $level,
            "receive" => 1,
            "newbie" => 1,
            "modal" => $modal,
            "inviter_uid" => $inviter_uid,
            "cityid" => $cityid,
            "flag" => 1,
            "update_at" => time(),
            "active_at" => time(),
            "add_at" => time()
        );
        
        return $this->db->add('the_app_user',$data);   //写入记录
    }
    
    //获取最新的应用会员
    function getLatestUser($appid = 0, $update_at = 0, $skip = 0){
        $skip = (int)$skip;
        $skip = max(0,$skip);
        $data = array(
            "count" => 0,
            "update_at" => $update_at,
            "skip" => 0,
            "results" => "",
        );
        $whereArr = array();
        if(!$appid){
            return $data;
        }
        array_push($whereArr, "`appid` = ".$appid.""); 
        if($update_at){
            array_push($whereArr, "`update_at` >= '".$update_at."'");
        }
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_app_user` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_app_user` WHERE ".$where." ORDER BY `update_at` ASC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $userids = array();
            foreach($results as $key => $val){
                $userids[] = $val["userid"];
            }
            $user_module = new user_module;
            $usersData = $user_module->getUsersProfile($userids);
            if(count($usersData)){
                $users = array();
                foreach($usersData as $user){
                    $users[$user['userid']] = $user;
                }
                foreach($results as $key => $val){
                    if(!isset($users[$val["userid"]])){
                        $users[$val["userid"]] = array();
                    }
                    $results[$key]['level'] = max(1, $results[$key]['level']);
                    $results[$key]['user'] = $users[$val["userid"]];
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
    
    //获取应用会员等级
    function getAppUserLevel($appid, $userid){
        $data = array();
        $sql = "SELECT `level` FROM `the_app_user` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
     }
    
    //获取应用会员信息
    function getAppUserDetail($appid, $userid, $cityid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_app_user` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
            $data['level'] = max(1,$data['level']);
            $cur_level = $data['level'];
            
            //每周年自动扣除经验值
            $user_level_module = new user_level_module;
            $userLevels = $user_level_module->getUserLevels($appid);        //todo: 按需读取字段
            $levelConf = $userLevels[$cur_level];
            $data['level_conf'] = $levelConf;
            $deduct_score = 0;      //扣除积分
            $deduct_exp = 0;        //扣除经验
            $deduct_conf = array(
                'score' => 0,
                'exp' => 0
            );
            if(isset($levelConf['deduct_score']) && $levelConf['deduct_score'] > 0 ){
                $deduct_conf['score'] = $levelConf['deduct_score'];        //每周年自动扣除的积分
            }
            if(isset($levelConf['deduct_exp']) && $levelConf['deduct_exp'] > 0 ){
                $deduct_conf['exp'] = $levelConf['deduct_exp'];            //每周年自动扣除的经验值
            }
            $year_data = array();
            if(!$data['deduct_at']){
                $data['deduct_at'] = $data['add_at'];
            }
            $t = time();
            $y = date('Y') - date('Y',$data['deduct_at']);
            if($y > 0){             //存在跨年度
                $tt = strtotime((date('Y', $data['deduct_at'])+$y).'-'.date('m-d H:i:s', $data['deduct_at']));
                if($t < $tt){       //未达到周年
                    $y --;
                    if($y > 0 ){    //继续存在跨年度
                        $tt = strtotime((date('Y', $data['deduct_at'])+$y-1).'-'.date('m-d H:i:s', $data['deduct_at']));
                        if($t < $tt){
                            $y --;  //修正周年
                        }
                    }
                }
            } 
            if($y > 0){                                     //有经验值、积分过期，自动扣除
                if($deduct_conf['score'] > 0){
                    if($data['score'] >= $y*$deduct_conf['score']){
                        $deduct_score = $y*$deduct_conf['score'];
                    }else{
                        $deduct_score = $data['score'];
                    }
                    $data['score'] = $data['score'] - $deduct_score;
                }
                if($deduct_conf['exp'] > 0){
                    if($data['exp'] >= $y*$deduct_conf['exp']){
                        $deduct_exp = $y*$deduct_conf['exp'];
                    }else{
                        $deduct_exp = $data['exp'];
                    }
                    $data['exp'] = $data['exp'] - $deduct_exp;
                }
                
                //计算扣除的年份
                $cur_year = date('Y');
                $year_data[] = $cur_year;
                if($y > 1){
                    for($i=1;$i<$y;$i++){
                        $year_data[] = $cur_year+$y;
                    }
                }
            }
            
            //自动升降级
            if(count($userLevels)){
                if(isset($userLevels[$data['level'] + 1])){
                    $data['next_level'] = $data['level'] + 1;
                    $data['next_level_conf'] = $userLevels[$data['next_level']];
                }
                for($i = 1; $i< 9; $i++){
                    if(isset($userLevels[$i]) && $userLevels[$i]['auto_up'] && $data['exp'] >= $userLevels[$i]['exp']){
                        $cur_level = $i;
                    }
                }
            }
            $setStr = "";
            if($cur_level != $data['level']){                   //级别有变化
                $data['level'] = $cur_level;
                $setStr .= "`level` = '".$cur_level."', `deduct_at` = '".time()."',";
            }
            
            if($deduct_score){
                $setStr .= "`score` = `score` - ".$deduct_score.",";
            }
            
            if($deduct_exp){
                $setStr .= "`exp` = `exp` - ".$deduct_exp.",";
            }
            
            
            $cityStr = '';  //更新cityid
            if($data['cityid'] == 0 ){
                $newCityid = (int)request('cityid');
                if($newCityid > 0 ){
                    $cityStr = ",`cityid` = '".$newCityid."'";
                }else if($cityid > 0 ){
                    $cityStr = ",`cityid` = '".$cityid."'";
                }
            }
            
            if($setStr){
                //更新应用用户信息
                $sql = "UPDATE `the_app_user` SET ".$setStr." `update_at` = '".time()."',`active_at` = '".time()."' ".$cityStr."  WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
                $ret = $this->db->updateX($sql);
                if($ret && $deduct_score){
                    //记录积分日志
                    $score_log_module = new score_log_module;
                    $dt = array(
                        'appid' => $appid,
                        'sub_shopid' => 0,
                        'userid' => $userid,
                        'type' => -1,      //{"array":[["系统扣除","-1"],["兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"],["系统返还","8"]],"mysql":""}
                        'score' => $deduct_score,
                        'exp' => $deduct_exp,
                        'data' => implode(',', $year_data),
                        'add_at' => time()
                    );
                    $score_log_module->addNow($dt);   //添加积分日志
                }
            }else{
                //更新最后活跃时间
                $sql = "UPDATE `the_app_user` SET `active_at` = '".time()."' ".$cityStr." WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
                $ret = $this->db->updateX($sql);    
            }
            
        }
        return $data;
    }
    
    //设置接受信息状态
    function setReceive($appid, $userid, $receive){
        $sql = "UPDATE `the_app_user` SET `receive` = '".$receive."',`update_at` = '".time()."'  WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        return $this->db->updateX($sql);
    }
    
    //判断是否新手
    function isNewbie($appid, $userid){
        $sql = "SELECT `newbie` FROM `the_app_user` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        $res = $this->db->getX($sql);
        if(is_array($res) && isset($res[0]) ){
            return $res[0]['newbie'];
        }else{
            return 1;       //1: 是新手 0:不是新手
        }
    }
    
    //获取可用积分和经验
    function getScoreAndExp($appid, $userid){
        $appid = (int)$appid;
        $userid = (int)$userid;
        $sql = "SELECT `score`,`exp` FROM `the_app_user` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."'";
        $res = $this->db->getX($sql);
        if(is_array($res) && isset($res[0]) ){
            return $res[0];
        }
    }
    
    //扣除积分 和经验
    function useScoreAndExp($appid, $userid, $score, $exp){
        $userid = (int)$userid;
        $score = (int)$score;
        $sql = "UPDATE `the_app_user` SET `score` = `score` - ".$score.",`exp` = `exp` - ".$exp.",`update_at` = '".time()."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' AND `score` >= ".$score." ";
        return $this->db->updateX($sql);
    }
    
    //扣除积分 
    function useScore($appid, $userid, $score){
        $userid = (int)$userid;
        $score = (int)$score;
        $sql = "UPDATE `the_app_user` SET `score` = `score` - ".$score.",`update_at` = '".time()."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' AND `score` >= ".$score." ";
        return $this->db->updateX($sql);
    }
    
    //增加积分 
    function addScore($appid, $userid, $score = 0, $exp = 0){
        $userid = (int)$userid;
        $score = (int)$score;
        $exp = (int)$exp;
        $setArr = array();
        if($score){
            $setArr[] = "`score` = `score` + ".$score."";
        }
        if($exp){
            $setArr[] = "`exp` = `exp` + ".$exp."";
        }
        if(!count($setArr)){
            return 0;    
        }
        $sql = "UPDATE `the_app_user` SET ".implode(' , ', $setArr).",`update_at` = '".time()."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        return $this->db->updateX($sql);
    }
    
    //添加用户
    function user_add($appid,$phone){
        $checkChar = new checkChar;
        $ret = array(
            'userid' => 0,
            'status' => 0
        );
        if( ! $phone){
            $ret['msg'] = '请提交手机号码';
            return $ret;
        }
        if( ! $checkChar->checkNow("mobile",$phone)){
            $ret['msg'] = '请提交正确的手机号码';
            return $ret;
        }
        
        $user_module = new user_module();
        if($user_module->checkPhone($phone)){       //已注册
            $ret['msg'] = '当前手机号码的用户已添加，无须重复添加';
            return $ret;
        }
        $username = trim(request('username'));
        if($user_module->checkUsername($username)){    //用户名已被占用
            if($user_module->checkUsername($phone)){    //用户名已被占用
                $str = "23456789ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz";
                $randname = '';
                for ($i=0; $i<4; $i++) {
                    $randname .= $str[mt_rand(1,strlen($str))-1];
                }
                $username = $randname.substr($phone,-4);
            }else{
                $username = $phone;
            }
        }
        
        $password = trim(request('password'));
        if($password){
            $actived = 1;
        }else{
            $actived = 0;
        }
        
        $nickname = trim(request('nickname'));
        if(!$nickname){
            $nickname = $phone;   
        }
        
        $time = time();
        $ip = getIp();
        $modal = 'import';
    
        $data = array(
            'appid' => $appid,
            'user_type' => 0,   //用户类型：{"array":[["手机注册","0"],["用户名注册","1"],["微信授权","2"]],"mysql":""}
            'phone' => $phone,
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
            'avatar' => trim(request('avatar')),
            'qq' => trim(request('qq')),
            'email' => trim(request('email')),
            'wechat' => trim(request('wechat')),
            'realname' => trim(request('realname')),
            'idcard' => trim(request('idcard')),
            'sex' => trim(request('sex')),
            'birthday' => trim(request('birthday')),
            "country" => trim(request('country')),
            "province" => trim(request('province')),
            "city" => trim(request('city')),
            "cityid" => intval(request('cityid')),
            "district" => trim(request('district')),
            "business" => trim(request('business')),
            "address" => trim(request('address')),
            "zip" => trim(request('zip')),
            'lng' => request('lng'),
            'lat' => request('lat'),
            'company' => trim(request('company')),
            'modal' => $modal,
            'actived' => $actived,
            'reg_ip' => $ip,
            'add_at' => $time,
        );
        $userid = $user_module->regNow($data);
        if(!$userid){
            $ret['msg'] = '用户添加失败';
            return $ret;
        }
        $app_userid = $this->addNow($appid, $userid, 0, 0, 0, $modal, 0, intval(request('cityid')));  //激活应用会员帐户
        
        $ret['status'] = 1;
        $ret['userid'] = $userid;
        $ret['msg'] = '用户添加成功';
        return $ret;
    }
    
    //更新trader字段
    function updateTrader($userid, $appid, $trader){
        $sql = "UPDATE `the_app_user` SET `trader` = '".$trader."',`update_at` = '".time()."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        return $this->db->updateX($sql);
    }
    
    //删除应用会员
    function deleteAppUser($appid,$userid){
        $sql = "DELETE FROM `the_app_user` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        return $this->db->delX($sql);
    }
    
    //更新level字段
    function updateLevel($userid, $appid, $level){
        $sql = "UPDATE `the_app_user` SET `level` = '".$level."',`update_at` = '".time()."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        return $this->db->updateX($sql);
    }
    
    //更新update_at字段
    function updateAt($appid, $userid){
        $sql = "UPDATE `the_app_user` SET `update_at` = '".time()."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        return $this->db->updateX($sql);
    }
    
}