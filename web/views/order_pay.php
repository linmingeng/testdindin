<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- saved from url=(0048)http://www.dindin.com/buyAction!newRefreshPay.go -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=GBK">


    <title>叮叮币收银台</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
    <meta http-equiv="description" content="This is my page">
    <link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
    <link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="./css/responsive.css">
    <script type="text/javascript" async="" src="./js/mv.js"></script><script type="text/javascript" async="" src="./js/mba.js"></script><script type="text/javascript" async="" src="./js/mvl.js"></script><script src="./js/hm.js"></script><script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="./js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="./js/shopme_common.js"></script>
    <script type="text/javascript" src="./js/load_data.js"></script>
    <script type="text/javascript" src="./js/cart.js"></script>
    <script type="text/javascript" src="./js/login.js"></script>
    <style>
        .clr:after{content:'';display:block;height:0;clear:both;}
        .f-left{float:left;}
        .f-right{float:right;}
        .ddb-tit{padding:34px 35px 0px;border-bottom:2px solid #ccc;}
        .ddb-tit .pull-right{margin-top:31px;}
        .ddb-tit h3{display:inline-block;}
        .ddb-tit .bg{padding: 3px 49px;}
        .ddb-tit .bg span,.ddb-tit .tx span{display:inline-block;}
        .ddb-tit .bg .round{width:10px;height:10px;border-radius:100%;border:2px solid #e41436;vertical-align: sub;}
        .ddb-tit .bg .line{width:41%;height:3px;background:#e41436;}
        .ddb-tit .tx span{text-align:center;padding:5px 20px;color:#e41436;}
        .ddb-cont{padding: 25px 60px 4px 60px;background: #fff;border: 1px solid #E6E5E5;margin-bottom: 15px;}
        .ddb-cont .pro-list{padding:25px 0;border-top:1px solid #E6E5E5;}
        .ddb-cont .pro-list .img img{border:1px solid #ccc;}
        .ddb-cont .pro-list .pri{padding-top:35px;color:#ff0042;font-weight:bold;width:18%;}
        .ddb-cont .pro-list .pri span{font-size:25px;}
        .ddb-cont .pro-list .img{width:10%;margin-right:2%;}
        .ddb-cont .pro-list .tex{width:68%;padding-top:12px;}
        .ddb-cont .pro-list .tex p:nth-child(1){font-size:18px;color: #424242;}
        .pay-type h4{color:#ff0042;font-weight:bold;height:36px;border-bottom:1px solid #ccc;font-size:16px;}
        .pay-type h4 span{font-size:25px;}
        .pay-type .type-logo{margin-top:10px;margin-bottom:20px;overflow: hidden;}
        .pay-type .type-logo img{margin-right:20px;}
        .pay-type .type-logo>div{margin-bottom:10px;}
        .pay-type .pay-pwd{/*background:url(./images/pay_bg.jpg) no-repeat;*/padding:5px 0px;border:1px solid #ccc;width:212px;border-radius:3px;}
        .pay-type .pw{width:35px;height:26px;line-height:26px;float:left;border-right:1px solid #ccc;}
        .pay-type .pw:last-child{border:none;}
        .pay-type .pw input{border:none;background: transparent;font-size: 16px;width:100%;height:100%;padding:0 0 0 12px;}
        .p-pwd a{color:#018bc8;margin-left:10px;}
        .p-pwd div{margin-bottom:10px;}
        .p-pwd div input{height:auto;}
        .pay-btn a{color:#fff;display:inline-block;padding:5px 30px;background:red;}
        .pay-btn{margin-bottom:15px;}
        .type-logo.row1 div{padding-left:0;}
        .type-logo.row1 div a.act{border:1px solid #FD0101;}
        .type-logo.row1 div a img.icons{display:none;position:absolute;right:0;bottom:0;border:none;margin-right:0;}
        .type-logo.row1 div a{display:block;width:100%;position:relative;border: 1px solid #ccc;}
        .type-logo.row1 div a.act .icons{display:block;}
        .ddb-pay-box{width: 100%;border-top: 1px solid #D8D4D4;padding: 10px;position:relative;padding-left:0;display:none;}
        .ddb-pay-box .small-div{position:absolute;left:770px;top:-6px;z-index:99;width:10px;height:10px;background:#fff;border-left:1px solid #D8D4D4;
            border-bottom:1px solid #D8D4D4;transform:rotate(134deg);}
        .err-msg{color:#fff;margin: 10px 10px;text-align: center;min-height: 20px;}
        @media screen and (max-width:1200px){
            .ddb-cont .pro-list .tex{padding-top:0;}

        }
        @media screen and (max-width:991px){
            .ddb-cont .pro-list .pri{width:100%;margin-top:5px;padding-top:0;}
            .ddb-cont .pro-list .pri span{font-size:22px;}
            .ddb-cont .pro-list .tex p:nth-child(1){margin-bottom:3px;font-size:16px;}
        }
        @media screen and (max-width:666px){
            .ddb-tit .pull-right{display:none;}
            .ddb-cont .pro-list .img{width:15%;padding-top:3%;}
            .ddb-cont h3{font-size:15px;}
            .ddb-cont .pro-list .tex p:nth-child(1){font-size:14px;}
            .ddb-cont{padding:25px 16px 4px 29px;}
        }
        @media screen and (max-width:450px){
            .ddb-cont .pro-list .img{width:100%;padding-top:0;text-align:center;margin-bottom:5px;}
            .ddb-cont .pro-list .tex{width:100%;}
            .ddb-cont .pro-list{padding:13px 0;}
            .ddb-tit{padding: 18px 35px 0px;}
            .ddb-tit h3{font-size:17px;}
            .ddb-tit img{width:45px;}
            .tit-text{line-height: 22px;}
            .tit-text img{width:20px;}
            .pay-type h4 span{font-size:20px;}
            .pay-type h4{font-size:14px;}

        }
    </style>
    <script type="text/javascript">

        function gotoPay(){

            var isSuccess = false;
            var type=$("#payChannels").val();
            if(type==1){
                $("#orderPayForm").attr("action","buyAction!pay.go");
                $("#orderPayForm").submit();
            }else{
                //var num = $("#couponPayNumber").val();
                //var pwd = $("#couponPayPwd").val();
                //if(num==''||num == null){
                //$('.err-msg').text('卡号不能为空');
                //return ;
                //}
                //if(pwd==''||pwd == null){
                //$('.err-msg').text('密码不能为空');
                //return ;
                //}
                if(parseFloat($('.orderAmount').text())>parseFloat($('.ye-amount').text())){
                    $('.order-tip').show();
                    window.setTimeout(function(){
                        $('.order-tip').hide();
                    },5000)
                    return;
                }else{
                    $('.order-tip').hide();
                }
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url:"buyAction!dindinCouponPay.go",
                    data:$('#orderPayForm').serialize(),// 要提交的表单
                    success:function(data){
                        $("#cboxOverlay,#cboxOverlay .overlaytext").css("display","none");
                        var msg = data.message;
                        var ret = data.ret;
                        if(msg=="SUCCESS"){
                            isSuccess = true;
                            location.href="http://www.dindin.com:80/wyfinish.jsp";
                        }else{

                            isSuccess = false;
                            $('.err-msg').text(msg);
                        }
                    },
                    beforeSend:function(){
                        $("#cboxOverlay,#cboxOverlay .overlaytext").css("display","block");
                    },
                    error:function(e){
                        isSuccess = false;
                        alert("支付失败!");
                    }
                });

            }
            return isSuccess;
        }
        function chooicePayType(type,obj){
            if(type==1){
                $('.ddb-pay-box').hide();
            }else{
                $('.ddb-pay-box').show();
            }
            $(obj).addClass('act').parent().siblings().find('a').removeClass('act');
            $("#payChannels").val(type);
        }
    </script>
</head>

<body>
<div class="outer_container" id="cont-container">

    <?php include './views/head.php';?>



    <div class="container">
        <div class="ddb-tit">
            <h3><img src="./image/ddb_img1.png"> &nbsp;&nbsp;叮叮收银台</h3>
            <div class="pull-right">
                <div class="bg">
                    <span class="round"></span><span class="line"></span><span class="round"></span><span class="line"></span><span class="round"></span>
                </div>
                <div class="tx">
                    <span>我的购物车</span>
                    <span>提交订单</span>
                    <span>选择支付方式</span>
                </div>
            </div>
        </div>
        <div class="ddb-cont">
            <h3 class="tit-text"><img src="./image/suc.png" width="28" style="vertical-align:sub;"> 订单提交成功，现在只差最后一步啦！</h3>


        </div>
        <div class="ddb-cont pay-type">
            <div class="row">
                <h4>实付金额：<span>￥<span class="orderAmount"><?php echo $ret['price'];?></span></span></h4>
                <b style="margin-bottom:10px;">选择支付方式：</b>
                <div class="type-logo row1">
                    <!--<div class="col-md-2 col-sm-3 col-xs-5"><a class="act" href="javascript:void(0);" onclick="chooicePayType(10,this)"><img src="./images/pay_ddb.jpg"/><img class="icons" src="./images/pay_ddb-icon_03.png"/></a></div>
                    -->
                    <div class="col-md-2 col-sm-3 col-xs-5"><a class="act" href="javascript:void(0);" onclick="chooicePayType(1,this)"><img src="./image/pay_ddbill.jpg"><img class="icons" src="./image/pay_ddb-icon_03.png"></a></div>
                    <div class="col-md-2 col-sm-3 col-xs-5"><a href="javascript:void(0);" onclick="chooicePayType(1,this)"><img src="./image/pay_alipay.jpg"><img class="icons" src="./image/pay_ddb-icon_03.png"></a></div>
                    <div class="col-md-2 col-sm-3 col-xs-5"><a href="javascript:void(0);" onclick="chooicePayType(1,this)"><img src="./image/pay_weixin.jpg"><img class="icons" src="./image/pay_ddb-icon_03.png"></a></div>
                    <!--<div class="col-md-2 col-sm-3 col-xs-5"><a href="javascript:void(0);" onclick="chooicePayType(1,this)"><img src="./images/pay_ddbill.jpg"/><img class="icons" src="./images/pay_ddb-icon_03.png"/></a></div>
                    --><div class="col-md-2 col-sm-3 col-xs-5"><a href="javascript:void(0);" onclick="chooicePayType(1,this)"><img src="./image/pay_yinlian.jpg"><img class="icons" src="./image/pay_ddb-icon_03.png"></a></div>




                    <div class="col-md-2 col-sm-3 col-xs-5"><a href="javascript:void(0);" onclick="chooicePayType(10,this)"><img src="./image/pay_ddb.jpg"><img class="icons" src="./image/pay_ddb-icon_03.png"></a></div>



                </div>
                <div class=""></div>
                <style>

                    .ddb-amount{color:#ed7d05;font-size:18px;margin-bottom:25px;}
                    .ddb-amount span{font-size:30px;}
                    .ddb-amount a.add-quan{color: #049CE7;border: 1px solid #8DC7F5;margin-left: 5%;padding: 3px 9px;transition:all .5s ease;font-size: 15px;}
                    .ddb-amount a.add-quan i{margin-right:3px;}
                    .ddb-amount a.add-quan:hover{color:#ed7d05;border: 1px solid #ed7d05;}
                    .quan-list ul{overflow:hidden;}
                    .quan-list ul li{background:#eeeeee;border-radius:4px;width:32%;margin-right:1%;float:left;padding:20px 24px 20px 20px;overflow:hidden;position:relative;
                        margin-bottom:15px;}
                    .quan-list ul li .closes{position:absolute;right:2px;top:4px;z-index:9;width:20px;height:20px;cursor:pointer;}
                    .quan-list ul li .d-box{background:url(./images/ddb_pay_list_bg.png) no-repeat;background-size:100% 100%;color:#fff;padding:6px 10px;
                    }
                    .quan-list ul li .d-box>div{border:1px dashed #fff;}
                    .quan-list ul li .d-box .d-left{width:64px;font-size:21px;float:left;padding:16px 11px 10px 20px;border-right:1px dashed #fff;position:relative;}
                    .quan-list ul li .d-box .d-right{font-size:18px;float:right;padding: 20px 12px 14px 6px;}
                    .quan-list ul li .d-box .d-right p{/*color:#fff;*/}
                    .quan-list ul li .d-box .d-right .juan-yue{font-size:14px;}
                    .quan-list ul li .d-box .d-right .juan-yue span{font-size:40px;}
                    .quan-list ul li .d-box .d-left i{position:absolute;width:35px;height:17px;background-color:#ef5344;border:1px dashed #fff;
                        right:-18px;z-index:9;}
                    .quan-list ul li .d-box .d-left i.i-left{top:-2px;border-top: none;border-left: none;border-radius:0 0 50px 50px;}
                    .quan-list ul li .d-box .d-left i.i-right{bottom:-2px;border-bottom: none;border-right: none;border-radius:50px 50px 0 0;}
                    #tip-id{width:100%;max-width:450px;position:fixed;left:50%;margin:-200px 0 0 -220px;top:50%;z-index:9999;background:#fff;border-radius: 3px;
                        padding:15px;display:none;background:url(./images/ddb_pay_quan.png) no-repeat;padding: 50px 58px 0px 106px;background-size:100%;}
                    #tip-id .inputbox{margin-top: 33%;background-color:#fff;padding: 11px 29px 13px 40px;font-size:18px;position:relative;overflow:hidden;}
                    #tip-id .inputbox div{padding:5px 0;}
                    #tip-id .inputbox input{width: 160px;background: transparent;border: none;}
                    #tip-id .inputbox i{displa:block;width:50px;height:50px;border-radius:100%;position:absolute;z-index:9;margin-top:-25px;top:50%;}
                    #tip-id .inputbox i.i-left{left:-25px;background-color:#ff3b02;}
                    #tip-id .inputbox i.i-right{right:-25px;background-color:#bc0915;}
                    #tip-id .closebox {width:120px;margin:25px auto 48px auto;}
                    #tip-id .closebox a{display:block;width:100%;background-color:#fbf140;color:#bd0a14;text-align: center;padding: 6px 0;font-size: 20px;
                        border-radius: 4px;}
                    #tip-id #cboxClose1{right:82px;top:47px;position:absolute;cursor:pointer;}
                    .order-tip{margin-bottom: 15px;color:red;display:none;}
                    @media screen and (max-width:1199px){
                        .quan-list ul li{width:48%;}
                        .quan-list ul li .d-box .d-right{margin-right:30px;}
                    }
                    @media screen and (max-width:991px){
                        .quan-list ul li .d-box .d-left{width:60px;font-size: 18px;padding: 16px 11px 10px 14px;}
                        .quan-list ul li .d-box .d-right{margin-right:0;font-size: 14px;padding: 20px 12px 14px 6px;}
                        .quan-list ul li .d-box .d-right .juan-yue span{font-size:26px;}
                        .quan-list ul li .d-box .d-left i.i-right{}
                    }
                    @media screen and (max-width:767px){
                        .quan-list ul li{width:100%;}
                        .quan-list ul li .d-box .d-right{margin-right:25%;}
                    }
                    @media screen and (max-width:520px){
                        .quan-list ul li .d-box .d-right{float:left;margin-left:20px;margin-right:0;}
                        .ddb-amount a.add-quan{display: block;width: 119px;margin-left: 0;margin-top: 5px;}
                        #tip-id .inputbox{font-size:16px;}
                        #tip-id .inputbox input{width:145px;}
                        #tip-id .inputbox i{width:42px;height:42px;}
                        #tip-id .inputbox{padding: 11px 29px 13px 26px;}
                    }
                    @media screen and (max-width:413px){
                        #tip-id .inputbox{font-size:14px;padding: 11px 21px 13px 22px;}
                        #tip-id .inputbox input{width:125px;}
                        #tip-id{padding: 50px 42px 0px 89px;margin: -200px 0 0 -206px;width: 350px;margin-left: -175px;}
                        #tip-id #cboxClose1{right:68px;top:35px;}
                        #tip-id .closebox{width:80px;margin: 5px auto 26px auto;}
                        #tip-id .closebox a{padding: 3px 0;font-size: 14px;}
                        .err-msg{margin:5px 10px;}
                        .ddb-amount a.add-quan{width:99px;padding: 3px 7px;font-size: 13px;}
                    }
                    @media screen and (max-width:380px){
                        .quan-list ul li .d-box .d-left{width:44px;font-size:15px;padding: 16px 10px 10px 10px;}
                        .quan-list ul li .d-box .d-left i{}
                        .quan-list ul li .d-box .d-right .juan-yue span{font-size:22px;}
                        .quan-list ul li .d-box .d-right{padding: 20px 0px 14px 0px;}
                        .ddb-amount{font-size:15px;}
                        .ddb-amount span{font-size:22px;}
                    }
                    @media screen and (max-width:343px){
                        .quan-list ul li .d-box .d-righ{margin-left:13px;}
                    }
                </style>
                <div>
                    <div class="ddb-pay-box">
                        <span style="color:gray;">代金券支付可支持多券合并支付，但不能与其他支付方式合并支付</span>
                        <div class="ddb-amount" style="margin-top: 9px;">代金券【余额合计：<span>￥<span class="ye-amount">0.0</span>元</span>】<a class="add-quan"><i class="glyphicon glyphicon-plus-sign"></i>添加代金券</a></div>
                        <div class="quan-list">
                            <ul>

                            </ul>
                        </div>
                    </div>


                    <div class="order-tip">代金券余额不足以支付该订单，请确认后再试！</div>
                    <div class="pay-btn">
                        <a href="javascript:void(0)" onclick="return gotoPay()">确认支付</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="clearfix footer_margin"></div>


        <title>My JSP 'footer.jsp' starting page</title>
<?php include './views/foot.php';?>