<!DOCTYPE html>
<!-- saved from url=(0031)http://www.dindin.com/login.jsp -->
<html dir="ltr" lang="en"><!--<![endif]--><head><meta http-equiv="Content-Type" content="text/html; charset=GBK">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>用户登录</title>
    <!--<base href="http://velikorodnov.com/opencart/shopme/demo6/">--><!--<base href=".">--><base href=".">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="http://www.dindin.com/dindinv2Images/favicon.ico" rel="icon">
    <link rel="icon" href="http://www.dindin.com/dindinv2Images/favicon.ico" type="image/x-icon">
    <!-- Version 2.0.3 -->
    <!-- <script id="facebook-jssdk" src="http://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.3&appId=109031762530738"></script> -->
    <script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="./js/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
    <link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="./css/responsive.css">
    <!-- Custom css -->
    <!-- Custom script -->

    <!-- Custom styling -->
    <!-- Custom fonts -->
    <!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,900" media="screen"> -->
    <style>
        .branch-card-title{border: 1px solid #B4D8F8;padding: 10px;background: #fff;cursor:pointer;color: #777777;position:relative;}
        .icon-sanjiao{position:absolute;top:5px;right:10px;width:15px;height:15px;border:1px solid #B4D8F8;background:#fff;transform:rotate(43deg);
            -moz-transform:rotate(43deg);-webkit-transform:rotate(43deg);-ms-transform:rotate(43deg);-o-transform:rotate(43deg);border-top: none;border-left: none;}
        .icon-sanjiao.up{top:15px;border-bottom: none;border-right: none;border-top:1px solid #B4D8F8;border-left:1px solid #B4D8F8;}
    </style>
</head>
<body class="account-register style-4 ">
<!-- Cookie Control -->

<!-- Old IE Control -->
<div class="outer_container">
    <!-- header 部分 -->

    <div id="notification" class="container"></div>
    <div class="container">

        <div class="row">



            <div id="content" class="col-md-12 col-sm-12">

                <style>
                    a [class*="dsl-icon-"],
                    [class*="dsl-icon-"],
                    [class*="dsl-icon-"]:before{
                        margin:0px !important;
                        background-image:none !important;
                    }

                    #submitMobileForm {
                        width: 100%;
                        max-width: 215px;
                        height: 41px;
                        background: #28ACFD;
                        border-radius: 0;
                    }

                    .dsl-label {
                        display: none;
                        vertical-align: middle;
                    }
                    .dsl-label-icons{
                        display: inline-block;
                    }
                    .dsl-hide-icon{
                        opacity: 0
                    }
                    .errorRed{border:1px solid rgb(243, 170, 170);}
                    #loginForm input{max-width:535px;}
                    .radio-list{padding-top:7px;}
                    .radio-list input{vertical-align: bottom;}
                    .services .ser-img, .services .ser-text {
                        float: left;
                    }
                    .services .ser-item{width:140px;float:left;}
                    .services .ser-item:nth-child(3){width:108px;}
                    .services .ser-item .ser-img{margin-right:3px;}
                    .services .ser-text small{color:#676767;}
                    .register-title h3{padding:0;}
                    .register-title a{text-decoration: underline;}
                    /*
                    动态是使用#regform .code{width:61%;float:left;margin-right:3%;}
                    下面两行删除
                    */
                    #loginForm .code{width:54%;float:left;margin-right:7%;}
                    .login-button{width:54%;}
                    #loginForm .phone-input .code{width:61%;float:left;margin-right:3%;}

                    #loginForm .form-group{height:65px;margin-bottom:0;position:relative;}
                    #loginForm .form-group a{color:#0f78b9;}
                    #loginForm .form-group .error-message{position:absolute;left:0;top:48px;border:1px solid rgb(226, 139, 55);border-radius:4px;color:#777777;margin-top: 3px;font-size: 13px;
                        padding:4px;z-index:99;background:#fff;display:none;opacity:0;transition:all 1s;-webkit-transition:all 1s;-moz-transition:all 1s;-o-transition:all 1s;}

                    #loginForm .form-group input{border-radius:0;background-color:#F6FCFF;border:1px solid #8dd3f9;}
                    #loginBtn {width:100%;max-width: 215px;height: 41px;background: #28ACFD;border-radius: 0;}
                    #loginForm .form-group .error-message i{position:absolute;left:10px;top:-3px;width:5px;height:5px;border:1px solid rgb(226, 139, 55);background:#fff;transform:rotate(45deg);
                        border-bottom:none;border-right:none;}
                    #loginForm .form-group .pwd-grade{margin-top: 5px;font-size: 13px;color: #666;}
                    #loginForm .form-group .pwd-grade .grade{display:none;width:25px;height:8px;margin-right:5px;background:#aaaaaa;color:#809a2d;}
                    #loginForm .form-group .pwd-grade .grade-text{display:none;}
                    .form-item .lefts img{width:100%;max-width:446px;}
                    .form-item .rights{padding-top: 70px;width:100%;max-width:350px;}
                    .phone-input{display:none;}
                    .phone-input .button-code{padding: 8px 23px 9px;}
                    .phone-input .button-code.disable{background:#ccc;cursor:no-drop;}
                    .phone-input .button-code.able{background:#72AF49;cursor:pointer;color:#fff;}
                    .err-msg{color: rgb(255, 123, 123);font-size: 13px;margin-top: 5px;display:none;}
                    .form-bo{font-size:13px;}
                    .no-padding-left{padding-left:0;}
                    .codechange span{margin-left:5px;font-size:13px;}
                    .register-title h4{font-weight:bold;}
                    @media screen and (max-width:991px){
                        .form-item .rights{padding-top:0;}
                    }
                    @media screen and (max-width:767px){
                        .form-item{padding-right:15px;}
                    }
                    @media screen and (max-width:615px){
                        .form-item{padding-left:5px;}
                        .form-item .lefts{display:none;}
                        .form-item .rights{float: none!important;margin: 0 auto;}
                    }
                    @media screen and (max-width:374px){
                        #loginForm .code{margin-right:3%;}
                        .phone-input .button-code{padding: 8px 11px 9px;}
                    }
                    @media screen and (max-width:345px){
                        #loginForm .code{width:40%;}
                        .login-button{width:40%;}
                    }
                </style>


                <div class="row">
                    <div class="col-md-4 col-sm-4"><a href="http://www.dindin.com/"><img src="./image/dindin_logo1.png"></a></div>
                    <div class="pull-right servic mobile_hide" style="padding-top: 30px;">
                        <div class="row services">
                            <div class="ser-item">
                                <div class="ser-cont">
                                    <a href="http://www.dindin.com/insurance.jsp">
                                        <div class="ser-img"><img src="./image/zpbz.png" width="30"></div>
                                        <div class="ser-text">
                                            <div><strong>正品保障</strong></div>
                                            <div><small>海外原产地采购</small></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="ser-item">
                                <div class="ser-cont">
                                    <a href="http://www.dindin.com/insurance.jsp">
                                        <div class="ser-img"><img src="./image/hgjg.png" width="30"></div>
                                        <div class="ser-text">
                                            <div><strong>海关监管</strong></div>
                                            <div><small>全程海关监管查验</small></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="ser-item">
                                <div class="ser-cont">
                                    <a href="http://www.dindin.com/insurance.jsp">
                                        <div class="ser-img"><img src="./image/wlps.png" width="30"></div>
                                        <div class="ser-text">
                                            <div><strong>物流派送</strong></div>
                                            <div><small>7-15天送达</small></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="ser-item">
                                <div class="ser-cont">
                                    <a href="http://www.dindin.com/insurance.jsp">
                                        <div class="ser-img"><img src="./image/zjaq.png" width="30"></div>
                                        <div class="ser-text">
                                            <div><strong>资金安全</strong></div>
                                            <div><small>专业第三方支付</small></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  <p class="margin-b">如果您已经有帐户，请点击登录 <a href="http://velikorodnov.com/opencart/shopme/demo6/index.php?route=account/login">login page</a>.</p> -->
                <div class="row form-item" style="padding-top: 50px;">
                    <div class="col-md-7 col-lg-7 col-sm-6 col-xs-5 lefts" style="text-align: center;">
                        <a target="_blank" href="http://www.dindin.com/goodsDetail_id_11647.htm"><img src="./image/register-img.jpg"></a>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-5 col-xs-7 pull-right1 rights">
                        <div class="row register-title">
                            <h4 class="col-md-12 col-lg-12 col-sm-12 col-xs-12 no-padding-left">已有帐号，请登录</h4>

                        </div>
                        <form id="loginForm" method="post" class="form-horizontal" name="loginForm" action="?user/login">
                            <div class="form-group">

                                <input type="text" name="u" id="u" value="" placeholder="邮箱/手机号" class="form-control" maxlength="25">
                                <div class="err-msg">

                                    登陆名不能为空！

                                </div>
                            </div>
                            <div class="form-group">
                                <input type="password" name="p" id="p" placeholder="请输入6-16位长度的密码" class="form-control" maxlength="16">
                                <div class="err-msg">密码不能为空！</div>

                            </div><!--
			        <div class="form-group">
			        	<div class="code"><input type="text"  name="verifycode" id="verifycode" maxlength="4" placeholder="请输入验证码" class="form-control"/><div class="err-msg">验证码不能为空！</div></div>
			        	<div><a class="codechange"  title="看不清?换一张" onclick="$('#imgParity').attr('src','./createParityImage.jsp?t='+(new Date().getTime()));return false;"><img id="imgParity" src="createParityImage.jsp" width="88" /><span>换一张</span></a></div>
			        	<div class="clearfix code-msg"></div>
			        	
			        </div> 
			        --><div class="form-group">
                                <div class="col-md-8 col-lg-8 col-sm-8 col-xs-7 login-button" style="padding: 0;"><button class="btn btn-primary" type="button" id="loginBtn">登录</button></div>
                                <div class="col-md-4 col-lg-4 col-sm-4" style="text-align:right;padding:0;line-height: 56px;">
                                </div>
                            </div><!--
			        <input type="hidden" name='jump_url' id="jump_url" value="" >
			        --><div class="form-group form-bo">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding-left" style="display: block;"><input type="checkbox" id="rememberUserName" name="rememberUserName" value="1"> 记住用户名</div>
                                <div class="pull-right"> <a href="?/user/register">免费注册</a></div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer" style="border-top: 1px solid #ccc;margin-top: 50px;padding-top: 20px;">
        <div class="container text-center">

            <div id="footer_payment_icon">
                <!--<a href="http://si.trustutn.org/info?sn=975150608014227389852"><img src="https://apply.trustutn.org/images/cert/bottom_large_img.png" width="114" alt=""></a>
                --><a href="https://cert.ebs.gov.cn/1d977c68-da31-4817-9e2b-082cad56a9be"><img src="./image/bluelogo1.gif" alt="" width="134"></a>
                <a href="https://credit.szfw.org/CX20151013011602820168.html"><img src="./image/footer_img_3.jpg" width="114"></a>
                <a href="http://www.ectrust.cn/seal/splash/2000007.htm"><img src="./image/bluelogo.jpg" alt="" width="134"></a>
                <a href="http://www.dindin.com/show_page12.jsp"><img src="./image/xfzzycn.jpg" alt="" width="114"></a>
            </div>

            <div id="powered" style="line-height: 24px;"><div>叮叮网版权所有&#169; 2007-2018年 </div><div>工信部备案号: <a href="http://www.miitbeian.gov.cn/">闽ICP备17003323号-1</a></div></div>
        </div>
    </div>

    <!-- footer 部分 -->

</div>  <!-- .outer_container ends -->
<!-- Resources dont needed until page load -->
<script type="text/javascript" src="./js/jquery.cookie.js"></script>
<script>
    var jump_url = "";
    $( document ).ready(function() {
        $("#loginBtn").click(function(){
            var userName = $("#u").val();
            var password = $("#p").val();
            var verifycode = $("#verifycode").val();
            //var errorMsg = $(".err-msg");
            //清楚错误信息
            //errorMsg.html("");	
            if($.trim(userName)==''){

                checkInput($("#u"));
                $('#u').next().html("登陆名不能为空！");
                return;
            }
            if($.trim(password)==''){
                checkInput($("#p"));
                $('#p').next().html("密码不能为空！");
                return ;
            }
            /*if($.trim(verifycode)==''){
             checkInput($("#verifycode"));
             $('.code-msg').next().html("验证码不能为空！");
             return ;
             }*/

            $("#loginBtn").addClass("disabled");
            //提交登陆
            $.ajax({
                type: "POST",
                dataType:'json',
                async: false,
                data:"phone="+userName+"&password="+password+"&ajax=1",
                url:"?/user/login",
                success: function(data){
                    if(data.code == 200){
                        location.href = "?/index/";
                    }else{
                        $("#loginBtn").removeClass("disabled");
                        alert(data.msg);
                    }
            }});
        });
    })

    function getUrlParam(name)
    {

        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象

        var r = window.location.search.substr(1).match(reg); //匹配目标参数

        if (r!=null) return unescape(r[2]); return null; //返回参数值

    }


    $('#loginForm  input').focus(function(){
        $(this).css({'background-color':'#fff','border':'1px solid #777777'});

    }).blur(function(ev){
        $(this).css({'background-color':'#F6FCFF','border':'1px solid #8dd3f9'});

    })
    $('#u').blur(function(){
        checkInput($(this));
    })
    $('#p').blur(function(){
        checkInput($(this));
    })

    $('#verifycode').blur(function(){
        checkInput($(this));
    })

    function checkInput(obj){
        //if(obj=='u'){
        if(obj.val()==''){
            obj.next().show();
            obj.css({'border':'1px solid rgb(255, 123, 123)','background-color':'rgb(252, 229, 229)'})

        }else{
            obj.next().hide();
            obj.css('border','1px solid rgb(141, 211, 249)')
        }
        //}

    }
</script>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

</body></html>