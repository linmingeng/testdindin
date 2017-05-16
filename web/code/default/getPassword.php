<?php
/**
 * 取回密码
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class getPassword {
    
    public $userTable = 'the_user';     //用户表名
    public $msg ;                       //提示信息
    public $ul ;                        //转向地址
    public $sec = 3000;                 //转向时间，默认3秒后转向：3000
    
    function __construct() {
        $act = trim($_REQUEST['act']);
        $idcard = trim($_REQUEST['idcard']);
        if(isset($_REQUEST['questionId'])){
            $questionId = (int)$_REQUEST['questionId'];
        }else{
            $questionId = rand(1,3);
        }
        //echo "questionId=".$questionId;
        //echo "idcard=".$idcard;
        $this->idcard = $idcard;
        $this->questionId = $questionId;
        
        if($act == "get"){
            return $this->getPasswordNow();
        }else{
            return $this->getUserInfo();
        }
    }
    
    function getUserInfo() {
        global $db,$siteConfig,$loginType;
        
        $userTable = $this->userTable;
        
        $idcard = $this->idcard;
        $questionId = $this->questionId;
        if(!in_array($questionId,array(1,2,3))){
            $this->ul = "?do=forget";     
            $this->sec = 1000;
            $this->msg = "操作异常请重试！";
            return;
        }
        
        $sql = "SELECT * FROM `".$userTable."` WHERE `idcard`='".$idcard."' ";
        $results = $db->getX($sql);
        if(count($results) > 0){
            $question = $results[0]['question'.$questionId];
            if($question == ""){
                $question = $results[0]['question1'];
                $this->questionId = 1;
            }
            
            if($question == ""){
                $question = $results[0]['question2'];
                $this->questionId = 2;
            }
            
            if($question == ""){
                $question = $results[0]['question3'];
                $this->questionId = 3;
            }
            
            if($question == ""){
                $this->ul = "?do=index";     
                $this->sec = 1000;
                $this->msg = "当前账号未设置密码保护资料，无法取回密码！";
                return;
            }
            //echo "question=".$question;
            $this->question = $question;
        }else{
            $this->ul = "?do=forget";     
            $this->sec = 1000;
            $this->msg = "请输入正确的身份证号码！";
            return;
        }
    }
    
    function getPasswordNow() {
        global $db,$siteConfig,$loginType;
        
        $userTable = $this->userTable;
        $questionId = $this->questionId;
        if(!in_array($questionId,array(1,2,3))){
            $this->ul = "?do=forget";     
            $this->sec = 1000;
            $this->msg = "操作异常请重试！";
            return;
        }
        
        $idcard = trim($_REQUEST['idcard']);
        $answer = trim($_REQUEST['answer']);
        $verifyCode = strtolower($_REQUEST['verifyCode']);
        $verifyCodeCheck = strtolower($_SESSION['verifyCode']);
        
        $this->idcard = $idcard;
        $this->answer = $answer;
        
        $checkChar = new checkChar;
        
        if(!$checkChar->checkNow("idcard",$idcard)){
            $this->ul = "?do=forget";     
            $this->sec = 1000;
            $this->msg = "请输入正确的身份证号码！";
            return;
        }
        
        if($verifyCode != $verifyCodeCheck){
            $this->msg = "请输入正确的验证码！";
            $this->getUserInfo();
            return;
        }
        
        $sql = "SELECT * FROM `".$userTable."` WHERE `idcard`='".$idcard."' AND `answer".$questionId."`='".$answer."' ";
        $results = $db->getX($sql);
        if(count($results) > 0){
            $pword = uc_authcode($results[0]['pword'], 'DECODE');
            $this->ul = "?do=login";     
            $this->sec = 1000;
            $this->msg = "您的密码为“".$pword."”，请记住！";
        }else{
            $this->msg = "请输入正确的身份证号码！";
            $this->getUserInfo();
            return;
        }

    }
    
}

$obj = new getPassword;
$questionId = $obj->questionId;
$idcard = $obj->idcard;
$msg = $obj->msg;
$ul = $obj->ul;
$sec = $obj->sec;
$question = $obj->question;

include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>