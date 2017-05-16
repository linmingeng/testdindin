<?php
/**
 * 设置银行账号
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class profile {
    
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
                $this->accountname = $result['accountname'];
                $this->bankname = $result['bankname'];
                $this->accountnum = $result['accountnum'];
            }

        }
        
    }
    
    function setNow() {
        global $db,$loginUid;
        
        $accountname = htmlspecialchars(trim($_REQUEST['accountname']));
        $bankname = htmlspecialchars(trim($_REQUEST['bankname']));
        $accountnum = htmlspecialchars(trim($_REQUEST['accountnum']));
        
        $this->accountname = $accountname;
        $this->bankname = $bankname;
        $this->accountnum = $accountnum;
        
        $checkChar = new checkChar;
        
        if(!$checkChar->checkNow("str2-4-10",$accountname)){
            $this->msg = "户名在4到10个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-4-30",$bankname)){
            $this->msg = "开户银行在4到30个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-10-30",$accountnum)){
            $this->msg = "账号在10到30个字符以内!";
            return;
        }
        
        $userTable = $this->userTable;
        $dataArray = array(
            $loginUid,
            array(
                'bankname' => $bankname,
                'accountname' => $accountname,
                'accountnum' => $accountnum
            )
        );
        
        $results = $db->update($userTable,$dataArray);
        $this->msg = "银行账号设置成功!";
    }
    
}

$obj = new profile;
$msg = $obj->msg;

$accountname = $obj->accountname;
$bankname = $obj->bankname;
$accountnum = $obj->accountnum;
        
include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>