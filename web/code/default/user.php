<?php
/**
 * 修改密码
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class user {
    
    public $userTable = 'the_user';     //用户表名
    public $ucsyn;                      //同步登录/退出的代码
    public $msg ;                       //提示信息
    public $ul ;                        //转向地址
    public $sec = 3000;                 //转向时间，默认3秒后转向：3000
    
    function __construct() {
        $act = trim($_REQUEST['act']);
        
        if($act == "setPassword"){
            $this->setPasswordNow();
        }
    }
    
    function setPasswordNow() {
        global $db,$siteConfig,$loginType,$loginUid,$loginUsername;
        
        $userTable = $this->userTable;
        
        $password = trim($_REQUEST['password']);
        $newPassword = trim($_REQUEST['newPassword']);
        $rePassword = trim($_REQUEST['rePassword']);
        $verifyCode = strtolower($_REQUEST['verifyCode']);
        $verifyCodeCheck = strtolower($_SESSION['verifyCode']);
        
        $checkChar = new checkChar;
        
        if(!$checkChar->checkNow("str2-5-30",$password)){
            $this->msg = "原密码在5到30个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-5-30",$newPassword)){
            $this->msg = "新密码在5到30个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-5-30",$rePassword)){
            $this->msg = "确认密码在5到30个字符以内!";
            return;
        }
        
        if($newPassword != $rePassword){
            $this->msg = "两次输入的密码不一致!";
            return;
        }
        
        if($newPassword == $password){
            $this->msg = "新密码不能与原密码一致!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-4-4",$verifyCode)){
            $this->msg = "请输入正确的验证码!";
            return;
        }
        
        if($verifyCode != $verifyCodeCheck){
            $this->msg = "请输入正确的验证码!";
            return;
        }
        
        if($loginType == "uc"){     //通过ucenter登录
            $return = uc_user_edit($loginUsername, $password, $newPassword);    //1=>成功 -1=>密码错误  0=>失败
            if($return == 1){
                
                //重新设置数据库密码
                $dataArray = array(
                   $loginUid,
                   array(
                       'password' => md5($newPassword),
                   )
                );
                $db->update($userTable,$dataArray);
                
                //退出登录
                _setcookie('auth', '', 0);
                
                $this->ul = "?do=login";     
                $this->sec = 1000;
                $this->msg = "恭喜,密码修改成功!请重新登录!";
                
            }else if($return == -1){
                $this->msg = "对不起,原密码错误!";
            }else if($return == 0){
                $this->msg = "对不起,密码修改失败!请重试!";
            }else {
                $this->msg = "未知异常,请重试!";
            }
            
        }else{     //通过db登录
            $sql = "SELECT * FROM `".$userTable."` WHERE `uid`='".$loginUid."' ";
            $results = $db->getX($sql);
            if(count($results) > 0){
                if($results[0]['password'] == md5($password)){
                    if($results[0]['flag']){
                        //重新设置密码
                        $dataArray = array(
                           $loginUid,
                           array(
                               'password' => md5($newPassword),
                           )
                        );
                        
                        $return = $db->update($userTable,$dataArray);
                        if($return){
                            //退出登录
                            _setcookie('auth', '', 0);
                            
                            $this->ul = "?do=login";     
                            $this->sec = 1000;
                            $this->msg = "恭喜,密码修改成功!请重新登录!";
                            
                        }else{ 
                            $this->msg = "未知异常,请重试!";
                        }
                    }else{
                        //退出登录
                        _setcookie('auth', '', 0);
                        
                        //账号被锁定
                        $this->ul = "?do=login";     
                        $this->sec = 1000;
                        $this->msg = "对不起,当前账号已被锁定!";
                        
                    }
                }else{
                    //原密码错误
                    $this->msg = "对不起,原密码错误!";
                }
            }else{
                //退出登录
                _setcookie('auth', '', 0);
                
                //请重新登录
                $this->ul = "?do=login";     
                $this->sec = 1000;
                $this->msg = "请重新登录".$siteConfig['siteName']."”后台管理系统!";
                
            }
        }
        
    }
    
}

$obj = new user;
$ucsyn = $obj->ucsyn;
$msg = $obj->msg;
$ul = $obj->ul;
$sec = $obj->sec;

include './templates/'.$tempDir.'/'.$pagedo.'.htm';

?>