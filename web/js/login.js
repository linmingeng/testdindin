
function userLogin(){};
	var callbackFunction = null;
	userLogin.prototype.isNext = false;
	userLogin.getLogin=function(user){ //tag每种情况所带标识，0：若登录，将用户名添加到头部，1：若未登录，弹出框让用户登录，2：若未登录，弹出框让用户登录，并跳到下一步
	//登录验证
		//console.log(user.username+'---'+$.md5("123456"))
		// var pwd = $.md5(user.userpwd);
		//console.log(pwd)
			$.ajax({
				type:"POST",
				async:false,
				// url:"./loginAction!login.go",
                url:"?user/login",
				data:{phone:user.username,password:user.userpwd,code:user.verifycode,ajax:1},
				
				success:function(data){
					// var data = (eval('(' + data + ')'));
					if(data.code==200){
						//var islogin = islogin();
						
						//if(islogin()!=''){ //检查是否登录以获取用户名
							//$('#colorbox').hide();
							//$('#cboxOverlay').css('opacity','0').hide();
							//alert(islogin())
							//$('#user_nick').text(islogin());
						//}
						$.ajax({
							type:"POST",
							// url:"./loginAction!isLogin.go",
                            url:"?user/check",
							data:{ajax:1},
							// data:{u:$('.username').val(),p:$.md5($('.userpwd').val()),verifycode:$('.verifycode').val()},
							success:function(datas){
								// var datas = (eval('(' + data + ')'));
								if(datas.is_login==1){
									//alert(datas.nick_name)
									//return datas.nick_name;
									$('#colorbox').hide();
									$('#cboxOverlay').css('opacity','0').hide();
									//alert(islogin())
									$('#nologin').hide();
									$('#user_nick').text(data.profile.nickname).attr('title',data.profile.nickname);
									$('#user_login').show();
									//alert(isNextObj)
									//if(isNextObj){
										//location.href=('../buyAction!buySingle.go?id=7294&buynum=1');
									//}
									if(callbackFunction!=null){
										$(callbackFunction.action());
									}
								}else{
									//return '';
								}
							}
						})
					}else{
						
						$('.error-message').text(data.msg);
						$('#imgVerify').attr('src','./createParityImage.jsp?'+Math.random()); //刷新验证码
						//alert($('.error-message').text());
					}
				},
				//dataType: 'json',
				error:function(XMLResponse){
					//alert(arguments[1])
				}
			})
	
}


//检查是否登录
userLogin.isLogin=function(){
	var mss = '';
	$.ajax({
		async:false,
		type:"POST",
		url:"?user/check",
		//data:{u:user.username,p:user.userpwd,verifycode:user.verifycode},
		success:function(data){
			if(data.is_login == '1'){
				mss = data.user_info.nickname;
				
				//如果为已登陆状态,回调函数置空
				callbackFunction = null;
			}

		},
		dataType:'json'
	})
	return mss;
}

//检查是否登录，未登录则弹出登录框
userLogin.loginAlert=function(callback){
	if(!userLogin.isLogin()){
		//未登陆状态,给回调函数赋值;登陆成功后，调用回调函数.
		callbackFunction = callback;
		
		$('.loginbox').fadeIn();
    	$('#cboxOverlay').css('opacity', '0.9').fadeIn('slow');
	}else if(userLogin.isLogin()){
		//回调函数置空
		callbackFunction = null;
		return true;
	}
}

//退出登录
userLogin.logout=function(){

	$.ajax({
		async:false,
		type:"GET",
		url:"?user/logout",
		success:function(data){
			//alert(0)
			//$('#nologin').show();
			//$('#user_login').hide();
			location.href="";
		}
	})
}
