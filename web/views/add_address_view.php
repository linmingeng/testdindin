<!DOCTYPE html>
<!-- saved from url=(0037)http://www.dindin.com/add_address.jsp -->
<html dir="ltr" lang="en"><!--<![endif]--><head><meta http-equiv="Content-Type" content="text/html; charset=GBK">
<script type="text/javascript" charset="utf-8" async="" src="./js/contains.js"></script>
<script type="text/javascript" async="" src="./js/mv.js"></script>
<script type="text/javascript" async="" src="./js/mba.js"></script>
<script type="text/javascript" charset="utf-8" async="" src="./js/taskMgr.js"></script>
<script type="text/javascript" charset="utf-8" async="" src="./js/views.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>新增/编辑地址</title>
<link rel="icon" href="http://www.dindin.com/dindinv2Images/favicon.ico" type="image/x-icon">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Version 2.0.3 -->
<!-- <script id="facebook-jssdk" src="http://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.3&appId=109031762530738"></script> -->
<script type="text/javascript" async="" src="./js/mvl.js"></script><script src="./js/hm.js"></script>
<script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
<link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="./css/responsive.css">
<link rel="stylesheet" type="text/css" href="./css/address.css">

<script type="text/javascript" src="./js/owl.carousel.min.js"></script>
<script src="./js/jquery.matchHeight.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="./js/shopme_common.js"></script>

<script type="text/javascript" src="./js/tweetfeed.min.js"></script>
<script type="text/javascript" src="./js/regiondata.js"></script>
<script type="text/javascript" src="./js/address.js"></script>
<script type="text/javascript" src="./js/load_address.js"></script>
<script type="text/javascript" src="./js/load_toolbar.js"></script>


<style type="text/css">
.errorRed{border:1px solid rgb(243, 170, 170);}
a:link, a:visited{color:#333333;}
</style>
<!-- Custom css -->
<!-- Custom script -->

<!-- Custom styling -->
<!-- Custom fonts -->
<!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,900" media="screen"> -->
<script type="text/javascript">
	$(function(){
		showAddressEditor('<?php $address = explode('.',$ret['address']); echo end($address);?>');

		$.fn.onlyNum = function () {
		    $(this).keypress(function (event) {
		        var eventObj = event || e;
		        var keyCode = eventObj.keyCode || eventObj.which;
		        if ((keyCode >= 48 && keyCode <= 57))
		            return true;
		        else
		            return false;
		    }).focus(function () {
		    //禁用输入法
		        this.style.imeMode = 'disabled';
		    }).bind("paste", function () {
		    //获取剪切板的内容
		        var clipboard = window.clipboardData.getData("Text");
		        if (/^\d+$/.test(clipboard))
		            return true;
		        else
		            return false;
		    });
		};
		
		//设置只能输入数字的事件
		$("#receiveMobile").onlyNum();
		$("#receivePostCode").onlyNum();
		
		
	})
</script>
<script src="./js/share.js"></script>
<script charset="utf-8" async="" src="./js/i.js" id="_da"></script>
<link rel="stylesheet" href="./css/share_style0_32.css"></head>
<body class="account-account style-4 ">
<iframe src="javascript:false" title="" frameborder="0" tabindex="-1" style="position: absolute; width: 0px; height: 0px; border: 0px;" src="./other/saved_resource.html"></iframe>
<iframe style="display: none;" src="./other/saved_resource(1).html"></iframe>
<style type="text/css">
.WPA3-SELECT-PANEL { z-index:2147483647; width:463px; height:292px; margin:0; padding:0; border:1px solid #d4d4d4; background-color:#fff; border-radius:5px; box-shadow:0 0 15px #d4d4d4;}.WPA3-SELECT-PANEL * { position:static; z-index:auto; top:auto; left:auto; right:auto; bottom:auto; width:auto; height:auto; max-height:auto; max-width:auto; min-height:0; min-width:0; margin:0; padding:0; border:0; clear:none; clip:auto; background:transparent; color:#333; cursor:auto; direction:ltr; filter:; float:none; font:normal normal normal 12px "Helvetica Neue", Arial, sans-serif; line-height:16px; letter-spacing:normal; list-style:none; marks:none; overflow:visible; page:auto; quotes:none; -o-set-link-source:none; size:auto; text-align:left; text-decoration:none; text-indent:0; text-overflow:clip; text-shadow:none; text-transform:none; vertical-align:baseline; visibility:visible; white-space:normal; word-spacing:normal; word-wrap:normal; -webkit-box-shadow:none; -moz-box-shadow:none; -ms-box-shadow:none; -o-box-shadow:none; box-shadow:none; -webkit-border-radius:0; -moz-border-radius:0; -ms-border-radius:0; -o-border-radius:0; border-radius:0; -webkit-opacity:1; -moz-opacity:1; -ms-opacity:1; -o-opacity:1; opacity:1; -webkit-outline:0; -moz-outline:0; -ms-outline:0; -o-outline:0; outline:0; -webkit-text-size-adjust:none; font-family:Microsoft YaHei,Simsun;}.WPA3-SELECT-PANEL a { cursor:auto;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-TOP { height:25px;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-CLOSE { float:right; display:block; width:47px; height:25px; background:url(http://combo.b.qq.com/crm/wpa/release/3.3/wpa/views/SelectPanel-sprites.png) no-repeat;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-CLOSE:hover { background-position:0 -25px;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-MAIN { padding:23px 20px 45px;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-GUIDE { margin-bottom:42px; font-family:"Microsoft Yahei"; font-size:16px;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-SELECTS { width:246px; height:111px; margin:0 auto;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-CHAT { float:right; display:block; width:88px; height:111px; background:url(http://combo.b.qq.com/crm/wpa/release/3.3/wpa/views/SelectPanel-sprites.png) no-repeat 0 -80px;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-CHAT:hover { background-position:-88px -80px;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-AIO-CHAT { float:left;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-QQ { display:block; width:76px; height:76px; margin:6px; background:url(http://combo.b.qq.com/crm/wpa/release/3.3/wpa/views/SelectPanel-sprites.png) no-repeat -50px 0;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-QQ-ANONY { background-position:-130px 0;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-LABEL { display:block; padding-top:10px; color:#00a2e6; text-align:center;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-BOTTOM { padding:0 20px; text-align:right;}.WPA3-SELECT-PANEL .WPA3-SELECT-PANEL-INSTALL { color:#8e8e8e;}</style><style type="text/css">.WPA3-CONFIRM { z-index:2147483647; width:285px; height:141px; margin:0; background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAR0AAACNCAMAAAC9pV6+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjU5QUIyQzVCNUIwQTExRTJCM0FFRDNCMTc1RTI3Nzg4IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjU5QUIyQzVDNUIwQTExRTJCM0FFRDNCMTc1RTI3Nzg4Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NTlBQjJDNTk1QjBBMTFFMkIzQUVEM0IxNzVFMjc3ODgiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NTlBQjJDNUE1QjBBMTFFMkIzQUVEM0IxNzVFMjc3ODgiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6QoyAtAAADAFBMVEW5xdCkvtNjJhzf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) no-repeat;}.WPA3-CONFIRM { *background-image:url(http://combo.b.qq.com/crm/wpa/release/3.3/wpa/views/panel.png);}.WPA3-CONFIRM * { position:static; z-index:auto; top:auto; left:auto; right:auto; bottom:auto; width:auto; height:auto; max-height:auto; max-width:auto; min-height:0; min-width:0; margin:0; padding:0; border:0; clear:none; clip:auto; background:transparent; color:#333; cursor:auto; direction:ltr; filter:; float:none; font:normal normal normal 12px "Helvetica Neue", Arial, sans-serif; line-height:16px; letter-spacing:normal; list-style:none; marks:none; overflow:visible; page:auto; quotes:none; -o-set-link-source:none; size:auto; text-align:left; text-decoration:none; text-indent:0; text-overflow:clip; text-shadow:none; text-transform:none; vertical-align:baseline; visibility:visible; white-space:normal; word-spacing:normal; word-wrap:normal; -webkit-box-shadow:none; -moz-box-shadow:none; -ms-box-shadow:none; -o-box-shadow:none; box-shadow:none; -webkit-border-radius:0; -moz-border-radius:0; -ms-border-radius:0; -o-border-radius:0; border-radius:0; -webkit-opacity:1; -moz-opacity:1; -ms-opacity:1; -o-opacity:1; opacity:1; -webkit-outline:0; -moz-outline:0; -ms-outline:0; -o-outline:0; outline:0; -webkit-text-size-adjust:none;}.WPA3-CONFIRM * { font-family:Microsoft YaHei,Simsun;}.WPA3-CONFIRM .WPA3-CONFIRM-TITLE { height:40px; margin:0; padding:0; line-height:40px; color:#2b6089; font-weight:normal; font-size:14px; text-indent:80px;}.WPA3-CONFIRM .WPA3-CONFIRM-CONTENT { height:55px; margin:0; line-height:55px; color:#353535; font-size:14px; text-indent:29px;}.WPA3-CONFIRM .WPA3-CONFIRM-PANEL { height:30px; margin:0; padding-right:16px; text-align:right;}.WPA3-CONFIRM .WPA3-CONFIRM-BUTTON { position:relative; display:inline-block!important; display:inline; zoom:1; width:99px; height:30px; margin-left:10px; line-height:30px; color:#000; text-decoration:none; font-size:12px; text-align:center;}.WPA3-CONFIRM .WPA3-CONFIRM-BUTTON-FOCUS { width:78px;}.WPA3-CONFIRM .WPA3-CONFIRM-BUTTON .WPA3-CONFIRM-BUTTON-TEXT { line-height:30px; text-align:center; cursor:pointer;}.WPA3-CONFIRM-CLOSE { position:absolute; top:7px; right:7px; width:10px; height:10px; cursor:pointer;}</style>
<!-- Cookie Control -->
    <title></title>
<style type="text/css">
	.tuijian-img{position:relative;color:#fff;}
    .tuijian-number{position: absolute;
		right: 27%;
		top: 12%;
		font-size: 18px;
		font-weight: bold;}
    .tuijian-number span{display:inline-block;width:21px;height:21px;line-height:21px;border:1px solid #fff;margin-right:5px;text-align:center;}
	@media screen and (max-width: 1260px) {
		.tuijian-number{right:18%;}
	}
	@media screen and (max-width: 1015px) {
		.tuijian-number{right:0;}
	}
	
	@media screen and (max-width: 767px) {
		
		.tuijian-img{display:none;}
	}
</style>
 <!-- 右侧客服浮窗 -->
<link rel="stylesheet" href="./css/fix.css">
<script charset="utf-8" src="./other/wpa.php"></script>
<script>

	$(function(){
		if($(window).width()<767){
			$('#fix').fadeOut();
		}else{
			$('#fix').fadeIn();
		}
		initShare();
	})
	
	function initShare(){
		window._bd_share_config = {
			common : {
				bdText : "叮叮网 - 进口商品热卖排行榜",
				bdDesc : "叮叮网 - 进口商品热卖排行榜.叮叮网中国进口商品热卖榜，本栏商品全部为畅销热卖品，深受国内消费者喜欢的海淘佳品。",	
				bdUrl : "http://www.dindin.com/images/dindin_logo.png", 	
				bdPic : "http://www.dindin.com/images/dindin_logo.png",
				onBeforeClick:function(cmd,config){
					var host= window.location.host;
					var bdUrl='http://';
					/* var itemId = document.getElementById('itemid').value;
					if(host=='www.dindin.com'){
						bdUrl=bdUrl+host+'/goodsDetail_id_'
					}else{
						bdUrl=bdUrl+host+'/hello/goodsDetail_id_';
					} */
					bdUrl="http://www.dindin.com/hotSaleAction!findHotSaleGoods.go";
					config.bdUrl=bdUrl;
					return config;
				}
			},
			share : [{
				"bdSize" : 32
			}]/*,
			image : [{
				viewType : 'list',
				viewPos : 'top',
				viewColor : 'black',
				viewSize : '16',
				viewList : ['weixin','tqq','tsina','tieba','sqq','qzone','fbook']
			}],
			selectShare : [{
				"bdselectMiniList" : ['weixin','tqq','tsina','tieba','sqq','qzone','fbook']
			}]*/
		}
		with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='js/share.js'];
		//with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
	}
</script>
<!-- Old IE Control -->
<div class="outer_container" id="cont-container">
<!-- header 部分 -->
<?php include './views/head.php';?>
<div class="breadcrumb_wrapper container"><ul class="breadcrumb">
        <li><a href="http://www.dindin.com/index.jsp">首页</a></li>
        <li><a href="http://www.dindin.com/my_account.jsp">帐户</a></li>
        <li><a href="http://www.dindin.com/address.jsp">地址管理</a></li>
        <li><a href="http://www.dindin.com/add_address.jsp">新增/编辑地址</a></li>
      </ul></div>
<div id="notification" class="container"></div><div class="container">
  
    <div class="row">
  
  <div id="column-left" class="col-md-3 col-sm-4">
    <h3>我的帐户</h3>
<div class="list-group box">
    <title></title>
    
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	-->
<style type="text/css">
	.application{border:1px solid #eaeaea;background:#fff;}
	.application li{height:41px;line-height:41px;border-bottom:1px solid #eaeaea;padding-left:19px;}
	.hassubchild{position:relative;}
	.subchild{font-size:13px;padding-left:40px;}
</style>
  
  
  
    
	<a href="?personalInfo/view" class="list-group-item dark_hover">个人资料</a>
	<a href="?receiveAddress/view" class="list-group-item dark_hover">地址管理</a> 
	<a href="?order/list" class="list-group-item dark_hover">我的订单</a>
	
  

</div>
  </div>
    <div id="content" class="col-md-9 col-sm-8">
      <h1>编辑地址</h1>
	  
	  <script>
	  	
	  </script>
      <form action="" id="addForm" method="post" enctype="multipart/form-data" class="form-horizontal">
      	<input type="hidden" name="addressid" value="<?php echo $ret['addressid'];?>">
      	<input type="hidden" name="ajax" value="1">
        <input type="hidden" value="name=&quot;po.id&quot;">
        <div class="bordered_content box">
        <div class="padded">
        <fieldset>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname">姓名</label>
	            <div class="col-sm-10">
	              <input type="text" name="address_name" value="<?php echo $ret['name'];?>" maxlength="20" placeholder="姓名" id="address_name" class="form-control" required="required">
	            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname">手机号</label>
	            <div class="col-sm-10">
	              <input type="text" name="address_phone" maxlength="11" onkeyup="value=value.replace(/[\Wa-zA-z]/g,&#39;&#39;) " onbeforepaste="clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[\Wa-zA-z]/g,&#39;&#39;))" value="<?php echo $ret['phone'];?>" placeholder="手机号" id="address_phone" class="form-control" >
	            </div>
          </div>
			
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-country">地区</label>
            <div class="col-sm-10">
            	  <div class="area" id="regionArea">
            	  <div class="area" style="z-index:100;" >
            	  	<span class="province" id="provinceName_320"> <?php echo $address[0];?></span>
            	  <div class="provincelist" id="provinceList_320" style="display:none;z-index:10000">
            	  <iframe class="maskiframe" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" src="./other/saved_resource(2).html">
            	  </iframe>
            	  <a href="?add_address/view#pl">北京</a>
            	  <a href="?add_address/view#pl">天津</a>
            	  <a href="?add_address/view#pl">上海</a>
            	  <a href="?add_address/view#pl">重庆</a>
            	  <br>
            	  <a href="?add_address/view#pl">广东</a>
            	  <a href="?add_address/view#pl">广西</a>
            	  <a href="?add_address/view#pl">海南</a>
            	  <a href="?add_address/view#pl">山东</a>
            	  <a href="?add_address/view#pl">江苏</a>
            	  <a href="?add_address/view#pl">安徽</a>
            	  <a href="?add_address/view#pl">浙江</a>
            	  <br>
            	  <a href="?add_address/view#pl">福建</a>
            	  <a href="?add_address/view#pl">湖北</a>
            	  <a href="?add_address/view#pl">湖南</a>
            	  <a href="?add_address/view#pl">河南</a>
            	  <a href="?add_address/view#pl">江西</a>
            	  <a href="?add_address/view#pl">吉林</a>
            	  <a href="?add_address/view#pl">黑龙江</a>
            	  <br>
            	  <a href="?add_address/view#pl">辽宁</a>
            	  <a href="?add_address/view#pl">四川</a>
            	  <a href="?add_address/view#pl">云南</a>
            	  <a href="?add_address/view#pl">贵州</a>
            	  <a href="?add_address/view#pl">西藏</a>
            	  <a href="?add_address/view#pl">陕西</a>
            	  <a href="?add_address/view#pl">青海</a>
            	  <br>
            	  <a href="?add_address/view#pl">甘肃</a>
            	  <a href="?add_address/view#pl">宁夏</a>
            	  <a href="?add_address/view#pl">新疆</a>
            	  <a href="?add_address/view#pl">河北</a>
            	  <a href="?add_address/view#pl">山西</a> 
            	  <a href="?add_address/view#pl">内蒙古</a>
            	  <br>
            	  <a href="?add_address/view#pl">香港</a>
            	  <a href="?add_address/view#pl">澳门</a>
            	  <a href="?add_address/view#pl">台湾</a>
            	  </div>
            	  <select name="cityId" id="cityId_320" style="width:100px;">
            	  	<option style="color:#666" value=""><?php echo $address[1];?></option>
            	  </select>
            	  <select name="areaId" id="areaId_320" style="width:100px;">
            	  	<option style="color:#666" value=""><?php echo $address[2];?></option>
            	  </select>
            	  <input type="hidden" name="provinceId" id="provinceId_320"></div></div>
			      <input type="hidden" name="po.regionId" id="address_regionId">
			      <input type="hidden" name="address_regionStr" id="address_regionStr">
			      <input type="hidden" name="address_complete" id="address_complete">
            </div>
          </div>

		  <div class="form-group required">
	            <label class="col-sm-2 control-label" for="input-address-1">街道地址</label>
	            <div class="col-sm-10">
	              <input type="text" name="address_area" maxlength="80" value="<?php echo $ret['address'];?>" placeholder="街道地址" id="address_area" class="form-control">
	           </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city">邮编</label>
	            <div class="col-sm-10">
	              <input type="text" name="address_zip" maxlength="6" onkeyup="value=value.replace(/[\Wa-zA-z]/g,&#39;&#39;) " onbeforepaste="clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[\Wa-zA-z]/g,&#39;&#39;))" onpaste="return false;" value="<?php echo $ret['zip'];?>" placeholder="邮编" id="address_zip" class="form-control">
	             </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city">身份证</label>
	            <div class="col-sm-10">
	              <input type="text" name="address_idCard" id="address_idCard" maxlength="18" value="<?php echo $ret['idCard'];?>" placeholder="收货人身份证号码" class="form-control">
	             <div style="font-size: 12px;color: rgb(253, 42, 42);margin-top: 5px;display:none;">身份证号码不正确！</div>
	             </div>
          </div>
          
		 <div class="form-group no_b_margin">
            <label class="col-sm-2 control-label">设为默认</label>
            <div class="col-sm-10">
	           <label class="radio-inline">
	           		<input type="radio" name="address_isDefault" value="1"> 是
	           </label>
	           <label class="radio-inline">
	           		<input type="radio" name="address_isDefault" value="0" checked="checked"> 否
	           </label>   
            </div>
         </div>
        </fieldset>
        </div>
        <div class="bottom_buttons">
        <div class="row">
          <div class="col-sm-6"><a href="?receiveAddress/view" class="btn btn-default">返回</a></div>
          <div class="col-sm-6 text-right">
            <input type="button" id="add_address" value="确定" class="btn btn-primary">
          </div>
          </div>
        </div>
        </div>
      </form>
      </div>
    </div>
</div>
<script>
 $("#add_address").click(function(){
        var address_idCard =$("#address_idCard").val();
        var address_name =$("#address_name").val();
        var address_area = $("#address_area").val();
        var address_zip = $("#address_zip").val(); 
        var address_phone = $("#address_phone").val();
        var provinceName=$("span.province").text();
        var city=$("select[name='cityId'] option:selected").text();
        var area=$("select[name='areaId'] option:selected").text();
        if($.trim(address_name)==""){
            alert("姓名不能为空！");
            return;
        }

        if($.trim(address_phone)==""){
            alert("手机号不能为空！");
            return;
        }

        if(isNaN(address_phone)){            
            alert("手机号不是数字！");
            return;
        }
		if (provinceName=="- 选择省 -" || city=="- 选择市 -" ){
			if (area=="- 选择区 -" && $("select[name='areaId'] ").attr("display")!="none")  {
				alert("请选择完整地区！");
           		return;
			}
		}else{
			if(area=="- 选择区 -"){area=""}
            var address_complete=provinceName+"."+city+"."+area;
            $("#address_complete").val(address_complete);
        }

        if($.trim(address_area)==""){
            alert("街道地址不能为空！");
            return;
        }
        
        if($.trim(address_zip)==""){
            alert("邮编不能为空！");
            return;
        }          
        
          
        if(isNaN(address_zip)){            
            alert("邮编不是数字！");
            return;
        }
        
        if($.trim(address_idCard)==""){        
            alert("证件号码不能为空");
            return;
        
        }else{        
            var uform=$("#addForm").serialize();
            $.ajax({
               type: "POST",
               url: "?add_address/view",
               data: uform,
               dataType: "json",
               success: function(data){
                 if(data.code==200) {
                        alert("地址保存成功");
                        window.location.href = '?receiveAddress/view';
                    }else{
                        alert(data.msg);
                  }
                }                               
            });
        }
        
        
    });
</script>
<div class="clearfix footer_margin"></div>




  

    
    <title>My JSP 'footer.jsp' starting page</title>
    
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	-->
<?php include './views/foot.php'; ?>
  <!--<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');


  ga('create', 'UA-70297441-1', 'auto');
  ga('send', 'pageview');


</script>
  
  -->



<!-- footer 部分 -->

</div>  <!-- .outer_container ends -->
<!-- Resources dont needed until page load -->
<script type="text/javascript" src="./js/jquery.cookie.js"></script>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<div id="cboxOverlay" style="display: none;"></div>
<style type="text/css">
	#colorbox1{position: absolute;
	top: 0;
	left: 0;
	z-index: 9999;
	overflow: hidden;}
	#colorbox1 .product img {
	float: left;
	margin-right: 20px;
	}
	#colorbox1 .cart_notification .product {
	padding: 19px 20px;
	display: table;
	width: 100%;
	}
	#colorbox1 .cart_notification .bottom {
	position: absolute;
	bottom: 0;
	right: 0;
	left: 0;
	}
	#colorbox1 .bottom {
	padding: 20px 19px;
	border-top: 1px solid #eaeaea;
	}
</style>
<!-- <script>
	$(function(){
		var flag = userLogin.isLogin();
	if(!flag){
		 alert("您尚未登录,请登录！");
		 userLogin.loginAlert({action:function(){
				location.reload(true);
			}}
		);
	}
	})
</script> -->
 <div id="colorbox1" class="login" role="dialog" tabindex="-1" style="display: none; visibility: visible; top: 250px; left: 752px; position: fixed; width: 400px; height: 200px;">
	 <div id="cboxWrapper" style="height: 200px; width: 400px;">
		 <div>
			 <div id="cboxTopLeft" style="float: left;"></div>
			 <div id="cboxTopCenter" style="float: left; width: 400px;"></div>
			 <div id="cboxTopRight" style="float: left;"></div>
		 </div>
		 <div style="clear: left;">
			 <div id="cboxContent" style="float: left; width: 400px; height: 200px;">
				 <div id="cboxLoadedContent" style="width: 400px; overflow: auto; height: 200px;">
					 <div class="cart_notification">
					 <div class="product">
					 	<img src="./image/45-80x80.JPG"><span>Success: You have added <a href="http://velikorodnov.com/opencart/shopme/demo6/index.php?route=product/product&amp;product_id=60">Ipsum Dolor Adipiscing 15, 2.5 fl oz (75ml)</a> to your <a href="http://www.dindin.com/shopping_cart.html">shopping cart</a>!</span>
					 </div>
					 <div class="bottom"><a class="btn btn-default" href="http://www.dindin.com/shopping_cart.html">View Cart</a> <a class="btn btn-primary" href="http://www.dindin.com/checkout.html">Checkout</a>
					 </div>
					 </div>
				 </div>
				 <div id="cboxTitle" style="float: left; display: block;"></div>
				 <div id="cboxCurrent" style="float: left; display: none;"></div>
				 <a id="cboxPrevious" style="display: none;"></a>
				 <a id="cboxNext" style="display: none;"></a>
				 <button id="cboxSlideshow" style="display: none;"></button>
				 <div id="cboxLoadingOverlay" style="float: left; display: none;"></div>
				 <div id="cboxLoadingGraphic" style="float: left; display: none;"></div>
				 <div id="cboxClose">close</div>
			 </div>
			 <div id="cboxMiddleRight" style="float: left; height: 200px;"></div>
		 </div>
		 <div style="clear: left;">
			 <div id="cboxBottomLeft" style="float: left;"></div>
			 <div id="cboxBottomCenter" style="float: left; width: 400px;"></div>
			 <div id="cboxBottomRight" style="float: left;"></div>
		 </div>
	 </div>
	 <div style="position: absolute; width: 9999px; visibility: hidden; max-width: none; display: none;"></div>
 </div>
<div id="cboxOverlay" style="display: none;"></div>
<div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none;">
	<div id="cboxWrapper">
		<div>
			<div id="cboxTopLeft" style="float: left;"></div>
			<div id="cboxTopCenter" style="float: left;"></div>
			<div id="cboxTopRight" style="float: left;"></div>
		</div>
		<div style="clear: left;">
			<div id="cboxMiddleLeft" style="float: left;"></div>
			<div id="cboxContent" style="float: left;">
				<div id="cboxTitle" style="float: left;"></div>
				<div id="cboxCurrent" style="float: left;"></div>
				<a id="cboxPrevious"></a><a id="cboxNext"></a>
				<button id="cboxSlideshow"></button>
				<div id="cboxLoadingOverlay" style="float: left;"></div>
				<div id="cboxLoadingGraphic" style="float: left;"></div>
			</div>
			<div id="cboxMiddleRight" style="float: left;"></div>
		</div>
		<div style="clear: left;">
			<div id="cboxBottomLeft" style="float: left;"></div>
			<div id="cboxBottomCenter" style="float: left;"></div>
			<div id="cboxBottomRight" style="float: left;"></div>
		</div>
	</div>
	<div style="position: absolute; width: 9999px; visibility: hidden; display: none; max-width: none;"></div>
</div>
</body></html>