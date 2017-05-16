<!DOCTYPE html>
<!-- saved from url=(0060)http://www.dindin.com/orderAction!getOrderDetail.go?id=71446 -->
<html dir="ltr" lang="en"><!--<![endif]--><head><meta http-equiv="Content-Type" content="text/html; charset=GBK"><script type="text/javascript" charset="utf-8" async="" src="./js/contains.js"></script>
<script type="text/javascript" async="" src="./js/mv.js"></script>
<script type="text/javascript" async="" src="./js/mba.js"></script>
<script type="text/javascript" charset="utf-8" async="" src="./js/taskMgr.js"></script>
<script type="text/javascript" charset="utf-8" async="" src="./js/views.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>订单详情</title>
<!--<base href="http://velikorodnov.com/opencart/shopme/demo5/">--><script charset="utf-8" async="" src="./js/i.js" id="_da"></script><!--<base href=".">--><base href=".">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="http://www.dindin.com/" rel="icon">
<!-- Version 2.0.3 -->
<!-- <script id="facebook-jssdk" src="http://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.3&appId=109031762530738"></script> -->
<script type="text/javascript" async="" src="./js/mvl.js"></script><script src="./js/hm.js"></script><script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>

<link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
<link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="./css/responsive.css">

<link rel="stylesheet" type="text/css" href="./css/styles.css" media="screen">
<link rel="stylesheet" type="text/css" href="./css/blog.css" media="screen">
<!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,900" media="screen"> -->

<script type="text/javascript" src="./js/owl.carousel.min.js"></script>
<script src="./js/jquery.matchHeight.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="./js/shopme_common.js"></script>

<script type="text/javascript" src="./js/spin.min.js"></script>
<link rel="stylesheet" href="./css/General.css">
<script type="text/javascript" src="./js/tweetfeed.min.js"></script>

<!-- Custom css -->
<!-- Custom script -->

<!-- Custom styling -->
<!-- Custom fonts -->
<!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,900" media="screen"> -->
<style type="text/css">
	.mobile_hide a{display: block;color: #018bc8;text-decoration: underline;margin-bottom: 10px;}
	.orderId a{color: #018bc8;}
/*======历史订单详情 开始=================*/
		.info{background: #fff;margin-top:45px;}
		.detail{background: #eaeaea;padding: 5px 0;}
		.detail .dingdan p span{color: #0f0f0f;}
		.detail .staus p span{color: #809a2d;}
		.info .info-text{padding-left: 15px;margin-top: 30px;}
		.info .info-text h2,.info .info-point h2{color: #000;font-size: 18px;}
		.info .info-text ul li{color: #787777;margin-bottom: 15px;line-height: 18px;}
		.info .info-point{padding-left: 15px;margin-top: 20px;padding-bottom: 20px;}
		.info .info-point p{color: #787777;}
		.info .info-point p span{color: #ee0101;}
		.info .info-point p a{color: #0f78b9;}
		.shop-list{margin-top: 25px;background: #fff;}
		.shop-list h2{font-size: 18px;padding-top: 15px;color: #000;padding-left: 15px;}
		.shop-list table{width: 100%;border: 1px solid #eaeaea;}
		.shop-list table tr th{background: #eaeaea;padding: 5px 0;text-align: center;color: #6E6D6D;}
		.shop-list table tr th:nth-child(1){width: 27%;}
		.shop-list table tr th:nth-child(6){text-align: left;}
		.shop-list table tr td{text-align: center;color: #7d7d7d;}
		.shop-list table tr td:nth-child(6){text-align: left;}
		.shop-list table tr td img{display: block;width: 34%;border: 1px solid #eaeaea;margin: 10px 5px 10px 15px;}
		.shop-list table tr td p{text-align: left;color: #0f0f0f;padding-left:15px;margin-bottom: 10px;}
		.shop-list table tr td span{color: #F74141;font-weight: bold;}
		.shop-list table tr td a{display: inline-block;color: #0f78b9;}
		.shop-list table tr td a.buy{border: 1px solid #d2d2d2;padding: 1px 5px;background: #ececec;color: #656363;margin-top: 5px;font-size:13px;}
		.shop-list .shop-text{margin-top: 25px;padding-bottom: 20px;}
		.shop-list .shop-text .shop-info{margin-bottom: 15px;}
		.shop-list .shop-text .shop-left,.shop-list .shop-text .shop-right{float: left;}
		.shop-list .shop-text .shop-left{width: 87%;text-align: right;color: #7d7d7d;}
		.shop-list .shop-text .shop-left span,.shop-list .shop-text .shop-right span{color: #F74141;font-size: 15px;font-weight: bold;}
		.shop-list .shop-text .shop-right{width: 13%;color: #7d7d7d;}
		@media screen and (max-width: 1199px) {
			.shop-list table tr td p{margin-top: 18px;}
		}
		@media screen and (max-width: 991px) {
			.shop-list table tr td p{margin-top: 5px;}
		}
		@media screen and (max-width: 767px) {
			.shop-list table tr td p{margin-top: 15px;}
			.info{margin-top:0px;}
		}
		@media screen and (max-width: 610px) {
			.shop-list table tr td p{margin-top: 5px;}
		}
		@media screen and (max-width: 420px) {
			.info .info-text ul li{font-size: 12px;}
			.shop-list table{font-size: 12px;}
			.shop-list table tr td img{float: none;width: 75%;}
			.shop-list table tr td p{margin-top: -5px;}
			.shop-list .shop-text .shop-info{font-size: 12px;}
			.shop-list .shop-text .shop-left{width: 80%;}
			.shop-list .shop-text .shop-right{width: 20%;}
			.dingdan{padding-right: 0;font-size: 12px;}
			.staus{padding: 0;font-size: 12px;}
			.info h2,.shop-list h2{font-size: 16px;}
		}
		/*======历史订单详情 结束=================*/
</style>
<script src="./js/share.js"></script><link rel="stylesheet" href="./css/share_style0_32.css"></head>
<body class="account-order style-3 ">
<iframe src="javascript:false" title="" frameborder="0" tabindex="-1" style="position: absolute; width: 0px; height: 0px; border: 0px;" src="./image/saved_resource.html"></iframe>
<iframe style="display: none;" src="./image/saved_resource(1).html"></iframe>

<!-- Cookie Control -->    
    <title></title>   
    <div class="tuijian-img">
	<img src="./image/head_top_img.jpg" width="100%">
	<div class="tuijian-number">
		<img src="./image/head_top_img_03.png"><a href="http://www.dindin.com/exchange_integral.jsp" target="_blank">查看可兑换的礼品&gt;&gt;</a>
	</div>
	<div class="tuijian-number" style="right:23.5%;top:62%;margin-top: 3px;">
		<a href="http://www.dindin.com/integral_rule.jsp" target="_blank">查看如何获取更多积分&gt;&gt;</a>
	</div>
</div>
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
	
	
	BizQQWPA.addCustom({aty: '1', a: '1003', nameAccount: 4008622111, selector: 'BizQQWPA'});
    jQuery(function(){
        jQuery('#fix ul li .back').click(function(){
            jQuery('html,body').animate({scrollTop:0},300);
        });
        jQuery('#fix ul li').each(function(){
            jQuery(this).find('a').hover(function(){
                jQuery(this).find('div').css({display:'block'});
            },function(){
                jQuery(this).find('div').css({display:'none'});
            })
        })
        jQuery('#fix ul li span').hover(function(){
            jQuery(this).find('dl').css({display:'block'});
            jQuery(this).find('dl').next().show();
        },function(){
            jQuery(this).find('dl').css({display:'none'});
            jQuery(this).find('dl').next().hide();
        })
        $('#fix .gototop').click(function(){
        	$('body,html').animate({scrollTop:0},1500);
        })
    })

</script>
  


<!-- Old IE Control -->
<div class="outer_container" id="cont-container">
<!-- header 部分 -->

<?php include './views/head.php';?>

<div class="breadcrumb_wrapper container" style="margin-bottom: 0;"><ul class="breadcrumb">
        <li><a href="http://www.dindin.com/index.html">首页</a></li>
        <li><a href="http://www.dindin.com/memberIndex.go">帐户</a></li>
        <li><a>订单详情</a></li>
      </ul></div>
<div id="notification" class="container"></div><div class="container">
  
  <div class="row">
  
  <div id="column-left" class="col-md-3 col-sm-4">
    <h3>我的帐户</h3>
<div class="list-group box">
  
    <a href="http://www.dindin.com/memberIndex.go" class="list-group-item dark_hover">我的帐户</a>
	<a href="http://www.dindin.com/userInfoManageAction!edit.go" class="list-group-item dark_hover">个人资料</a>
	<a href="http://www.dindin.com/receiveAddressAction!getAddressList.go" class="list-group-item dark_hover">地址管理</a> 
	<a href="http://www.dindin.com/order_history.jsp" class="list-group-item dark_hover">我的订单</a>
	<a href="http://www.dindin.com/changePwd.jsp" class="list-group-item dark_hover">修改密码</a>
	<a href="http://www.dindin.com/score.go" class="list-group-item dark_hover hassubchild">我的积分</a>
	<a href="http://www.dindin.com/recommend.go" class="list-group-item dark_hover subchild"><i class="fa fa-angle-right"></i> 获取积分</a>  
	<a href="http://www.dindin.com/yhqList.go" class="list-group-item dark_hover hassubchild">我的优惠券</a>
	<a href="http://www.dindin.com/yhq!add.go" class="list-group-item dark_hover subchild"><i class="fa fa-angle-right"></i> 兑换优惠券</a>
	
    <h3 class="application-h" style="margin-top:20px;">应用推荐<img style="margin-left:10px;margin-top:-13px;" src="./image/memhot.gif"></h3>
	<div class="application">
		<ul role="menubar" class="img_align">
          <li><img src="./image/ico-jiaofei-36.png" align="absmiddle" width="18" height="18">　<a href="http://www.zfbill.com/chargeEntering.html" target="_blank">水电煤缴费</a></li>
          <li><img src="./image/ico-tx-36.png" align="absmiddle" width="18" height="18">　<a href="http://www.zfbill.com/index.html" target="_blank">手机充值</a></li>
          <li><img src="./image/ico-jiaofadan-36.png" align="absmiddle" width="18" height="18">　<a href="http://www.zfbill.com/fare.html" target="_blank">交通罚款代办</a></li>
          <li><img src="./image/ico-game-36.png" align="absmiddle" width="18" height="18">　<a href="http://www.zfbill.com/game.html" target="_blank">游戏点卡</a></li>
          <li><img src="./image/ico-postal.png" align="absmiddle" width="18" height="18">　<a href="http://www.zfbill.com/postal.html" target="_blank">邮政网上汇款</a></li>
		  <li><img src="./image/ico-lottery-36.png" align="absmiddle" width="18" height="18">　<a href="http://www.zfbill.com/chargeInternet.html" target="_blank">固话宽带</a></li>
        </ul>
	</div>
  

</div>
  </div>
    
  <div id="content" class="col-md-9 col-sm-8">
      <div class="info">
			<div class="detail clearfix">
				<div class="col-xs-8 col-sm-8 col-lg-4 dingdan">
					<p>订单号：<span>y170501155123810</span></p>
				</div>
				<div class="col-xs-4 col-sm-4 col-lg-8 staus">
					<p>状态：<span>待支付
					</span></p>
				</div>
			</div>
			<div class="info-text">
				<h2>订单信息</h2>
				<ul>
					<li>下单时间：<span>2017-05-01 15:51:23</span></li>
					<li>支付方式：<span>网上支付</span></li>
					<li>收货信息：<span>温杨杨&nbsp;&nbsp;12345678910&nbsp;&nbsp;&nbsp;&nbsp;350000&nbsp;&nbsp;福建省福州市晋安区晋安区</span></li>	
					
					<li>物流名称：<span>叮叮物流(<a target="_blank" href="http://www.kuaidi100.com/frame/index.html">查看物流</a>)</span></li>
					<li>物流编号：<span></span></li>
				</ul>
			</div>
			<hr>
			<div class="info-point" style="display:none;">
				<h2>订单积分</h2>
				<p>积分：已返积分<span> 399</span>【<a href="http://www.dindin.com/exchange_integral.jsp">积分兑换</a>】</p>
			</div>

		</div>
        <div class="shop-list">
			<h2>商品清单</h2>
			<table>
				<tbody>
					<tr>
						<th>商品</th>
						<th>单价</th>
						<th>数量</th>
						<th>优惠</th>
						<th>小计</th>
						<th>操作</th>
					</tr>
					<tr>
						<td>
							<img src="./image/19348.png" alt="">								
							<p><a title="M&amp;M牛奶巧克力豆（MM糖果玩具） 2件装" href="http://www.dindin.com/goodsDetail_id_19348.htm" class="nameSubStr">M&amp;M牛奶巧克力豆（MM糖果玩具） 2件装</a></p>
						</td>
						<td>￥66.0</td>
						<td>×1</td>
						<td>--</td>
						<td><span>￥66.00</span></td>
						<td>
							<a href="http://www.dindin.com/goodsComment!toCommentPage.go?order.id=19348" style="display: none;">评价晒单</a><br>
							<a href="http://www.dindin.com/goodsDetail_id_19348.htm" class="buy">还要买</a>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="shop-text" style="display: none;">
				<div class="shop-info clearfix">
					<div class="shop-left">共<span> 1 </span>件商品 金额：</div>
					<div class="shop-right">￥66.0</div>
				</div>
				<div class="shop-info clearfix">
					<div class="shop-left">优惠券优惠：</div>
					<div class="shop-right">-￥30.00</div>
				</div>
				<div class="shop-info clearfix">
					<div class="shop-left">运费（已免运费）：</div>
					<div class="shop-right">￥0</div>
				</div>
				<div class="shop-info clearfix">
					<div class="shop-left">订单金额：</div>
					<div class="shop-right"><span>￥66.0</span></div>
				</div>
			</div>
		</div> 
  
 
      </div>
    </div>
</div>


<div class="clearfix footer_margin"></div>
    <title>My JSP 'footer.jsp' starting page</title>
    
<?php include './views/foot.php'; ?>

<!-- footer 部分 -->

</div>  <!-- .outer_container ends -->
<!-- Resources dont needed until page load -->
<script type="text/javascript" src="./js/jquery.cookie.js"></script>

<script>
function doquick_search( ev, keywords ) {
	if( ev.keyCode == 38 || ev.keyCode == 40 ) {
		return false;
	}	
	$('#ajax_search_results').remove();
	 updown = -1;
	if( keywords == '' || keywords.length < 1 ) {
		return false;
	}
	keywords = encodeURI(keywords);
	$.ajax({url: $('base').attr('href') + 'index.php?route=module/d_ajax_search/ajaxsearch&keyword=' + keywords, dataType: 'json', success: function(result) {
            console.log(result);
		if( result.length > 0 ) {
			var html, i;
			html = '<div id="ajax_search_results"><div id="ajax_search_results_body">';
			for(i=0;i<result.length;i++) {
				html += '<div class="live_row"><div class="live_image">';
				
				if(result[i].thumb){
					html += '<a href="' + result[i].href + '"><img src="' + result[i].thumb + '" /></a>';
				}
				
				html += '</div>';
				html += '<div class="live_name"><a href="' + result[i].href + '"><span class="name">' + result[i].name + '</span></a>';
				html += '</div>';
				
				html += '<div class="live_price">';
				
				if(result[i].special.length > 0){
					html += '<a href="' + result[i].href + '"><p class="old-price">' + result[i].price + '</p>';
					html += '<p class="special price">' + result[i].special + '</p></a>';
				} else {
					if(result[i].price.length > 0){
						html += '<a href="' + result[i].href + '"><p class="price">' + result[i].price + '</p></a>';	
					}else{
						
					}
				}
				html += '</div></div>';
			}
			html += '</div></div>';
			if( $('#ajax_search_results').length > 0 ) {
				$('#ajax_search_results').remove();
			}
			$('#search').append(html);
		}
	}});
	return true;
}

function upDownEvent( ev ) {
	var elem = document.getElementById('ajax_search_results_body');
	var fkey = $('#search').find('[name=search]').first();
	if( elem ) {
		var length = elem.childNodes.length - 1;
		if( updown != -1 && typeof(elem.childNodes[updown]) != 'undefined' ) {
			$(elem.childNodes[updown]).removeClass('selected');
		}
		if( ev.keyCode == 38 ) {
			updown = ( updown > 0 ) ? --updown : updown;	
		}
		else if( ev.keyCode == 40 ) {
			updown = ( updown < length ) ? ++updown : updown;
		}
		if( updown >= 0 && updown <= length ) {
			$(elem.childNodes[updown]).addClass('selected');
			var text = $(elem.childNodes[updown]).find('.name').html();
			$('#search').find('[name=search]').first().val(text);
		}
	}
	return false;
}
var updown = -1;
$(document).ready(function(){
	$('[name=search]').keyup(function(ev){
		doquick_search(ev, this.value);
	}).focus(function(ev){
		doquick_search(ev, this.value);
	}).keydown(function(ev){
		upDownEvent( ev );
	}).blur(function(){
		window.setTimeout("$('#ajax_search_results').remove();updown=0;", 15000);
	});
	$(document).bind('keydown', function(ev) {
		try {
			if( ev.keyCode == 13 && $('.selected').length > 0 ) {
				if($('.selected').find('a').first().attr('href')){
					document.location.href = $('.selected').find('a').first().attr('href');
				}
			}
		}
		catch(e) {}
	});
	if($(window).width()>1200){
		nameSubStr($('.shop-list a.nameSubStr'),40);//限制商品名称字数
	}else if($(window).width()<767){
		nameSubStr($('.shop-list a.nameSubStr'),15);//限制商品名称字数
	}
});
</script>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<div id="cboxOverlay" style="display: none;"></div>


<script type="text/javascript" src="./js/load_toolbar.js"></script>
<div id="cboxOverlay" style="display: none;"></div>
<div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none;"><div id="cboxWrapper"><div><div id="cboxTopLeft" style="float: left;"></div>
<div id="cboxTopCenter" style="float: left;"></div>
<div id="cboxTopRight" style="float: left;"></div></div>
<div style="clear: left;"><div id="cboxMiddleLeft" style="float: left;"></div>
<div id="cboxContent" style="float: left;"><div id="cboxTitle" style="float: left;"></div>
<div id="cboxCurrent" style="float: left;"></div><a id="cboxPrevious"></a><a id="cboxNext"></a><button id="cboxSlideshow"></button>
<div id="cboxLoadingOverlay" style="float: left;"></div><div id="cboxLoadingGraphic" style="float: left;"></div></div>
<div id="cboxMiddleRight" style="float: left;"></div></div>
<div style="clear: left;"><div id="cboxBottomLeft" style="float: left;"></div><div id="cboxBottomCenter" style="float: left;"></div>
<div id="cboxBottomRight" style="float: left;"></div></div></div>
<div style="position: absolute; width: 9999px; visibility: hidden; display: none; max-width: none;"></div></div>
</body>
</html>