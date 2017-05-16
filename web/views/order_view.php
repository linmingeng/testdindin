<!DOCTYPE html>
<!-- saved from url=(0039)http://www.dindin.com/order_history.jsp -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=GBK"><script type="text/javascript" charset="utf-8" async="" src="./js/contains.js"></script><script type="text/javascript" charset="utf-8" async="" src="./js/taskMgr.js"></script><script type="text/javascript" charset="utf-8" async="" src="./js/views.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>我的订单</title>
<link rel="icon" href="http://www.dindin.com/dindinv2Images/favicon.ico" type="image/x-icon">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="http://www.dindin.com/order_history.jsp" rel="icon">
<!-- Version 2.0.3 -->
<!--[if lte IE 8]>
<script src=http://blog.csdn.net/jzj1993/article/details/"http://cdn.bootcss.com/jquery/1.9.0/jquery.min.js">
<![endif]-->
<script type="text/javascript" async="" src="./js/mv.js"></script><script type="text/javascript" async="" src="./js/mba.js"></script><script type="text/javascript" async="" src="./js/mvl.js"></script><script src="./js/hm.js"></script><script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>

<link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
<link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="./css/responsive.css">


<script type="text/javascript" src="./js/owl.carousel.min.js"></script>
<script src="./js/jquery.matchHeight.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="./js/tweetfeed.min.js"></script>
<script type="text/javascript" src="./js/jquery.cookie.js"></script>
<script type="text/javascript" src="./js/shopme_common.js"></script>

<script type="text/javascript" src="./js/load_toolbar.js"></script>
<script type="text/javascript" src="./js/login.js"></script>
<script type="text/javascript" src="./js/order_history.js"></script>
<link rel="stylesheet" href="./css/General.css">
<script src="./js/share.js"></script><script charset="utf-8" async="" src="./js/i.js" id="_da"></script><link rel="stylesheet" href="./css/share_style0_32.css"></head>
<body class="account-order style-3 "><iframe src="javascript:false" title="" frameborder="0" tabindex="-1" style="position: absolute; width: 0px; height: 0px; border: 0px;" src="./image/saved_resource.html"></iframe><iframe style="display: none;" src="./image/saved_resource(1).html"></iframe>  
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
  


<div class="outer_container" id="cont-container">
<!-- header 部分 -->





  

    
    <title>My JSP 'header.jsp' starting page</title>
    
<?php include './views/head.php';?>
<div class="breadcrumb_wrapper container"><ul class="breadcrumb">
        <li><a href="http://www.dindin.com/index.html">首页</a></li>
        <li><a href="http://www.dindin.com/my_account.html">帐户</a></li>
        <li><a href="http://www.dindin.com/order_history.html">我的订单</a></li>
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
      <h1>我的订单</h1>
	<div class="history">
		<div class="tab-history clearfix">
			<div class="col-md-6 col-lg-6">
				<ul class="tab" id="orderTabs">
					<li class="active" onclick="checkLoadOrderList(&#39;allOrders&#39;)">全部订单</li>
					<li onclick="checkLoadOrderList(&#39;ordersByPay&#39;)">待支付</li>
					<li onclick="checkLoadOrderList(&#39;ordersByFa&#39;)">待发货</li>
					<li onclick="checkLoadOrderList(&#39;ordersByShou&#39;)">待收货</li>
					<li onclick="checkLoadOrderList(&#39;ordersBySuccess&#39;)">已完成</li>
				</ul>
			</div>
			<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 recover" style="display: none;">

				<form action="http://www.dindin.com/order_history.jsp" method="">
					<div class="search-his">
						<input type="text" name="search" value="" placeholder="商品名称/商品编号/订单号">
						<i class="fa fa-search search"></i>
					</div>
				</form>
			</div>
		</div>
		<!-- ---------- -->
		<div class="shop-history" id="all-order">
			<table class="table-responsive">
				<thead>
					<tr>
						<th></th>
						<th>商品</th>
						<th>数量</th>
						<th>单价</th>
						<th id="staus">
							<span>全部状态</span>
						</th>
					</tr>
				</thead>
				<tbody id="allOrder"><tr><td class="order-info" colspan="5"><div class="orderNum"><div>订单号： </div><div><em class="on">y170501155123810</em></div></div><div class="shouhuoRen"><div>收货人： </div><div><em class="on">温杨杨</em></div></div><div class="orderTime"><div>下单时间： </div><div><em>2017-05-01 15:51:23</em></div></div></td></tr><tr><td colspan="2" style="text-align:left"><a href="http://www.dindin.com/goodsDetail_id_19348.htm"><img src="./image/19348.png" alt="M&amp;M牛奶巧克力豆（MM糖果玩具） 2件装" title="M&amp;M牛奶巧克力豆（MM糖果玩具） 2件装"><p class="goods-name name nameSubStr" title="M&amp;M牛奶巧克力豆（MM糖果玩具） 2件装">M&amp;M牛奶巧克力豆（MM糖果玩具） 2件装</p></a></td><td>x1</td><td align="center"><span class="active">￥66.00</span></td><td class="order-status"><span>待支付</span><a href="http://www.dindin.com/orderAction!getOrderDetail.go?id=71446" class="active">订单详情</a></td></tr><tr><td colspan="5"><div class="order-totals">总计：<span>￥66</span></div><div class="editBox"><a class="bt_small active edit-btn" target="_blank" href="http://www.dindin.com/buyAction!newToSelectPayPage.go?oids=71446">去付款</a><a onclick="cancelOrder(71446)" href="http://www.dindin.com/order_history.jsp#" class="edit-btn">取消订单</a></div></td></tr></tbody>
			</table>
		</div>
		<div id="pagebarWrap" class="wrap_page">
			<div class="page">
				<p id="itemList_pagebar" class="link_page"><a href="javascript:void(0);" style="" class="disabled"><span class="before_page">&lt;</span></a><a class="on"><span>1</span></a><a href="javascript:void(0);" style="color : darkgray"><span class="next_page">&gt;</span></a></p>
			</div>
	    </div>
		<div class="shop-history" id="o-pay">
			<table class="table-responsive">
				<thead>
					<tr>
						<th>操作</th>
						<th>商品</th>
						<th>数量</th>
						<th>单价</th>
						<th id="staus">
							<span>全部状态</span>
						</th>
					</tr>
					<tr>
					<td colspan="5">
							<div class="pay-together"><input type="checkbox" id="payCk_1"> 全选&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0);" class="megerPay">合并付款</a></div>
						</td>
					</tr>
				</thead>
				<tbody id="ordersByPay">
					
				</tbody>
				<tbody><tr>
					<td colspan="5">
							<div class="pay-together"><input type="checkbox" id="payCk_2"> 全选&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0);" class="megerPay">合并付款</a></div>
					</td>
				</tr>
			</tbody></table>
		</div>
		<div id="pagebarByPayWrap" class="wrap_page">
			<div class="page">
				<p id="itemList_pageByPaybar" class="link_page"></p>
			</div>
	  </div>
		<div class="shop-history" id="o-fa">
			<table class="table-responsive">
				<thead>
					<tr>
						<th></th>
						<th>商品</th>
						<th>数量</th>
						<th>单价</th>
						<th id="staus">
							<span>全部状态</span>
						</th>
					</tr>
				</thead>
				<tbody id="ordersByFa">
					
				</tbody>
			</table>
		</div>
		<div id="pagebarByFaWrap" class="wrap_page">
			<div class="page">
				<p id="itemList_pageByFabar" class="link_page"></p>
			</div>
  		</div>
		<div class="shop-history" id="o-shou">
			<table class="table-responsive">
				<thead>
					<tr>
						<th></th>
						<th>商品</th>
						<th>数量</th>
						<th>单价</th>
						<th id="staus">
							<span>全部状态</span>
						</th>
					</tr>
				</thead>
				<tbody id="ordersByShou">
					
				</tbody>
			</table>
		</div>
		<div id="pagebarByShouWrap" class="wrap_page">
			<div class="page">
			
				<p id="itemList_pageByShoubar" class="link_page"></p>
			</div>
  		</div>
  		
  		<div class="shop-history" id="o-success">
			<table class="table-responsive">
				<thead>
					<tr>
						<th></th>
						<th>商品</th>
						<th>数量</th>
						<th>单价</th>
						<th id="staus">
							<span>全部状态</span>
						</th>
					</tr>
				</thead>
				<tbody id="ordersBySuccess">
					
				</tbody>
			</table>
		</div>
		<div id="pagebarBySuccessWrap" class="wrap_page">
			<div class="page">
			
				<p id="itemList_pageBySuccessbar" class="link_page"></p>
			</div>
  		</div>
		<!-- ---------- -->	
	</div>
		
      
         <!-- tab-content End -->
      </div>
    </div>
</div>
<!-- 合并支付form -->
<form name="megerForm" method="post" action="http://www.dindin.com/buyAction!mergeOrderToPay.go" id="megerForm">

</form>

<div class="clearfix footer_margin"></div>




  

    
    <title>My JSP 'footer.jsp' starting page</title>
    
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	-->
  
<?php include './views/foot.php'; ?>
<!-- footer 部分 -->

</div>  <!-- .outer_container ends -->
<!-- Resources dont needed until page load -->

<div id="cboxOverlay" style="display: none;"></div>
<!-- 收货成功弹框 -->










<script>
		$(function(){
			var staus = $("#staus");
			var tab_shop = staus.find(".tab-shop");
			var li = staus.find("li");
			staus.hover(function(){
				var dis = tab_shop.css("display");
				if( dis == "none" ){
					tab_shop.css({display:"block"});
				}else{
					tab_shop.css({display:"none"});
				}
			})
			li.each(function(){
				$(this).click(function(){
					var text = $(this).text();
					staus.find("span").html(text);
				})
			})
			$(".history .tab li").click(function(){
				$(this).addClass("active").siblings().removeClass("active");
			})
			
			
			var win_w = $(window).width();
			$(".shop-history table tr td a").each(function(){
				var text = $(this).text();
				// console.log(text);
				if( text == "立即购买" ){
					$(this).addClass("on");
				};
				if( text == "确认收货" || text == "立即付款" || text == "订单详情" ){
					$(this).addClass("active");
				};
				if( text == "还要买" ){
					if(win_w < 500){
						$(this).css({
							"border":"1px solid #d2d2d2",
							"background":"ececec",
							"color":"171717",
							"width":"100%",
							"margin":"0 auto"
						})
					}else{
						$(this).css({
						"border":"1px solid #d2d2d2",
						"background":"ececec",
						"color":"171717",
						"width":"60%",
						"margin":"0 auto"
						})
					}							
				}
			})
			$(window).resize(function(){
				var win_w = $(window).width();
				if( text == "还要买" ){
					if(win_w < 500){
						$(this).css({
							"border":"1px solid #d2d2d2",
							"background":"ececec",
							"color":"171717",
							"width":"100%",
							"margin":"0 auto"
						})
					}else{
						$(this).css({
						"border":"1px solid #d2d2d2",
						"background":"ececec",
						"color":"171717",
						"width":"60%",
						"margin":"0 auto"
						})
					}							
				}
			})
			$(".shop-history table tr td i").each(function(){
				$(this).click(function(){
					$(this).parents("tr").css({"display":"none"}).next("tr").css({"display":"none"});
				})
			})
			
		});
		
	</script>
<div id="cboxOverlay" style="display: none;"></div><div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none;"><div id="cboxWrapper"><div><div id="cboxTopLeft" style="float: left;"></div><div id="cboxTopCenter" style="float: left;"></div><div id="cboxTopRight" style="float: left;"></div></div><div style="clear: left;"><div id="cboxMiddleLeft" style="float: left;"></div><div id="cboxContent" style="float: left;"><div id="cboxTitle" style="float: left;"></div><div id="cboxCurrent" style="float: left;"></div><a id="cboxPrevious"></a><a id="cboxNext"></a><button id="cboxSlideshow"></button><div id="cboxLoadingOverlay" style="float: left;"></div><div id="cboxLoadingGraphic" style="float: left;"></div></div><div id="cboxMiddleRight" style="float: left;"></div></div><div style="clear: left;"><div id="cboxBottomLeft" style="float: left;"></div><div id="cboxBottomCenter" style="float: left;"></div><div id="cboxBottomRight" style="float: left;"></div></div></div><div style="position: absolute; width: 9999px; visibility: hidden; display: none; max-width: none;"></div></div></body></html>