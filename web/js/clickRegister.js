var emailReg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;//����������ʽ

function clickUserNumber(){
	var div = $("#usrNumber").next("div");
	var usrNum = $("#usrNumber").val();
	
	if($.trim(usrNum)==''){
		
		$("#usrNumber").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#usrNumber").next("div").css({"display":"none"});
		return false;
	}else{
		
		if(usrNum.length<6){
			
			$("#usrNumber").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
			$("#usrNumber").next("div").css({"display":"none"});
			return false;
		}
			
		if(!emailReg.test(usrNum)||check_usrEmailNumber()){
				
				$("#usrNumber").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
				$("#usrNumber").next("div").css({"display":"none"});
				return false;
		}
	}
	$("#usrNumber").next("div").css({"display":"none"});
	$("#usrNumber").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'}); 
	return true;
}

function clickPassword(){//��֤����
	var div = $("#usrPwd").next("div");
	var userPwd = $("#usrPwd").val();
	
	if($.trim(userPwd)==''){

		$("#usrPwd").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#usrPwd").next("div").css({"display":"none"});
		return false;
	}else{

		if(userPwd.length<6){
			
			$("#usrPwd").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
			$("#usrPwd").next("div").css({"display":"none"});
			return false;
		}else{
			$("#usrPwd").css({'background-color':'#fff','border':'1px solid rgb(119, 119, 119)'});
			return true;
		}
	}
	$("#usrPwd").next("div").css({"display":"none"});
	$("#usrPwd").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'}); 
	return true;
	
}
function clickPasswordconfirm(){
	var password = $("#usrPwd").val();
	var passwordconfirm = $("#passwordconfirm").val();
	if($.trim(passwordconfirm)==''){//ȷ������Ϊ��

		$("#passwordconfirm").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#passwordconfirm").next("div").css({"display":"none"});
		//$("#passwordconfirm").next("div").css({"display":"none"});
		return false;
	}else{

		if(password!=passwordconfirm){//�����ȷ�����벻һ��
			
			$("#passwordconfirm").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
			$("#passwordconfirm").next("div").css({"display":"none"});
			return false;
		}
	}
	$("#passwordconfirm").next("div").css({"display":"none"});
	$("#passwordconfirm").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'}); 
	return true;
}
function clickParityString(){
	var parityString = $("#parityString").val();
	if($.trim(parityString)==''){

		$("#parityString").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#parityString").next("div").css({"display":"none"});
		return false;
	}else{
	
		if($.trim(parityString).length!=6){
		
			$("#parityString").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
			$("#parityString").next("div").css({"display":"none"});
			return false;
		}
	}
	$("#parityString").next("div").css({"display":"none"});
	$("#parityString").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'}); 
	return true;
}

//������֤��
function clickEmailCode(){
	var emailCode = $("#emailCode");
	if($.trim(emailCode.val())==''){
		
		emailCode.addClass("errorRed");
		return false;
	}else{
		
		emailCode.removeClass("errorRed");
	}
	return true;
}

//�ֻ�ע��
//У���ֻ�����
var phoneNumberReg = /^1[3|4|5|8|7][0-9]\d{8,8}$/;
function  clickPhoneNumber(){
	var phone_number = $("#phone-number").val();
	if($.trim(phone_number)==''){

		//$("#phone-number").next("div").css({"display":"block"}).find("span").html("�ֻ����벻��Ϊ�գ�");
		$("#phone-number").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#phone-number").next("div").css({"display":"none"});
		return false;
	}else{
	
		//У���û����Ƿ��Ѿ�����
		if(!phoneNumberReg.test(phone_number)||check_usrNumber()){
		
			//$("#phone-number").next("div").css({"display":"block"}).find("span").html("�ֻ����벻��ȷ��");
			$("#phone-number").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
			$("#phone-number").next("div").css({"display":"none"});
			return false;
		}
	}
	$("#phone-number").next("div").css({"display":"none"});
	$("#phone-number").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'}); 
	return true;
}

//����
function clickPhoneUsrpwd(){
	
	var phone_usrPwd = $("#phone-usrPwd").val();
	if($.trim(phone_usrPwd)=='' || phone_usrPwd.length<6){

		//$("#phone-usrPwd").next("div").css({"display":"block"}).find("span").html("����Ų���Ϊ�գ�");
		$("#phone-usrPwd").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#phone-usrPwd").next("div").css({"display":"none"});
		return false;
	}
	$("#phone-usrPwd").next("div").css({"display":"none"});
	$("#phone-usrPwd").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'}); 
	return true;
}

function clickPhonePasswordconfirm(){
	var password = $("#phone-usrPwd").val();
	var phone_passwordconfirm = $("#phone-passwordconfirm").val();
	if($.trim(phone_passwordconfirm)==''||password!=phone_passwordconfirm){

		$("#phone-passwordconfirm").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#phone-passwordconfirm").next("div").css({"display":"none"});
		return false;
	}
	$("#phone-passwordconfirm").next("div").css({"display":"none"});
	$("#phone-passwordconfirm").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'});
	return true;
}

function clickPhoneCode(){
	
	var phoneCode = $("#phoneCode").val();
	if($.trim(phoneCode)=='' || $.trim(phoneCode).length!=6){

		$("#phoneCode").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#phoneCode").next("div").css({"display":"none"});
		return false;
	}
	$("#phoneCode").next("div").css({"display":"none"});
	$("#phoneCode").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'});
	return true;
}

//ʧȥ�����Ч��
$('#usrNumber').blur(function() {
	clickUserNumber();
}).focus(function(){
	var div = $("#usrNumber").next("div");
	var usrNum = $("#usrNumber").val();
	/*$(this).next().css({'top':'38px','opacity':'1','display':'block'});
	$(this).css({'background-color':'#fff','border':'1px solid #777777'});*/
	if($.trim(usrNum)==''){
		
		$(this).next().css({'top':'38px','opacity':'1','display':'block'});
		$(this).css({'background-color':'#fff','border':'1px solid #777777'});
		//$("#usrNumber").next("div").css({"display":"block"}).find("span").html("����/QQ�Ų���Ϊ�գ�");
		return false;
	}else{
		
		
			
		if(!emailReg.test(usrNum)){
				
				$("#usrNumber").next("div").css({"display":"block"}).find("span").html("���������ʽ����ȷ��");
				return false;
		}
		
		if(usrNum.length<6){
			
			$("#usrNumber").next("div").css({"display":"block"}).find("span").html("�������䳤��̫�̣�");
			return false;
		}
		
		if(check_usrEmailNumber()){
		
			$("#usrNumber").next("div").css({"display":"block"}).find("span").html("���������Ѿ���ע�ᣡ");
			return false;
		}
	}
	//$("#usrNumber").next("div").css({"display":"none"});
});



$('#usrPwd').blur(function() {
	
	clickPassword();
	if($.trim($("#passwordconfirm").val())!=''){
		
		clickPasswordconfirm();
	}
}).focus(function(){
	
	var div = $("#usrPwd").next("div");
	var userPwd = $("#usrPwd").val();
	
	if($.trim(userPwd)==''){
		$(this).next().css({'top':'38px','opacity':'1','display':'block'});
		$(this).css({'background-color':'#fff','border':'1px solid #777777'});

		//$("#usrPwd").next("div").css({"display":"block"}).find("span").html("����Ų���Ϊ�գ�");
		return false;
	}else{

		if(userPwd.length<6){
			
			$("#usrPwd").next("div").css({"display":"block"}).find("span").html("���볤������6λ�����16λ��");
			return false;
		}
	}
	//$("#usrPwd").next("div").css({"display":"none"});
	return true;
});

$('#passwordconfirm').blur(function() {
	
	clickPasswordconfirm();
}).focus(function(){
	
	var password = $("#usrPwd").val();
	var passwordconfirm = $("#passwordconfirm").val();
	if($.trim(passwordconfirm)==''){

		$(this).next().css({'top':'38px','opacity':'1','display':'block'});
		$(this).css({'background-color':'#fff','border':'1px solid #777777'});
		//$("#passwordconfirm").next("div").css({"display":"block"}).find("span").html("ȷ������Ų���Ϊ�գ�");
		return false;
	}else{

		if(password!=passwordconfirm){
			
			$("#passwordconfirm").next("div").css({"display":"block"}).find("span").html("ȷ����������벻һ�£�");
			return false;
		}
	}
	$("#passwordconfirm").next("div").css({"display":"none"});
});

$('#parityString').blur(function() {
	
	clickParityString();
}).focus(function(){
	
	
	var parityString = $("#parityString").val();
	if($.trim(parityString)==''){

		//$("#parityString").next("div").css({"display":"block"}).find("span").html("��֤�벻��Ϊ�գ�");
		$(this).next().css({'top':'38px','opacity':'1','display':'block'});
		$(this).css({'background-color':'#fff','border':'1px solid #777777'});
		return false;
	}else{
	
		if($.trim(parityString).length!=6){
		
			$("#parityString").next("div").css({"display":"block"}).find("span").html("��֤�볤�Ȳ���ȷ��");
			return false;
		}
	}
	$("#parityString").next("div").css({"display":"none"});
	
});

$("#emailCode").blur(function(){
	
	clickEmailCode();
});


//�ֻ�ע��
$('#phone-number').blur(function() {//�绰����
	
	clickPhoneNumber();
}).focus(function(){
	
	var phone_number = $("#phone-number").val();
	if($.trim(phone_number)==''){

		//$("#phone-number").next("div").css({"display":"block"}).find("span").html("�ֻ����벻��Ϊ�գ�");
		$(this).next().css({'top':'38px','opacity':'1','display':'block'});
		$(this).css({'background-color':'#fff','border':'1px solid #777777'});
		return false;
	}else{
	
		if(!phoneNumberReg.test(phone_number)){
		
			$("#phone-number").next("div").css({"display":"block"}).find("span").html("�ֻ������ʽ����ȷ��");
			return false;
		}
		
		if(check_usrNumber()){
			
			$("#phone-number").next("div").css({"display":"block"}).find("span").html("���˺��Ѿ���ע�ᣡ");
			return false;
		}
	}
	$("#phone-number").next("div").css({"display":"none"});
});

$('#phone-usrPwd').blur(function() {//����
	
	clickPhoneUsrpwd();
	if($.trim($("#phone-passwordconfirm").val())!=''){
		
		clickPhonePasswordconfirm();
	}
}).focus(function(){
	
	var phone_usrPwd = $("#phone-usrPwd").val();
	if($.trim(phone_usrPwd)==''){

		//$("#phone-usrPwd").next("div").css({"display":"block"}).find("span").html("����Ų���Ϊ�գ�");
		$(this).next().css({'top':'38px','opacity':'1','display':'block'});
		$(this).css({'background-color':'#fff','border':'1px solid #777777'});
		return false;
	}else{

		if(phone_usrPwd.length<6){
			
			$("#phone-usrPwd").next("div").css({"display":"block"}).find("span").html("���볤������6λ�����16λ��");
			return false;
		}
	}
	$("#phone-usrPwd").next("div").css({"display":"none"});
});

$('#phone-passwordconfirm').blur(function() {//ȷ������
	
	clickPhonePasswordconfirm();
}).focus(function(){
	
	var password = $("#phone-usrPwd").val();
	var phone_passwordconfirm = $("#phone-passwordconfirm").val();
	if($.trim(phone_passwordconfirm)==''){

		$(this).next().css({'top':'38px','opacity':'1','display':'block'});
		$(this).css({'background-color':'#fff','border':'1px solid #777777'});
		return false;
	}else{

		if(password!=phone_passwordconfirm){
			
			$("#phone-passwordconfirm").next("div").css({"display":"block"}).find("span").html("ȷ����������벻һ�£�");
			return false;
		}
	}
	$("#phone-passwordconfirm").next("div").css({"display":"none"});
});

// $('#phoneCode').blur(function() {//��֤��
	
// 	clickPhoneCode();
// }).focus(function(){
	
	
// 	var phoneCode = $("#phoneCode").val();
// 	if($.trim(phoneCode)==''){

// 		$(this).next().css({'top':'38px','opacity':'1','display':'block'});
// 		$(this).css({'background-color':'#fff','border':'1px solid #777777'});
// 		return false;
// 	}else{
	
// 		if($.trim(phoneCode).length!=6){
		
// 			$("#phoneCode").next("div").css({"display":"block"}).find("span").html("��֤�볤�Ȳ���ȷ��");
// 			return false;
// 		}
// 	}
// 	$("#phoneCode").next("div").css({"display":"none"});
// });




$('#submitForm').click(function() {
		qqOrEmailRegist();
});


$('#submitMobileForm').click(function() {
	mobileRegist();
});

//QQ��������ע��
function qqOrEmailRegist(){
	
	//�������
	if(clickUserNumber() && clickPassword() && clickPasswordconfirm()){
		if(!clickParityString()){
			
			return false;
		}
		$("#submitForm").addClass("disabled");
		$.ajax({
		   type: "POST",
		   url: "regist!regist.go",
		   data: $("#regform").serialize(),
		   dataType:"json",
		   success: function(data){
				//�����֤��
				$("#imgParity").click();
				if(data.result==1){
					
					$("#submitForm").removeClass("disabled");
					$("#parityString").val('');
					alert(data.message);
				}else{
					
					window.location.href="regist!registSuccess.go";
				}
		   }
		});
	}
}

/**
 * ��鶣�����Ƿ����
 */

function check_usrNumber(){
	var usrNumberExists=false;
	var phone_number = $("#phone-number").val();
	// $.ajax({
	// 	   type: "POST",
	// 	   url: "regist!check_usrNumber.go",
	// 	   data: {usrNumber:$("#phone-number").val()},
	// 	   dataType:"json",
	// 	   async:false,
	// 	   success: function(data){
	// 		   if(data.result==1){
	//
	// 			   //$("#phone-number").next("div").css({"display":"block"}).find("span").html("���˺��Ѿ���ע�ᣡ");
	// 			   usrNumberExists = true;
	// 		   }else{
	//
	// 			   usrNumberExists = false;
	// 			}
	// 	  }
	// 	});
	// return usrNumberExists;
	return 0;
}
/**
 * ��������Ƿ����
 * @return {TypeName} 
 */
function check_usrEmailNumber(){
	var usrNumberExists=false;
	var usrNumber = $("#usrNumber").val();
	$.ajax({
		   type: "POST",
		   url: "regist!check_usrNumber.go",
		   data: {usrNumber:$("#usrNumber").val()},
		   dataType:"json",
		   async:false,
		   success: function(data){
			   if(data.result==1){
								
				   //$("#phone-number").next("div").css({"display":"block"}).find("span").html("���˺��Ѿ���ע�ᣡ");
				   usrNumberExists = true;
			   }else{
					
				   usrNumberExists = false;
				}
		  }
		});
	return usrNumberExists;
}

//�ֻ�ע��
function mobileRegist(){

	//�������
	if(clickPhoneNumber() && clickPhoneUsrpwd() && clickPhonePasswordconfirm()){
		// if(!clickPhoneCode()){
			
		// 	return false;
		// }
		$("#submitMobileForm").addClass("disabled");
		$.ajax({
		   type: "POST",
		   url: "?/user/phone_reg",
		   data: {usrNumber:$("#phone-number").val(),usrPwd:$("#phone-usrPwd").val()},//,parityString:$("#phoneCode").val()
		   dataType:"json",
		   success: function(data){
				//�����֤��
			   if(data.code == 200){
                   if(data.newbie==0){
                       $("#phone-number").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
                       $("#phone-number").next("div").css({"display":"none"});
                       $("#submitMobileForm").removeClass("disabled");
                       //$("#phoneCode").val('');
                       $('#imgVerify').click();
                       alert('手机号已注册');
                   }else if(data.newbie==1){
                       window.location.href="?";
                   }
			   }
		   }
		});
	}
}

var timer2;
$("#emailBtn").click(function(){
	if(!clickUserNumber()){
		
		return ;
	}
	
	if(timer2==null){
		
		//����������֤��
		sendEmailCode();
	}
	
	
});

/**
 * ����������֤��
 */
function sendEmailCode(){
	
	timer2 = setInterval("timeCountEmail()",1000);
	$.ajax({
		   type: "POST",
		   url: "regist!sendRegistEmail.go",
		   data: "msgEmail="+$("#usrNumber").val(),
		   async: false,
		   dataType:'json',
		   success: function(data){
			
				if(data.messageCode=='A001'){
					
					alert(data.message);
				}
		   }
		});
}

/**
 * ��֤��
 */
function clickValidCode(){
	var parityStringIphone = $("#parityStringIphone").val();
	if($.trim(parityStringIphone)==''){
		
		$("#parityStringIphone").css({'background-color':'rgb(252, 229, 229)','border':'1px solid rgb(253, 173, 173)'});
		$("#parityStringIphone").next("div").css({"display":"none"});
		return false;
	}
	$("#parityStringIphone").next("div").css({"display":"none"});
	$("#parityStringIphone").css({'background-color':'rgb(255, 255, 255)','border':'1px solid rgb(119, 119, 119)'});
	return true;
	
}

$('#parityStringIphone').blur(function() {//��֤��
	
	clickValidCode();
}).focus(function(){
	
	
	var parityStringIphone = $("#parityStringIphone").val();
	if($.trim(parityStringIphone)==''){

		$("#parityStringIphone").next().css({'top':'38px','opacity':'1','display':'block'});
		$("#parityStringIphone").css({'background-color':'#fff','border':'1px solid #777777'});
		return false;
	}
	$("#parityStringIphone").next("div").css({"display":"none"});
});

//���Ͷ�����֤
var timer;
function sendCode(){
	if(!clickPhoneNumber()||!clickValidCode()){
		
		return;
	}

	if(timer==null){
		
		sendData();
	}
	
}

//ʱ��100��
var time2 = 61;
//����ʱͳ��
function timeCountEmail(){
	time2--;
	$("#emailBtn").html(time2+"����ȡ");
	//���õ����ť
	$("#emailBtn").addClass("disable").removeClass("able");
	if(time2==0){
	
		//���õ����ť
		$("#emailBtn").addClass("able").removeClass("disable");
		//�����ʱ��
		clearInterval(timer2);
		timer2=null;
		$("#emailBtn").html("��ȡ��֤��");
		
		//��ԭ����
		time2=61;
		veryCode='';
	}
}

//ʱ��100��
var time = 61;
//����ʱͳ��
function timeCount(){
	time--;
	$("#moblieBtn").html(time+"����ȡ");
	//���õ����ť
	$("#moblieBtn").addClass("disable").removeClass("able");
	if(time==0){
	
		//���õ����ť
		$("#moblieBtn").addClass("able").removeClass("disable");
		//�����ʱ��
		clearInterval(timer);
		timer=null;
		$("#moblieBtn").html("��ȡ��֤��");
		
		//��ԭ����
		time=61;
		veryCode='';
	}
}

/**
 * ������֤��
 */
function sendData(){
	
	$.ajax({
		   type: "POST",
		   url: "regist!sendMessage.go",
		   data: {usrNumber:$("#phone-number").val(),parityString1:$("#parityStringIphone").val()},
		   dataType:"json",
		   success: function(data){
				//�����֤��
				$("#imgPhoneParity").click();
				if(data.result==1){
					//�����֤��
					$("#imgVerify").click();
					$("#parityStringIphone").val('');
					alert(data.message);
				}else{
					
					timer = setInterval(timeCount,1000);
				}
		   }
		});
}
