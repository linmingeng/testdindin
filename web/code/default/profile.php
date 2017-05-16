<?php
/**
 * 修改资料
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class profile {
    
    public $userTable = 'the_user';     //用户表名
    public $msg ;                       //提示信息
    public $sex = "保密";               //默认性别：男 女 保密
    
    function __construct() {
        $act = trim($_REQUEST['act']);
        
        if($act == "edit"){
            $this->editNow();
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
                $this->name = $result['name'];
                $this->idcard = $result['idcard'];
                
                if(!$result['year'] || !$result['month'] || !$result['day'] ){
                    $this->birthday = "";
                }else{
                    if($result['month'] < 10){
                        $result['month'] = "0".$result['month'];
                    }
                    if($result['day'] < 10){
                        $result['day'] = "0".$result['day'];
                    }
                    
                    $this->birthday = $result['year']."-".$result['month']."-".$result['day'];
                }
                
                $this->sex = $result['sex'];
                $this->province = $result['province'];
                $this->city = $result['city'];
                $this->address = $result['address'];
                $this->zip = $result['zip'];
                $this->phone = $result['phone'];
                $this->mobile = $result['mobile'];
                $this->qq = $result['qq'];
                $this->email = $result['email'];
                $this->msn = $result['msn'];
            }

        }
        
    }
    
    function editNow() {
        global $db,$loginUid;
        
        $name = htmlspecialchars(trim($_REQUEST['name']));
        $idcard = htmlspecialchars(trim($_REQUEST['idcard']));
        $birthday = htmlspecialchars(trim($_REQUEST['birthday']));
        $sex = htmlspecialchars(trim($_REQUEST['sex']));
        $province = htmlspecialchars(trim($_REQUEST['province']));
        $city = htmlspecialchars(trim($_REQUEST['city']));
        $address = htmlspecialchars(trim($_REQUEST['address']));
        $zip = htmlspecialchars(trim($_REQUEST['zip']));
        $phone = htmlspecialchars(trim($_REQUEST['phone']));
        $mobile = htmlspecialchars(trim($_REQUEST['mobile']));
        $qq = htmlspecialchars(trim($_REQUEST['qq']));
        $email = htmlspecialchars(trim($_REQUEST['email']));
        $msn = htmlspecialchars(trim($_REQUEST['msn']));
        
        $this->name = $name;
        $this->idcard = $idcard;
        $this->birthday = $birthday;
        $this->sex = $sex;
        $this->province = $province;
        $this->city = $city;
        $this->address = $address;
        $this->zip = $zip;
        $this->phone = $phone;
        $this->mobile = $mobile;
        $this->qq = $qq;
        $this->email = $email;
        $this->msn = $msn;
        
        $checkChar = new checkChar;
        
        if(!$checkChar->checkNow("str2-4-10",$name)){
            $this->msg = "真实姓名在4到10个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("idcard",$idcard)){
            $this->msg = "请输入正确的身份证号码!";
            return;
        }
        
        if(!$checkChar->checkNow("date",$birthday)){
            $this->msg = "请输入正确的出生日期!";
            return;
        }
        
        if(!in_array($sex,array('男','女','保密'))){
            $this->msg = "请选择正确的性别!";
            return;
        }
        
        if(!$checkChar->checkNow("str2-10-100",$address)){
            $this->msg = "联系地址在10到100个字符以内!";
            return;
        }
        
        if(!$checkChar->checkNow("zip",$zip)){
            $this->msg = "请输入正确的邮政编码!";
            return;
        }
        
        if(!$checkChar->checkNow("phone",$phone)){
            $this->msg = "请输入正确的联系电话!";
            return;
        }
        
        if(!$checkChar->checkNow("mobile",$mobile)){
            $this->msg = "请输入正确的手机号码!";
            return;
        }
        
        if(!$checkChar->checkNow("qq",$qq)){
            $this->msg = "请输入正确的QQ号码!";
            return;
        }
        
        if(!$checkChar->checkNow("mail",$email)){
            $this->msg = "请输入正确的E-mail!";
            return;
        }
        
        if(!$checkChar->checkNow("mail",$msn)){
            $this->msg = "请输入正确的MSN!";
            return;
        }
        
        $year = "";
        $month = "";
        $day = "";
        
        if($birthday != ""){
            $birthdayArray = explode("-",$birthday);    
            if(isset($birthdayArray[0])){
                $year = $birthdayArray[0];
            }
            
            if(isset($birthdayArray[1])){
                $month = $birthdayArray[1];
            }
            
            if(isset($birthdayArray[2])){
                $day = $birthdayArray[2];
            }
        }
        
        $userTable = $this->userTable;
        $dataArray = array(
            $loginUid,
            array(
                'idcard' => $idcard,
                'name' => $name,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'sex' => $sex,
                'province' => $province,
                'city' => $city,
                'address' => $address,
                'zip' => $zip,
                'phone' => $phone,
                'mobile' => $mobile,
                'qq' => $qq,
                'email' => $email,
                'msn' => $msn
            )
        );
        
        $results = $db->update($userTable,$dataArray);
        $this->msg = "资料修改成功!";
    }
    
}

$obj = new profile;
$msg = $obj->msg;

$name = $obj->name;
$idcard = $obj->idcard;
$birthday = $obj->birthday;
$sex = $obj->sex;
$province = $obj->province;
$city = $obj->city;
$address = $obj->address;
$zip = $obj->zip;
$phone = $obj->phone;
$mobile = $obj->mobile;
$qq = $obj->qq;
$email = $obj->email;
$msn = $obj->msn;
        
        
include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>