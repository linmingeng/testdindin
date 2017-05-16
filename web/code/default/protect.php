<?php
/**
 * 密码保护
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class protect {
    
    public $userTable = 'the_user';     //用户表名
    public $msg ;                       //提示信息
    
    function __construct() {
        $act = trim($_REQUEST['act']);
        
        if($act == "set"){
            $this->setNow();
        }else{
            $this->getNow();
        }
    }
    function getNow() {
        global $db,$loginUid;

        $userTable = $this->userTable;
        
        $idArray = $loginUid;
        $results = $db->get($userTable,$idArray);
        if($results != ""){
            
            if(isset($results[0])){
                $result = $results[0];
                $this->question1 = $result['question1'];
                $this->answer1 = $result['answer1'];
                $this->question2 = $result['question2'];
                $this->answer2 = $result['answer2'];
                $this->question3 = $result['question3'];
                $this->answer3 = $result['answer3'];
                
            }

        }
        
    }
    
    function setNow() {
        global $db,$loginUid;
        
        $question1 = htmlspecialchars(trim($_REQUEST['question1']));
        $answer1 = htmlspecialchars(trim($_REQUEST['answer1']));
        $question2 = htmlspecialchars(trim($_REQUEST['question2']));
        $answer2 = htmlspecialchars(trim($_REQUEST['answer2']));
        $question3 = htmlspecialchars(trim($_REQUEST['question3']));
        $answer3 = htmlspecialchars(trim($_REQUEST['answer3']));
        
        $this->question1 = $question1;
        $this->answer1 = $answer1;
        $this->question2 = $question2;
        $this->answer2 = $answer2;
        $this->question3 = $question3;
        $this->answer3 = $answer3;
        
        $checkChar = new checkChar;
        
        if($question1 != ""){
            if($answer1 == ""){
                $this->msg = "答案一在50个字符以内!";
                return;
            }
        }
        
        if($question2 != ""){
            if($answer2 == ""){
                $this->msg = "答案二在50个字符以内!";
                return;
            }
        }
        
        if($question3 != ""){
            if($answer3 == ""){
                $this->msg = "答案三在50个字符以内!";
                return;
            }
        }
        
        if(!$checkChar->checkNow("str2-0-50",$question1)){
            $this->msg = "问题一在50个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-0-50",$answer1)){
            $this->msg = "答案一在50个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-0-50",$question2)){
            $this->msg = "问题二而在50个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-0-50",$answer2)){
            $this->msg = "答案二在50个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-0-50",$question3)){
            $this->msg = "问题三在50个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-0-50",$answer3)){
            $this->msg = "答案三在50个字符以内!";
            return;
        }
        
        $userTable = $this->userTable;
        $dataArray = array(
            $loginUid,
            array(
                'answer1' => $answer1,
                'question1' => $question1,
                'answer2' => $answer2,
                'question2' => $question2,
                'answer3' => $answer3,
                'question3' => $question3
            )
        );
        
        $results = $db->update($userTable,$dataArray);
        $this->msg = "密码保护设置成功!";
    }
    
}

$obj = new protect;
$msg = $obj->msg;

$question1 = $obj->question1;
$answer1 = $obj->answer1;
$question2 = $obj->question2;
$answer2 = $obj->answer2;
$question3 = $obj->question3;
$answer3 = $obj->answer3;
       
include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>