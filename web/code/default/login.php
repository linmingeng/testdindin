<?php
/**
 * 用户登录
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class login {
    
    public $userTable = 'the_user';     //用户表名
    public $uid = 0;                    //-100=>未知异常,请重试,-2=>密码错误,-1=>用户不存在,或者被删除,>0=>用户ID
    public $ucsyn;                      //同步登录/退出的代码
    public $activeUser = 0;             //0=>未激活,1=>已激活
    public $auth ;                      //用户激活信息加密串
    public $msg ;                       //提示信息
    public $ul ;                        //转向地址
    public $sec = 3000;                 //转向时间，默认3秒后转向：3000
    
    function __construct() {
        $act = trim($_REQUEST['act']);
        
        if($act == "login"){
            return $this->loginNow();
        }
    }
    
    function loginNow() {
        global $db,$siteConfig,$loginType;
        
        $userTable = $this->userTable;
        
        $idcard = trim($_REQUEST['idcard']);
        $password = trim($_REQUEST['password']);
        $verifyCode = strtolower($_REQUEST['verifyCode']);
        $verifyCodeCheck = strtolower($_SESSION['verifyCode']);
        
        $this->idcard = $idcard;
        
        $checkChar = new checkChar;
        
        if(!$checkChar->checkNow("idcard",$idcard)){
            $this->msg = "请输入正确的身份证号码！";
            return;
        }
        
        if(!$checkChar->checkNow("str2-5-30",$password)){
            $this->msg = "密码为5到12个字符，可包含字母、数字、下划线，区分大小写！";
            return;
        }
        
        if(!$checkChar->checkNow("str2-4-4",$verifyCode)){
            $this->msg = "请输入正确的验证码！";
            return;
        }
        
        if($verifyCode != $verifyCodeCheck){
            $this->msg = "请输入正确的验证码！";
            return;
        }
        
        $sql = "SELECT * FROM `".$userTable."` WHERE `idcard`='".$idcard."' ";
        $results = $db->getX($sql);
        if(count($results) > 0){
            $username = $results[0]['username'];
        }
        
        if($loginType == "uc"){     //通过ucenter登录
            //通过接口判断登录帐号的正确性，返回值为数组
            list($uid, $username, $password, $email) = uc_user_login($username, $password);
            if($uid > 0) {
                
                $results = $db->get($userTable,$uid);
                if(count($results) > 0){
                    if($results[0]['flag']){
                        $this->activeUser = 1;
                    }
                }
                
                if($this->activeUser){
                    //登陆成功,使用 uc_authcode 函数 对 Cookie 进行加密。也可以使用自己的函数。
                    _setcookie('auth', uc_authcode($uid."\t".$username."\t".time(), 'ENCODE'), 86400);
                    
                    //生成同步登录/退出的代码
                    $ucsyn = uc_user_synlogin($uid);
                    $this->ucsyn = $ucsyn;
                }else{
                    //给未激活用户生成用户激活信息的加密串
                    $auth = uc_authcode($uid."\t".$username."\t".time(), 'ENCODE');
                    $this->auth = $auth;
                    
                    //退出登录
                    _setcookie('auth', '', 0);
                    
                    //生成同步登录/退出的代码
                    $ucsyn = uc_user_synlogout();
                }
            }
        }else{     //通过db登录
            $sql = "SELECT * FROM `".$userTable."` WHERE `username`='".$username."' ";
            $results = $db->getX($sql);
            if(count($results) > 0){
                if($results[0]['password'] == md5($password)){
                    $uid = $results[0]['uid'];
                    if($results[0]['flag']){
                        $this->activeUser = 1;
                        //登陆成功,使用 uc_authcode 函数 对 Cookie 进行加密。也可以使用自己的函数。
                        _setcookie('auth', uc_authcode($uid."\t".$username."\t".time(), 'ENCODE'), 86400);
                    }else{
                        //退出登录
                        _setcookie('auth', '', 0);
                    }
                }else{
                    $uid = -2;
                }
            }else{
                $uid = -1;
            }
        }
        
        $this->uid = $uid;
        
        if($uid > 0){
            if($this->activeUser){
                $this->ul = "?do=member";     
                $this->sec = 1000;
                //$this->msg = "登录成功！";
            }else{
                //转到激活页面
                $this->ul = "?do=active&auth=".$auth."";   
                $this->msg = "请先激活！";
            }
        }else if($uid == -1){
            $this->msg = "用户不存在，或者被删除！";
        }else if($uid == -2){
            $this->msg = "登录密码错误！";
        }else if($uid == -100){
            $this->msg = "未知异常，请重试！";
        }
    }
    
}

$obj = new login;
$uid = $obj->uid;
$idcard = $obj->idcard;
$ucsyn = $obj->ucsyn;
$activeUser = $obj->activeUser;
$auth = $obj->auth;
$msg = $obj->msg;
$ul = $obj->ul;
$sec = $obj->sec;

include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>