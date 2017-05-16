<?php
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/user_level_module.php';
require_once BASE_PATH.'/modules/score_log_module.php';

/**
 * 应用会员相关控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-20
 */
class app_user {
    
    function __construct(){
        $this->app_user_module = new app_user_module();
    }
    
    //设置是否接受push信息
    function receive_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            return ;
        }
       
        $key = 'profiles:'.$user_info['userid'];
        $cache->del($key);                                  //删除缓存
        
        $data = array(
            'receive' => request('receive')
        );
        
        $appid = (int)request('appid');
        
        $this->app_user_module->setReceive($appid, $user_info['userid'], $data['receive']);
        
        return $data;
    }
    
    //送积分
    function score_add_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $type = trim(request('type'));
        $data = trim(request('data'));
        $appid = (int)request('appid');
        $sub_shopid = (int)request('sub_shopid');
        $app_conf_module = new app_conf_module;
        $conf = $app_conf_module->getConf($appid, 'member');
        //if( !is_array($conf['member']) || !isset($conf['member'][$type]) || $conf['member']['open'] !=  1){
        if( !is_array($conf['member']) ){   //去掉 会员配置的开关，以会员等级的设置为准
            return array('alert' => '活动暂未开始，请联系客服！');
        }
        
        $score_txt = '积分';
        if(isset($conf['member']['score_txt']) && $conf['member']['score_txt']){
            $score_txt = $conf['member']['score_txt'];    
        }
        
        $score_log_module = new score_log_module;
        
        //积分日志分类：{"array":[["系统扣除","-1"],["积分兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"]],"mysql":""}
        //$status = intval($conf['member'][$type]);        //去掉 会员配置的开关，以会员等级的设置为准
        //if(!$status){
        //    return array('alert' => '活动暂未开始，请联系客服！');
        //}
        $userLevel = $this->app_user_module->getAppUserLevel($appid, $user_info['userid']);
        if(is_array($userLevel) && isset($userLevel['level'])){
            $userLevel['level'] = (int)$userLevel['level'];
            $userLevel['level'] = max(1,$userLevel['level']);
        }else{
            error(403,'请重新登录！');
        }
        
        $user_level_module = new user_level_module;
        $levelConf = $user_level_module->getLevelConf($appid,$userLevel['level']);
        if(!is_array($levelConf) || !count($levelConf)){
            return array('alert' => '活动暂未开始，请联系客服！');
        }
        $score = intval($levelConf[$type]);
        if(!$score){
            return array('alert' => '活动暂未开始，请联系客服！');
        }
        
        if($type == 'sign'){                //每日签到领取积分、每日一次
            $typeid = 4;
            if($score_log_module->checkIfGot($appid, $user_info['userid'], 4, 1, 0)){
                return array('alert' => '今日已签到，明天再来吧！');
            }
            $msg = '签到成功！<br>获得'.$score.''.$score_txt.'！';
        }else if($type == 'continuous'){    //连续7日签到领取积分、每日一次
            $typeid = 5;
            if($score_log_module->checkIfGot($appid, $user_info['userid'], 5, 1, 0)){
                return array('alert' => '连续签到的'.$score_txt.'奖励领取过啦！');
            }
            $num = $score_log_module->checkIfGot($appid, $user_info['userid'], 4, 7, 0);
            if($num != 7){
                return array('alert' => '连续7天签到才可以领取'.$score_txt.'奖励！');
            }
            $msg = '连续签到的'.$score_txt.'奖励领取成功！<br>获得'.$score.''.$score_txt.'！';
        }else if($type == 'lbs_sign'){      //LBS签到领取积分、每日一次
            $lbs_sign_pre = '门店';
            if(isset($conf['member']['lbs_sign_pre']) && $conf['member']['lbs_sign_pre']){
                $lbs_sign_pre = $conf['member']['lbs_sign_pre'];    
            }
            if($sub_shopid <= 0){
                return array('alert' => $lbs_sign_pre.'签到失败，请重新定位！');
            }
            $typeid = 6;
            //判断是否在门店附近 TODO: xxxx
            
            //判断今天是否已领取
            if($score_log_module->checkIfGot($appid, $user_info['userid'], 6, 1, 0, $sub_shopid)){
                return array('alert' => '当前'.$lbs_sign_pre.'签到已完成，明天再来吧！');
            }
            $msg = $lbs_sign_pre.'签到成功！<br>获得'.$score.''.$score_txt.'！';
            $key = 'score_log:list:'.$appid.':'.$user_info['userid'].':1:6';
            $cache->del($key);               //删除缓存
        
        }else if($type == 'birthday'){       //生日领取积分、每年一次
            $typeid = 7;
            if($score_log_module->checkIfGot($appid, $user_info['userid'], 7, 0, 1)){
                return array('alert' => '今年已领取过生日祝福'.$score_txt.'啦！');
            }
            $msg = '生日祝福'.$score_txt.'领取成功！<br>获得'.$score.''.$score_txt.'！';
        }else{
            return array('alert' => '活动暂未开始，请联系客服！');    
        }
        
        $key = 'profiles:'.$user_info['userid'];
        $cache->del($key);                                  //删除缓存
        $key = 'score_log:list:'.$appid.':'.$user_info['userid'].':1';
        $cache->del($key);                                  //删除缓存
        
        $state = $this->app_user_module->addScore($appid, $user_info['userid'], $score, $score);
        $ret = array(
            'state' => $state
        );
        if($state){
            $ret['alert'] = $msg;
            $score_log_module = new score_log_module;
            $dt = array(
                'appid' => $appid,
                'sub_shopid' => $sub_shopid,
                'userid' => $user_info['userid'],
                'type' => $typeid,      //{"array":[["系统扣除","-1"],["兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"],["系统返还","8"]],"mysql":""}
                'score' => $score,
                'exp' => $score,
                'data' => $data,
                'add_at' => time()
            );
            $score_log_module->addNow($dt);   //添加积分日志
        }
        return $ret;
    }
    
}