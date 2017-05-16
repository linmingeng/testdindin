<?php
require_once BASE_PATH.'/modules/msg_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/user_level_module.php';
require_once BASE_PATH.'/modules/score_log_module.php';
/**
 * 用户相关模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-20
 */
class user_module {
    
    function __construct() {
        global $db;
        $this->db = $db;
        $this->pageSize = 10;
        $this->fileds = '`userid`,`username`,`nickname`,`birthday`,`user_type`,`phone`,`avatar`,`sex`,`wx_nickname`,`realname`,`country`,`province`,`city`,`district`,`company`,`zip`';
        $this->profile_fileds = '`userid`,`user_type`,`phone`,`username`,`nickname`,`avatar`,`qq`,`email`,`wechat`,`realname`,`wx_nickname`,`idcard`,`sex`,`birthday`,`country`,`province`,`city`,`cityid`,`district`,`business`,`address`,`zip`,`lng`,`lat`,`company`';
        $this->user_filde='`userid`, `user_type`, `appid`, `phone`, `username`, `password`, `nickname`, `avatar`, `qq`, `email`, `wechat`, `realname`, `idcard`, `idcard_front`, `idcard_back`, `idcard_man`, `verified`, `sex`, `birthday`, `country`, `province`, `city`, `cityid`, `district`, `business`, `address`, `zip`, `lng`, `lat`, `company`, `bank`, `bank_user`, `bank_account`, `android_pushid`, `ios_pushid`, `ipad_pushid`, `wp_pushid`, `wx_unionid`, `wx_nickname`, `inviter_uid`, `modal`, `blocked`, `actived`, `add_at`, `reg_ip`, `login_ip`, `login_at`, `logout_at`, `login_times`, `flag`, `problem`, `answer`';
    }
    
    //登录（生成cookie）
    function loginNow($userInfo){
        global $session,$cookiettl,$db;
        $appid = (int)request('appid');
        $inviter_uid = (int)request('inviter_uid');
        $modal = trim(request('modal'));
        
        if(isset($userInfo['cityid']) && $userInfo['cityid'] > 0){
            $cityid = $userInfo['cityid'];
        }else{
            $cityid = (int)request('cityid');
        }
        
        $app_user_module = new app_user_module();
        $profile = $app_user_module->getAppUserDetail($appid, $userInfo['userid'], $cityid);
        if(count($profile) == 0){
            $app_userid = $app_user_module->addNow($appid, $userInfo['userid'], 0, 0, 0, $modal, $inviter_uid, $cityid);  //激活应用会员帐户
            if($app_userid){
                $profile = array(
                    "app_userid" => $app_userid,
                    "appid" => $appid,
                    "userid" => $userInfo['userid'],
                    "money" => 0,
                    "score" => 0,
                    "exp" => 0,
                    "level" => 1,
                    "receive" => 1,
                    "newbie" => 1,
                    "modal" => $modal,
                    "inviter_uid" => $inviter_uid,
                    "flag" => 1,
                    "add_at" => time()
                );
                $user_level_module = new user_level_module;
                $levelConf = $user_level_module->getLevelConf($appid, 1);
                $profile['level_conf'] = $levelConf;
            }else{
                return '系统繁忙，请稍后重试！';
            }
        }

        $login_ip = getIp();
        $login_at = time();
        $db->updateX("UPDATE `the_user` SET `login_ip` = '".$login_ip."', `login_at` = '".$login_at."', `login_times` = `login_times` + 1 WHERE `userid` = '".$userInfo['userid']."' ");
        $auth = myEncode($userInfo['user_type'].' '.$userInfo['userid'].' '.$userInfo['username'].' '.$userInfo['nickname'].' '.$login_ip.' '.$login_at.' '.$cookiettl,'ENCODE');
        //mySetcookie('auth',$auth,$cookiettl);
        $session->set($userInfo['userid'], $auth,$cookiettl);

        $profile['user_type'] = $userInfo['user_type'];      //用户类型：{"array":[["手机注册","0"],["用户名注册","1"],["微信授权","2"]],"mysql":""}
        $profile['userid'] = $userInfo['userid'];
        $profile['username'] = $userInfo['username'];
        $profile['nickname'] = $userInfo['nickname'];
        $profile['wx_nickname'] = $userInfo['wx_nickname'];
        $profile['birthday'] = $userInfo['birthday'];
        $profile['phone'] = $userInfo['phone'];
        $profile['avatar'] = $userInfo['avatar'];
        $profile['sex'] = $userInfo['sex'];
        $profile['realname'] = $userInfo['realname'];
        $profile['country'] = $userInfo['country'];
        $profile['province'] = $userInfo['province'];
        $profile['city'] = $userInfo['city'];
        $profile['district'] = $userInfo['district'];
        $profile['company'] = $userInfo['company'];
        $profile['zip'] = $userInfo['zip'];

        $profile = $this->doScorePrize($profile);      //处理自动签到、生日祝福的积分奖励
        
        $memberData = array(
            'auth' => $auth,
            'ttl' => $cookiettl,
            'profile' => $profile,
            'login_ip' => $login_ip,
            'login_at' => $login_at,
        );
        return $memberData;
    }

    //退出登录
    function logoutNow(){
        global $session,$session_key,$user_info;
        $userid = $user_info['userid'];
        if($userid){
            $this->db->updateX("UPDATE `the_user` SET `logout_at` = '".time()."' WHERE `userid` = '".$userid."' ");
        }
        //mySetcookie('auth','',-31104000);
        $session->del($session_key);
    }
    
    //登录验证
    function checkLogin($phone,$password){
        $ret = $this->db->getX("SELECT ".$this->fileds.",`password`,`blocked` FROM `the_user` WHERE `phone` = '".$phone."' AND `flag` = 1 " );
        if(count($ret)){
            if($ret[0]['blocked']){
                 return '账户 "'.$phone.'" 已被禁止登录！';
            }

            if( $ret[0]['password'] != md5($password)){
                return '登录密码错误！';
            }else{
                return $this->loginNow($ret[0]);
            }
        }
        
        return '账户 "'.$phone.'" 不存在！请先注册！';
    }
    
    //验证手机是否被占用
    function checkPhone($phone, $userid){
        if($userid){
            $ret = $this->db->getX("SELECT 1 FROM `the_user` WHERE `phone` = '".$phone."' AND `userid` != ".$userid." " );
        }else{
            $ret = $this->db->getX("SELECT 1 FROM `the_user` WHERE `phone` = '".$phone."' " );
        }
        return count($ret);    
    }

    //验证身份证是否被占用
    function checkIdcard($idcard, $userid){
        if($userid){
            $ret = $this->db->getX("SELECT 1 FROM `the_user` WHERE `idcard` = '".$idcard."' AND `userid` != ".$userid." " );
        }else{
            $ret = $this->db->getX("SELECT 1 FROM `the_user` WHERE `idcard` = '".$idcard."' " );
        }
        return count($ret);    
    }
    
    //验证手机是否被绑定
    function checkBindPhone($phone){
        $ret = $this->db->getX("SELECT `actived`,`wx_unionid` FROM `the_user` WHERE `phone` = '".$phone."' " );
        if(count($ret)){
            if($ret[0]['actived'] == 1 && $ret[0]['wx_unionid'] && $ret[0]['wx_unionid'] != 'NULL'){
                return 1;       //已激活已绑定
            }
        }
        return 0;    
    }
    
    //验证手机是否被绑定，响应 array
    function checkPhoneBind($phone){
        $ret = $this->db->getX("SELECT `actived`,`wx_unionid` FROM `the_user` WHERE `phone` = '".$phone."' " );
        return $ret[0];
    }
    
    //通过手机号获取用户信息
    function getUserByPhone($phone){
        $ret = $this->db->getX("SELECT ".$this->fileds.",`password`,`blocked`,`actived`,`wx_unionid` FROM `the_user` WHERE `phone` = '".$phone."' " );
        if(count($ret)){
            return $ret[0];    
        }
    }
    
    //绑定帐户(手机帐户与微信帐户绑定)
    function bindUser($phone_userid, $wechat_userid){
        global $user_info;
        if($user_info['userid'] != $wechat_userid || !$phone_userid || !$wechat_userid){
            return ;        //异常数据拒绝绑定
        }
        $wechat_user = $this->db->getX("SELECT `nickname`,`avatar`,`country`,`province`,`city`,`sex`,`wx_unionid`,`wx_nickname`,`inviter_uid` FROM `the_user` WHERE `userid` = ".$wechat_userid." AND `actived` = 0" );
        if(count($wechat_user)){
            $wechat_user = $wechat_user[0];
        }
        if(!$wechat_user['inviter_uid']){
            unset($wechat_user['inviter_uid']);
        }
        $flag = $this->db->update("the_user", array($phone_userid, $wechat_user) );
        if($flag){
            $up = $wechat_user;
            $up['nickname'] =  $up['nickname'].'_bind';
            $up['wx_unionid'] =  $up['wx_unionid'].'_bind';
            $up['flag'] = 0;
            $s = $this->db->update("the_user", array($wechat_userid, $up));
            if(!$s){
                $log = array('phone_userid' => $phone_userid, 'wechat_userid' => $wechat_userid, 'del_wechat_user_error' => '1' );
                mylog(json_encode($log), 'bind_user');
            }
            //$this->db->updateX("DELETE FROM `the_app_user` WHERE `userid` = '".$wechat_userid."' ");     //删除应用会员数据 bug 删除其他应用的会员
        }else{
            $log = array('phone_userid' => $phone_userid, 'wechat_userid' => $wechat_userid, 'update_phone_user_error' => '1' );
            mylog(json_encode($log), 'bind_user');
        }
        return $wechat_user;
    }
    
    //登录验证
    function checkPhoneLogin($appid, $app_name, $phone, $password, $wechat_userid){
        global $cache;
        $ret = $this->db->getX("SELECT ".$this->fileds.",`password`,`blocked`,`actived`,`wx_unionid` FROM `the_user` WHERE `phone` = '".$phone."' AND `flag` = 1 " );
        if(count($ret)){
            if($ret[0]['blocked']){
                 return '账户 "'.$phone.'" 已被禁止登录！';
            }
            if($ret[0]['actived'] == 0){
                $sql = "UPDATE `the_user` SET `password` = '".md5($password)."' , `actived` = 1  WHERE `phone` = '".$phone."' AND `actived` = 0 ";
                $flag = $this->db->updateX($sql);
                if(!$flag){
                    return '操作失败，请重试！';
                }
                $star = '';
                for($i = 0; $i < (strlen($password)-4); $i++){
                    $star .='*';
                }
                $data = array(
                    "appid" => $appid,
                    "phone" => $phone,
                    "sort" => 3,
                    "code" => substr($password, 0, 2).$star.substr($password, -2),
                    //"code" => $password,
                    "add_at" => time()
                );
                $this->db->add('the_sms',$data);   //发送密码通知短信
                
                $key = 'profiles:'.$ret[0]['userid'];
                $cache->del($key);                  //删除缓存
                
                if($wechat_userid && ( empty($ret[0]['wx_unionid']) || strtolower($ret[0]['wx_unionid']) =='null') ){
                    $wechat_user = $this->bindUser($ret[0]['userid'], $wechat_userid); //帐号绑定
                    if(isset($wechat_user['nickname'])){
                        $ret[0]['nickname'] = $wechat_user['nickname'];
                    }
                    if(isset($wechat_user['avatar'])){
                        $ret[0]['avatar'] = $wechat_user['avatar'];
                    }
                    if(isset($wechat_user['sex'])){
                        $ret[0]['sex'] = $wechat_user['sex'];
                    }
                }
                return $this->loginNow($ret[0]);
            }
            if( $ret[0]['password'] != md5($password)){
                return array('passwordError' => 1);
            }else{
                if($wechat_userid && ( empty($ret[0]['wx_unionid']) || strtolower($ret[0]['wx_unionid']) =='null') ){
                    $wechat_user = $this->bindUser($ret[0]['userid'], $wechat_userid); //帐号绑定
                    if(isset($wechat_user['nickname'])){
                        $ret[0]['nickname'] = $wechat_user['nickname'];
                    }
                    if(isset($wechat_user['avatar'])){
                        $ret[0]['avatar'] = $wechat_user['avatar'];
                    }
                    if(isset($wechat_user['sex'])){
                        $ret[0]['sex'] = $wechat_user['sex'];
                    }
                }
                return $this->loginNow($ret[0]);
            }
        }
        //绑定时，提示重新登录
        if($wechat_userid){
            error(403,'请重新登录！');
        }
        
        return '账户 "'.$phone.'" 不存在！请先注册！';
    }
    
    //验证用户名是否被占用
    function checkUsername($username, $userid){
        if($userid){
            $ret = $this->db->getX("SELECT 1 FROM `the_user` WHERE `username` = '".$username."' AND `userid` != ".$userid." " );
        }else{
            $ret = $this->db->getX("SELECT 1 FROM `the_user` WHERE `username` = '".$username."' " );
        }
        return count($ret);    
    }
    
    //验证昵称是否被占用
    function checkNickname($nickname, $userid){
        if($userid){
            $ret = $this->db->getX("SELECT 1 FROM `the_user` WHERE `nickname` = '".$nickname."' AND `userid` != ".$userid." " );
        }else{
            $ret = $this->db->getX("SELECT 1 FROM `the_user` WHERE `nickname` = '".$nickname."' " );
        }
        return count($ret);    
    }

    //验证数据是否存在
    function checkData($data,$userid){
        $ret = $this->db->getX("SELECT `userid` FROM `the_user` WHERE `nickname` = '".$data."' or `idcard` = '".$data."' or `phone` ='".$data."' ");
        if ($ret[0]['userid']==$userid) {
            return false;
        }else{
            return true;
        }
    }

    //写入会员表
    function regNow($data){
        return $this->db->add('the_user',$data);
    }
    
    //获取资料
    function getInfo($userid){
        $sql = "SELECT `userid`,`nickname`,`avatar`,`sex` FROM `the_user` WHERE `userid` = '".$userid."' AND `flag` = 1 ";
        $data = $this->db->getX($sql);
        if(count($data) ){
            return $data[0];    
        }
    }
    
    //获取资料
    function getProfiles(){
        global $user_info;
        if(!$user_info['userid']){
            return ;    
        }
        $sql = "SELECT ".$this->fileds." FROM `the_user` WHERE `userid` = '".$user_info['userid']."' AND `flag` = 1 ";
        $data = $this->db->getX($sql);
        if(count($data) == 0){
            return ;    
        }
        
        $appid = (int)request('appid');
        $app_user_module = new app_user_module();
        $profile = $app_user_module->getAppUserDetail($appid, $user_info['userid']);
        
        $ret = array_merge($data[0], $profile);
        
        $ret = $this->doScorePrize($ret);      //处理自动签到、生日祝福的积分奖励
        
        //获取未读信息
        $msg_module = new msg_module;
        $unread = $msg_module->getUnreadNums($appid, $user_info['userid']);
        $ret['unread'] = $unread;
        
        return $ret;
    }
    
    //获取地址
    function getaddress(){
        global $user_info;
        if(!$user_info['addressid']){
            return ;    
        }
        $sql = "SELECT ".$this->fileds." FROM `the_address` WHERE `addressid` = '".$user_info['addressid']."' AND `flag` = 1 ";
        $data = $this->db->getX($sql);
        if(count($data) == 0){
            return ;    
        }
        
        $appid = (int)request('appid');
        $app_user_module = new app_user_module();
        $profile = $app_user_module->getAppUserDetail($appid, $user_info['addressid']);
        
        $ret = array_merge($data[0], $profile);
        
        $ret = $this->doScorePrize($ret);      //处理自动签到、生日祝福的积分奖励
        
        //获取未读信息
        $msg_module = new msg_module;
        $unread = $msg_module->getUnreadNums($appid, $user_info['addressid']);
        $ret['addressid'] = $unread;
        
        return $ret;
    }

    //处理邀请用户获得积分
    function doInviteScorePrize($appid, $inviter_uid, $userid){
        if(!$appid || !$inviter_uid || !$userid){
            return ;   
        }
        //获取 邀请者 等级 及当前等级的配置信息
        $app_user_module = new app_user_module();
        $level_dt = $app_user_module->getAppUserLevel($appid, $inviter_uid);
        if(!is_array($level_dt) || !isset($level_dt['level'])){
            return ;
        }
        $level = max(1, $level_dt['level']);
        $user_level_module = new user_level_module();
        $leval_conf = $user_level_module->getLevelConf($appid,$level);
        
        if(is_array($leval_conf) && isset($leval_conf['invite_score']) && $leval_conf['invite_score'] > 0){
            $score_log_module = new score_log_module;
            $type = 10;
            $score = $leval_conf['invite_score'];
            $state = $app_user_module->addScore($appid, $inviter_uid, $score, $score);
            if($state){
                $ret['had_sign'] = 1;
                $dt = array(
                    'appid' => $appid,
                    'sub_shopid' => 0,
                    'userid' => $inviter_uid,
                    'type' => $type,      // { "-2": "管理员扣除", "-1": "系统扣除", 0: "兑换商品", 1: "系统赠送", 2: "线上购物获得", 3: "线下购物获得", 4: "每日签到获得", 5: "连续签到奖励", 6: "LBS签到获得", 7: "生日祝福获得", 8: "系统返还", 9: "管理员发放", 10: "邀请奖励" },
                    'score' => $score,
                    'exp' => $score,
                    'data' => $userid,
                    'add_at' => time()
                );
                $score_log_module->addNow($dt);   //添加积分日志
            }
        }
    }
    
    //处理自动签到、生日祝福的积分奖励
    function doScorePrize($ret){
        global $cache;
        
        if(!$ret['phone']){
            return $ret;        //未绑定手机号码的不处理自动签到、生日祝福的积分奖励
        }
        $del_cache = 0;
        $score_log_module = new score_log_module;
        $app_user_module = new app_user_module;
        
        $ret['is_birthday'] = 0;
        //计算生日
        if($ret['birthday'] && $ret['birthday'] != 'NULL'){
            $bdArr = explode('-',$ret['birthday']);
            if(count($bdArr) == 3){
                if(date('m') == $bdArr[1] && date('d') == $bdArr[2]){
                    $ret['is_birthday'] = 1;        //今天是生日
                }
            }
        }
        if($ret['is_birthday'] == 1 && isset($ret['level_conf']) && isset($ret['level_conf']['birthday']) && $ret['level_conf']['birthday'] > 0 ){
            $had_send = $score_log_module->checkIfGot($ret['appid'], $ret['userid'], 7, 0, 1);
            if(!$had_send){     //未发放：发放生日祝福的积分奖励
                $del_cache = 1;
                $type = 7;
                $score = $ret['level_conf']['birthday'];
                $state = $app_user_module->addScore($ret['appid'], $ret['userid'], $score, $score);
                if($state){
                    $ret['score'] = $ret['score'] + $score;
                    $ret['exp'] = $ret['exp'] + $score;
                    $dt = array(
                        'appid' => $ret['appid'],
                        'sub_shopid' => 0,
                        'userid' => $ret['userid'],
                        'type' => $type,      //{"array":[["系统扣除","-1"],["兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"],["系统返还","8"]],"mysql":""}
                        'score' => $score,
                        'exp' => $score,
                        'data' => $ret['birthday'],
                        'add_at' => time()
                    );
                    $score_log_module->addNow($dt);   //添加积分日志
                    
                    //发站内短信
                    $title = '今天是您的生日，祝您生日快乐！';
                    $content = '今天是您的生日，祝您生日快乐！我们给您发了'.$score.'积分。请查收！';
                    $msg_module = new msg_module;
                    $dt = array(
                        'appid' => $ret['appid'],
                        'userid' => $ret['userid'],
                        'sortid' => 1,      //分类；1=系统通知信息; 2=会员通知信息; 3=订单通知信息; 
                        'title' => $title,
                        'content' => $content,
                        'read' => 0,
                        'add_at' => time()
                    );
                    $msg_module->addNow($dt);   //添加积分日志
                }
            }
        }
        
        $ret['had_sign'] = 0;
        //处理每日签到
        if(isset($ret['level_conf']) && isset($ret['level_conf']['auto_sign']) && $ret['level_conf']['auto_sign'] > 0  && isset($ret['level_conf']['sign']) && $ret['level_conf']['sign'] > 0 ){
            $had_sign = $score_log_module->checkIfGot($ret['appid'], $ret['userid'], 4, 1, 0);
            $ret['had_sign'] = $had_sign;
            if(!$had_sign){     //未签到：自动签到
                $del_cache = 1;
                $type = 4;
                $score = $ret['level_conf']['sign'];
                $state = $app_user_module->addScore($ret['appid'], $ret['userid'], $score, $score);
                if($state){
                    $ret['score'] = $ret['score'] + $score;
                    $ret['exp'] = $ret['exp'] + $score;
                    $ret['had_sign'] = 1;
                    $dt = array(
                        'appid' => $ret['appid'],
                        'sub_shopid' => 0,
                        'userid' => $ret['userid'],
                        'type' => $type,      //{"array":[["系统扣除","-1"],["兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"],["系统返还","8"]],"mysql":""}
                        'score' => $score,
                        'exp' => $score,
                        'add_at' => time()
                    );
                    $score_log_module->addNow($dt);   //添加积分日志
                }
            }
        }
        
        if($del_cache){
            $key = 'profiles:'.$ret['userid'];
            $cache->del($key);                                  //删除缓存
            $key = 'score_log:list:'.$ret['appid'].':'.$ret['userid'].':1';
            $cache->del($key);                                  //删除缓存
        }
        return $ret;
    }
    
    //获取未激活的用户基本资料
    function getUser($userid){
        $sql = "SELECT ".$this->fileds.",`blocked`,`actived`,`wx_unionid` FROM `the_user` WHERE `userid` = '".$userid."' AND `flag` = 1 ";
        return $this->db->getX($sql);
    }
    
    //获取用户基本资料
    function getUsers($userids){
        if(!is_array($userids)){
            $userids = array($userids);
        }
        if(count($userids) == 0){
            return ;
        }else if(count($userids) == 1){
            $whereStr .= "`userid` = '".$userids[0]."' AND";
        }else {
            $whereStr .= "`userid` IN (".implode(',',$userids).") AND";
        } 
        $sql = "SELECT ".$this->user_filde." FROM `the_user` WHERE ".$whereStr." `flag` = 1 ";
        return $this->db->getX($sql);
    }
    
    //获取用户详细资料
    function getUsersProfile($userids){
        if(!is_array($userids)){
            $userids = array($userids);
        }
        if(count($userids) == 0){
            return ;
        }else if(count($userids) == 1){
            $whereStr .= "`userid` = '".$userids[0]."' AND";
        }else {
            $whereStr .= "`userid` IN (".implode(',',$userids).") AND";
        } 
        $sql = "SELECT ".$this->profile_fileds." FROM `the_user` WHERE ".$whereStr." `flag` = 1 ";
        return $this->db->getX($sql);
    }
    
    //获取用户性别
    function getSex($userid){
        $userid = (int)$userid;
        if(!$userid){
            return 0;
        }
        $sql = "SELECT `sex` FROM `the_user` WHERE `userid` = '".$userid."' ";
        $res = $this->db->getX($sql);
        if(count($res) && isset($res[0]) && isset($res[0]['sex'])){
            return $res[0]['sex'];
        }
        return 0;
    }
    //获取用户手机号
    function getPhone($userid){
        $userid = (int)$userid;
        if(!$userid){
            return '';
        }
        $sql = "SELECT `phone` FROM `the_user` WHERE `userid` = '".$userid."' ";
        $res = $this->db->getX($sql);
        if(count($res) && isset($res[0]) && isset($res[0]['phone'])){
            return $res[0]['phone'];
        }
        return '';
    }
    
    //修改资料
    function modifyNow($data){
        global $user_info;
        if(!$user_info['userid']){
            return 0;    
        }
        return $this->db->update('the_user',array($user_info['userid'],$data));
    }
    
    //更新资料
    function updateNow($userid, $data){
        return $this->db->update('the_user',array($userid,$data));
    }
    
    //修改密码
    function setPassword($oldpassword,$password){
        global $session,$session_key,$user_info;
        if(!$user_info['userid']){
            return 0;    
        }
        
        $sql = "SELECT `password` FROM `the_user` WHERE `userid` = '".$user_info['userid']."' ";
        $res = $this->db->getX($sql);
        if( ! count($res)){
            return 0;    
        }
        
        if($res[0]['password'] != md5($oldpassword)){
            return -1;    
        }
        
        $ret = $this->db->update('the_user',array($user_info['userid'],array('password' => md5($password))));
        //mySetcookie('auth','',-31104000);
        $session->del($session_key);
        return $ret;
    }
    
    //重置密码
    function resetPassword($phone, $password){
        global $session,$session_key;
        if(! $phone || ! $password){
            return ;    
        }
        $sql = "UPDATE `the_user` SET `password` = '".md5($password)."' WHERE `phone` = '".$phone."' AND `flag` = 1  ";
        $ret = $this->db->updateX($sql);
        //mySetcookie('auth','',-31104000);
        $session->del($session_key);
        return $ret;
    }
    
}