<?php
/**
 * 忘记密码
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class forget {
    
    public $userTable = 'the_user';     //用户表名
    public $msg ;                       //提示信息
    public $ul ;                        //转向地址
    public $sec = 3000;                 //转向时间，默认3秒后转向：3000
    
    function __construct() {
        $act = trim($_REQUEST['act']);
        
        if($act == "get"){
            return $this->getNow();
        }
    }
    
    function getNow() {
        global $db,$siteConfig,$loginType;
        
        $userTable = $this->userTable;
        
        $idcard = trim($_REQUEST['idcard']);
        $verifyCode = strtolower($_REQUEST['verifyCode']);
        $verifyCodeCheck = strtolower($_SESSION['verifyCode']);
        
        $this->idcard = $idcard;
        
        $checkChar = new checkChar;
        
        if(!$checkChar->checkNow("idcard",$idcard)){
            $this->msg = "请输入正确的身份证号码！";
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
            $this->ul = "?do=getPassword&idcard=".$idcard."&username=".urlencode($username)."";     
            $this->sec = 1000;
            $this->msg = "请继续回答提示问题！";
        }else{
            $this->msg = "请输入正确的身份证号码！";
            return;
        }

    }
    
}

$obj = new forget;
$idcard = $obj->idcard;
$msg = $obj->msg;
$ul = $obj->ul;
$sec = $obj->sec;

include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>