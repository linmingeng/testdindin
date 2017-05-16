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
    response('暂未启用微信绑定！code: 1005');
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

$user_info = array(
    'user_type' => 0,
    'userid' => 0,
    'username' => 'guest',
    'nickname' => 'guest',
    'login_ip' => '',
    'login_at' => 0,
);
//获取登录用户信息
$auth_data = '';
if( ! empty($_SERVER['HTTP_AUTH'])) {
    $auth_data = $_SERVER['HTTP_AUTH'];
}else if( ! empty($_COOKIE[$cookiepre.'auth'])) {
    $auth_data = $_COOKIE[$cookiepre.'auth'];
}
/* 微信绑定时，不考虑 wx_auth
else if( ! empty($_SERVER['HTTP_WX_AUTH'])) {
    $auth_data = $_SERVER['HTTP_WX_AUTH'];
}else if( ! empty($_COOKIE[$cookiepre.'wx_auth'])) {
    $auth_data = $_COOKIE[$cookiepre.'wx_auth'];
} 
*/

if($auth_data){
    $auth_data = explode(" ", myEncode($auth_data, 'DECODE'));
}
if(is_array($auth_data) && count($auth_data)){
    $user_info = array(
        'user_type' => $auth_data[0],
        'userid' => $auth_data[1],
        'username' => $auth_data[2],
        'nickname' => $auth_data[3],
        'login_ip' => $auth_data[4],
        'login_at' => $auth_data[5],
    );
}

if(!$user_info['userid']){
    response(array('alert' => '请先登陆后，再绑定微信号！'));
}

$access_info = req('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appData['wx_appid'].'&secret='.$appData['wx_appsecret'].'&code='.$code.'&grant_type=authorization_code');
if(!is_array($access_info)){
    response('微信系统繁忙！code: 1007');
}
if(isset($access_info['errcode'])){
    response('微信系统繁忙！code: 1008<br>'.$access_info['errmsg']);
}

$wx_user_info = req('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_info['access_token'].'&openid='.$access_info['openid'].'&lang=zh_CN');
if(!is_array($wx_user_info)){
    response('微信系统繁忙！code: 1009');
}
if(isset($wx_user_info['errcode'])){
    response('微信系统繁忙！code: 1010<br>'.$wx_user_info['errmsg']);
}

if(!isset($wx_user_info['unionid']) || !$wx_user_info['unionid'] ){
    response('微信系统繁忙！code: 1011, unionid:'.$wx_user_info['unionid']);
}

$sql = "SELECT `userid`,`nickname`,`avatar`,`country`,`province`,`city`,`sex`,`wx_unionid`,`wx_nickname`,`inviter_uid`,`actived` FROM `the_user` WHERE `wx_unionid` = '".$wx_user_info['unionid']."' AND `flag` = 1 ";
$ret = $db->getX($sql);
if(is_array($ret) && count($ret)){  //已注册
    if($ret[0]['userid'] == $user_info['userid']){
        response(array('alert' => '微信绑定成功！', 'nickname' => $user_info['nickname']));
    }else if($ret[0]['actived'] == 1){
        response(array('alert' => '当前微信号已绑定过啦，请更换一个微信号！'));
    }else if($ret[0]['actived'] == 0){ //帐号合并
        $wechat_user = $ret[0];
        $wechat_userid = $wechat_user['userid'];
        unset($wechat_user['userid']);
        $phone_userid = $user_info['userid'];
        if(!$wechat_user['inviter_uid']){
            unset($wechat_user['inviter_uid']);
        }
        $flag = $db->update("the_user", array($phone_userid, $wechat_user) );
        if($flag){
            $cache = new Redis_cache();
            $key = 'profiles:'.$phone_userid;
            $cache->del($key);                  //删除缓存
                
            $up = $wechat_user;
            $up['nickname'] =  $up['nickname'].'_bind';
            $up['wx_unionid'] =  $up['wx_unionid'].'_bind';
            $up['flag'] = 0;
            $s = $db->update("the_user", array($wechat_userid, $up));
            if(!$s){
                $log = array('phone_userid' => $phone_userid, 'wechat_userid' => $wechat_userid, 'del_wechat_user_error' => '1' );
                mylog(json_encode($log), 'wx_bind_user');
                response(array('alert' => '微信绑定失败，请稍后重试！'));
            }
            $db->updateX("DELETE FROM `the_app_user` WHERE `userid` = '".$wechat_userid."' ");     //删除应用会员数据
            response(array('alert' => '微信绑定成功！', 'nickname' => $wechat_user['nickname']));
        }else{
            $log = array('phone_userid' => $phone_userid, 'wechat_userid' => $wechat_userid, 'update_phone_user_error' => '1' );
            mylog(json_encode($log), 'wx_bind_user');
            response(array('alert' => '微信绑定失败，请稍后重试！'));
        }
    }
}

//更新用户信息
$data = array(
    'wx_unionid' => $wx_user_info['unionid'],
    'wx_nickname' => $wx_user_info['nickname'],
    'nickname' => $wx_user_info['nickname'],
    'sex' => $wx_user_info['sex'],
    'city' => $wx_user_info['city'],
    'province' => $wx_user_info['province'],
    'country' => $wx_user_info['country'],
    'avatar' => $wx_user_info['headimgurl'],
    'actived' => 1
);
$flag = $db->update('the_user',array($user_info['userid'],$data));
if($flag){
    response(array('alert' => '微信绑定成功！', 'nickname' => $wx_user_info['nickname']));
}else{
    response(array('alert' => '微信绑定失败，请稍后重试！'));
}