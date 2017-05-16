<?php 
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: application/json; charset=utf-8');
include '../config/config.php';
include '../libraries/utils.php';
include '../libraries/mysqli.php';

set_error_handler("errorHandler");
register_shutdown_function("fatalHandler");

$host = $_SERVER['HTTP_HOST'];

$_REQUEST = $_GET + $_POST;
$_REQUEST = myAddslashes($_REQUEST);   //简单的防注入

function response($ret){
    if( ! is_array($ret)){
        if(empty($ret)){
            $ret = array();
        }else{
            $ret = array('message' => htmlspecialchars($ret));
        }
    }
    if( ! isset($ret['code'])){     //补齐code
        if( ! count($ret)){
            $ret['code'] = 404;
        }else{
            $ret['code'] = 200;
        }
    }
    $ret = myjson_encode($ret);   
    $ret = str_replace(chr(10),'',$ret); 
    $ret = str_replace(chr(13),'',$ret);
    $ret = str_replace("\'",chr(39),$ret); 
    echo $ret;
    exit;
}

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    response('请返回重试！code: 1001');
}

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

$host = strtolower($host);
$domain = str_replace('www.','',$host);
$domain = str_replace('test.','',$domain);

$appid = getAppid($domain);

$whereStr = "`domain` = '".$domain."'";
if($appid){
    $whereStr = "`appid` = '".$appid."'";
}

$sql = "SELECT `appid`,`name`,`ver`,`typeid`,`wx_login`,`wx_appid`,`wx_appsecret`,`mobile_login`,`mobile_appid`,`mobile_appsecret` FROM `the_app_sets` WHERE ".$whereStr." AND `flag` = 1 ";
$ret = $db->getX($sql);
if(!is_array($ret) || !count($ret)){
    response('当前应用不存在！code: 1003');
}
$appData = $ret[0];

$code = trim(request('code'));
if(!$code){
    response('缺少参数“code”！code: 1006');
}

$mobile = trim(request('mobile'));
if($mobile){        //移动应用请求login
    $appData['wx_login'] = $appData['mobile_login'];
    $appData['wx_appid'] = $appData['mobile_appid'];
    $appData['wx_appsecret'] = $appData['mobile_appsecret'];
}
if(!$appData['wx_login'] || $appData['wx_appid'] == '' || $appData['wx_appid'] == 'NULL' || $appData['wx_appsecret'] == '' || $appData['wx_appsecret'] == 'NULL'){
    response('暂未启用微信登录！code: 1005');
}

//自动登录
function loginNow($userInfo){
    global $cookiettl,$db;
    $login_ip = getIp();
    $login_at = time();
    $db->updateX("UPDATE `the_user` SET `login_ip` = '".$login_ip."', `login_at` = '".$login_at."', `login_times` = `login_times` + 1 WHERE `userid` = '".$userInfo['userid']."' ");
    $wx_auth = myEncode($userInfo['user_type'].' '.$userInfo['userid'].' '.$userInfo['username'].' '.$userInfo['nickname'].' '.$login_ip.' '.$login_at.' '.$cookiettl,'ENCODE');
    mySetcookie('wx_auth',$wx_auth,$cookiettl);
    
    $memberData = array(
        'wx_auth' => $wx_auth,
        'ttl' => $cookiettl,
        'profile' => array(
            'user_type' => $userInfo['user_type'],      //用户类型：{"array":[["手机注册","0"],["用户名注册","1"],["微信授权","2"]],"mysql":""}
            'userid' => $userInfo['userid'],
            'username' => $userInfo['username'],
            'nickname' => $userInfo['nickname'],
            'wx_nickname' => $userInfo['wx_nickname'],
            'phone' => $userInfo['phone'],
            'avatar' => $userInfo['avatar'],
            'sex' => $userInfo['sex'],
        ),
        'login_ip' => $login_ip,
        'login_at' => $login_at,
    );
    if($userInfo['phone'] && $userInfo['phone'] != 'NULL'){
        mySetcookie('auth',$wx_auth,$cookiettl);        //绑定过手机号，自动登录
        $memberData['auth'] = $wx_auth;
    }
    return $memberData;
}

function req($url, $json = true){
    $ch = curl_init ();    
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 0 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $rep = curl_exec ( $ch );
    $success = false;
    if(!curl_errno($ch)){
        $info = curl_getinfo($ch);
        if($info['http_code'] == 200){
            if($json){
                if(gettype(json_decode($rep, 1)) == 'array' ){
                    return json_decode($rep, 1);
                }else{
                    return ;    
                }
            }
            return $rep;
        }
    }
    curl_close ( $ch );
}

//获取应用会员等级
function getAppUserLevel($appid, $userid){
    $data = array();
    $sql = "SELECT `level` FROM `the_app_user` WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
    $ret = $db->getX($sql);
    if(count($ret)){
        $data = $ret[0];
    }
    return $data;
}

//获取用户等级信息 TODO: cache
function getLevelConf($appid,$level){
    $data = array();
    $sql = "SELECT ".$this->fileds." FROM `the_user_level` WHERE `appid` = '".$appid."' AND `level` = '".$level."'";
    $res = $db->getX($sql);
    if(count($res)){
        return $res[0];
    }
    return $data;
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
    return $db->updateX($sql);
}

//处理邀请用户获得积分
function doInviteScorePrize($appid, $inviter_uid, $userid){
    if(!$appid || !$inviter_uid || !$userid){
        return ;   
    }
    //获取 邀请者 等级 及当前等级的配置信息
    $level_dt = getAppUserLevel($appid, $inviter_uid);
    if(!is_array($level_dt) || !isset($level_dt['level'])){
        return ;
    }
    $level = max(1, $level_dt['level']);
    
    $leval_conf = getLevelConf($appid,$level);
    if(is_array($leval_conf) && isset($leval_conf['invite_score']) && $leval_conf['invite_score'] > 0){
        $type = 10;
        $score = $leval_conf['invite_score'];
        $state = addScore($appid, $inviter_uid, $score, $score);
        if($state){
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
            $db->add('the_score_log',$dt);  //添加积分日志
        }
    }
}

$access_info = req('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appData['wx_appid'].'&secret='.$appData['wx_appsecret'].'&code='.$code.'&grant_type=authorization_code');
if(!is_array($access_info)){
    response('微信系统繁忙！code: 1007');
}
if(isset($access_info['errcode'])){
    response('微信系统繁忙！code: 1008<br>'.$access_info['errmsg']);
}

$user_info = req('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_info['access_token'].'&openid='.$access_info['openid'].'&lang=zh_CN');
if(!is_array($user_info)){
    response('微信系统繁忙！code: 1009');
}
if(isset($user_info['errcode'])){
    response('微信系统繁忙！code: 1010<br>'.$user_info['errmsg']);
}

if(!isset($user_info['unionid']) || !$user_info['unionid'] ){
    response('微信系统繁忙！code: 1011, unionid:'.$user_info['unionid']);
}

//通过unionid判断是否已注册
$sql = "SELECT `userid`,`username`,`nickname`,`user_type`,`phone`,`avatar`,`sex`,`wx_nickname` FROM `the_user` WHERE `wx_unionid` = '".$user_info['unionid']."' AND `flag` = 1 ";
$ret = $db->getX($sql);
if(is_array($ret) && count($ret)){  //已注册
    response(loginNow($ret[0]));
}

$inviter_uid = intval(request('inviter_uid'));
$level = 1;
//注册新用户
$data = array(
    'user_type' => 2,           //用户类型：{"array":[["手机注册","0"],["用户名注册","1"],["微信授权","2"]],"mysql":""}
    'appid' => $appData['appid'],
    'wx_unionid' => $user_info['unionid'],
    'wx_nickname' => $user_info['nickname'],
    'nickname' => $user_info['nickname'],
    'password' => '',
    'sex' => $user_info['sex'],
    'city' => $user_info['city'],
    'province' => $user_info['province'],
    'country' => $user_info['country'],
    'avatar' => $user_info['headimgurl'],
    'modal' => trim(request('modal')),
    "inviter_uid" => $inviter_uid,
    'reg_ip' => getIp(),
    'add_at' => time(),
    'actived' => 0,
    'username' => '',
    'phone' => '',
    
);
$userid = $db->add('the_user',$data);
if(!$userid){
    response('微信系统繁忙！code: 1012');
}

doInviteScorePrize($appData['appid'], $inviter_uid, $userid);      //处理邀请奖励

//激活应用会员
$sdata = array(
    "appid" => $appData['appid'],
    "userid" => $userid,
    "money" => 0,
    "score" => 0,
    "exp" => 0,
    "level" => 1,
    "receive" => 1,
    "newbie" => 1,
    "modal" => trim(request('modal')),
    "inviter_uid" => $inviter_uid,
    "flag" => 1,
    "update_at" => time(),
    "active_at" => time(),
    "add_at" => time()
);
$app_userid = $db->add('the_app_user',$sdata);   //写入记录
$data['userid'] = $userid;
response(loginNow($data));