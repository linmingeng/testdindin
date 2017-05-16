<!DOCTYPE html>
<!-- saved from url=(0039)http://www.dindin.com/shopping_cart.jsp -->
<html dir="ltr" lang="en"><!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>购物车</title>
<!--<base href="http://velikorodnov.com/opencart/shopme/demo5/">--><!--<base href=".">-->
<base href=".">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="http://www.dindin.com/" rel="icon">
<!-- Version 2.0.3 -->
<!-- <script id="facebook-jssdk" src="http://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.3&appId=109031762530738"></script> -->
<script type="text/javascript" async="" src="./js/mv.js"></script>
<script type="text/javascript" async="" src="./js/mba.js"></script>
<script type="text/javascript" async="" src="./js/mvl.js"></script>
<script src="./js/hm.js"></script>
<script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>

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

<script type="text/javascript" src="./js/spin.min.js"></script><style type="text/css"></style>
<script type="text/javascript" src="./js/tweetfeed.min.js"></script>

<!-- Custom css -->
<!-- Custom script -->

<!-- Custom styling -->
<!-- Custom fonts -->
<!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,900" media="screen"> -->
</head>
<body class="checkout-cart style-3 "><iframe src="./image/b.html" id="mediav_cookiemapping" style="width: 1px; border: 0px; position: absolute; left: -100px; top: -100px; height: 1px;"></iframe>
<!-- Cookie Control -->
<!-- Old IE Control -->
<div class="outer_container" id="cont-container">
<!-- header 部分 -->
    <?php include './views/head.php';?>
  


<div class="breadcrumb_wrapper container"><ul class="breadcrumb">
        <li><a href="http://www.dindin.com/index.html">首页</a></li>
        <li><a href="http://www.dindin.com/shopping_cart.html">购物车</a></li>
      </ul></div>
<div id="notification" class="container"></div><div class="container">
  
        <div class="row">
  
      
                <div id="content" class="col-sm-12">
      <h1 style="font-size:19px;">请确认购物车商品并点击按钮购买
              </h1>
      <form action="http://www.dindin.com/#" method="post" enctype="multipart/form-data" id="basket">
    	<div class="cart-info">
    		<div class="table-responsive">
                <table cellspacing="0" id="shopcart">
                    <thead>
                    <tr>
                        <td class="image ">图片</td>
                        <td class="name">商品名称</td>
                        <td class="price ">价格</td>
                        <td class="quantity">数量</td>
                        <td class="total ">总计</td>
                        <td class="remove ">删除</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?PHP
                        if(empty($ret['goods_list'])){
                            echo '<tr><td colspan="7" align="center"><div><i class="" style="display:inline-block;vertical-align: middle;margin-right: 10px;background: url(./images/header-cart-blue.png) no-repeat;width: 41px;height: 41px;"></i>您的购物车是空的!</div></td></tr>';

                        }else{
                            foreach($ret['goods_list'] as $goods){
                                echo '<tr id="goods_'.$goods['goodsid'].'"><td class="image">
                            <a id="'.$goods['goodsid'].'" href="?goodsDetail/view/goodsid/'.$goods['goodsid'].'"><img src="'.$goods['image'].'" alt="'.$goods['name'].'" title="'.$goods['name'].'"></a>
                        </td>
                        <td class="name">
                            <a href="?goodsDetail/view/goodsid/'.$goods['goodsid'].'">'.$goods['name'].'</a><br></td><td class="unit_price">￥'.$goods['price'].'</td><td class="quantity">
                            <input style="width:43px;" type="text" name="quantity" value="'.$goods['quantity'].'" size="1" class="quantity" data-id="'.$goods['goodsid'].'">
                        </td>
                        <td class="price total">￥<span id="'.$goods['goodsid'].'_total_price">'.$goods['goods_total_price'].'</span></td>
                        <td class="remove"><a onclick="cart.remove('.$goods['goodsid'].','.$goods['price'].');" data-toggle="tooltip" title="" class="btn btn-dark btn-icon-sm" data-original-title="Remove"><i class="fa fa-times"></i></a></td>
                    </tr>
                    <input type="hidden" id="'.$goods['goodsid'].'_price" value="'.$goods['price'].'">';
                            }
                            echo '<tr><td colspan="7" align="right" style="font-size:20px;color: red;">总计：￥<span class="totalcart">'.$ret['total_price'].'</span></td></tr>';
                        }
                    ?>

                    </tbody>
                </table>
	         </div>
          <div class="table_bottom_line">
          <a href="?index" class="btn btn-primary">继续购物</a>
		  <div class="pull-right">
		  <a href="javascript:void(0)" class="btn btn-primary topayment">去结算</a></div>
          </div>
         </div>
      </form>
      <div class="row">
      <div class="col-sm-8">
            <div class="row">
     
  
<script type="text/javascript"><!--
$('#button-coupon').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/coupon/coupon',
		type: 'post',
		data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
		dataType: 'json',
		beforeSend: function() {
			$('#button-coupon').button('loading');
		},
		complete: function() {
			$('#button-coupon').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content').parent().before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script>



       
        
<script type="text/javascript"><!--
$('#button-voucher').on('click', function() {
  $.ajax({
    url: 'index.php?route=checkout/voucher/voucher',
    type: 'post',
    data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
    dataType: 'json',
    beforeSend: function() {
      $('#button-voucher').button('loading');
    },
    complete: function() {
      $('#button-voucher').button('reset');
    },
    success: function(json) {
      $('.alert').remove();

      if (json['error']) {
        $('#content').parent().before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        $('html, body').animate({ scrollTop: 0 }, 'slow');
      }

      if (json['redirect']) {
        location = json['redirect'];
      }
    }
  });
});
//--></script>

<script type="text/javascript"><!--
$('#button-quote').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/shipping/quote',
		type: 'post',
		data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val()),
		dataType: 'json',
		beforeSend: function() {
			$('#button-quote').button('loading');
		},
		complete: function() {
			$('#button-quote').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['error']) {
				if (json['error']['warning']) {
					$('#content').parent().before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}

				if (json['error']['country']) {
					$('select[name=\'country_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>');
				}

				if (json['error']['zone']) {
					$('select[name=\'zone_id\']').after('<div class="text-danger">' + json['error']['zone'] + '</div>');
				}

				if (json['error']['postcode']) {
					$('input[name=\'postcode\']').after('<div class="text-danger">' + json['error']['postcode'] + '</div>');
				}
			}

			if (json['shipping_method']) {
				$('#modal-shipping').remove();

				html  = '<div id="modal-shipping" class="modal">';
				html += '  <div class="modal-dialog">';
				html += '    <div class="modal-content">';
				html += '      <div class="modal-header">';
				html += '        <h4 class="modal-title">Please select the preferred shipping method to use on this order.</h4>';
				html += '      </div>';
				html += '      <div class="modal-body">';

				for (i in json['shipping_method']) {
					html += '<p><strong>' + json['shipping_method'][i]['title'] + '</strong></p>';

					if (!json['shipping_method'][i]['error']) {
						for (j in json['shipping_method'][i]['quote']) {
							html += '<div class="radio">';
							html += '  <label>';

							if (json['shipping_method'][i]['quote'][j]['code'] == '') {
								html += '<input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" checked="checked" />';
							} else {
								html += '<input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" />';
							}

							html += json['shipping_method'][i]['quote'][j]['title'] + ' - ' + json['shipping_method'][i]['quote'][j]['text'] + '</label></div>';
						}
					} else {
						html += '<div class="alert alert-danger">' + json['shipping_method'][i]['error'] + '</div>';
					}
				}

				html += '      </div>';
				html += '      <div class="modal-footer">';
				html += '        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>';

								html += '        <input type="button" value="Apply Shipping" id="button-shipping" data-loading-text="Loading..." class="btn btn-primary" disabled="disabled" />';
				
				html += '      </div>';
				html += '    </div>';
				html += '  </div>';
				html += '</div> ';

				$('body').append(html);

				$('#modal-shipping').modal('show');

				$('input[name=\'shipping_method\']').on('change', function() {
					$('#button-shipping').prop('disabled', false);
				});
			}
		}
	});
});

$(document).delegate('#button-shipping', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/shipping/shipping',
		type: 'post',
		data: 'shipping_method=' + encodeURIComponent($('input[name=\'shipping_method\']:checked').val()),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping').button('loading');
		},
		complete: function() {
			$('#button-shipping').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content').parent().before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=checkout/shipping/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {

			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""> --- Please Select --- </option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '689') {
						html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"> --- None --- </option>';
			}

			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script>      </div>
            </div>
      
      </div>
      </div>
    </div>
</div>


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
<script type="text/javascript" src="./js/searchAutoComplate.js"></script>
<script>
$(document).ready(function(){
	$(document).bind('keydown', function(ev) {
		try {
			if( ev.keyCode == 13 && $('.selected').length > 0 ) {
				if($('.selected').find('a').first().attr('href')){
					//document.location.href = $('.selected').find('a').first().attr('href');
				}
			}
		}
		catch(e) {}
	});
});
var cartlist = getCartInfo('cart');//获取购物车信息添加到页面
$('.shopcart-count').text(cartlist.count);
$('#shopcart input.quantity').blur(function(){
	var proid = $(this).attr('data-id');
	var num = $(this).val();
	//var total = 0;
	if(!isNaN(num)){
		num = parseInt($(this).val());
        var cart = $.cookie('cart');
        var cart_info = eval('('+cart+')');
        var count = 0;
		if(num>0){

            var diff_num = 0;
            for(goodsid in cart_info){
                if(goodsid == proid){
                    diff_num = num - cart_info[goodsid];
                    cart_info[goodsid] = num;
                }
            }
            var car_str = JSON.stringify(cart_info);
            $.cookie('cart',car_str);
            if(diff_num != 0){
                getNewPrice(diff_num,proid,$(this));
            }
	  		//changePrice($(events.target));	
	    }else{
			alert('数量必须为正整数');
		}
	}else{
		alert('数量必须为数字');
	}
})
//$('#shopcart input.quantity').keypress(function(events){
//	if(event.keyCode == "13"){
//		var proid = $(events.target).attr('data-id');
//		var num = $(events.target).val();
//		//var total = 0;
//		if(!isNaN(num)){
//			num = parseInt($(events.target).val());
//			if(num>0){
//				getNewPrice(num,proid,$(events.target));
//		  		//changePrice($(events.target));
//		    }else{
//				alert('数量必须为正整数');
//			}
//		}else{
//			//getNewPrice(1,proid,$(events.target));
//			//$(this).val(1);
//			alert('数量必须为数字');
//		}
//		return false;
//	}
//});
$('.topayment').click(function(){
    var cart = $.cookie('cart');
    var cart_info = eval('('+cart+')');
    if(cart_info == null || JSON.stringify(cart_info) == '{}'){
        alert('请先选择需要购买的商品！');
        return ;
    }
	var flag = userLogin.isLogin();
	if(flag){
//		location.href="./shoppingAction!confirmdeal.go";
        location.href="?/order/confirm";
	}else{
		userLogin.loginAlert({action:function(){
//				location.href="./shoppingAction!confirmdeal.go";
//				location.href="?/order/confirm";
//                location.reload();
			}}
		);
	}
	
})
function getNewPrice(num,proid,obj){
	var pronum = num;
	var _that = obj;
//	var total=0;
//	$('#cartinfo tr[data-id='+proid+']').find('.pronum').text(pronum); //更新头部购物车数量
//	if(num<=getMaxBuy(proid)){ //如果数量小于限购数才执行
//		if(obj.next()){
//			obj.next().remove();
//		}
//
//	}else{
//		pronum = getMaxBuy(proid);
//		pronum = proid;
//		if(obj.parent().find('div').length<=0){ //判断是否已有提示
//			var str = '<div style="color: red;font-size: 12px;margin-top: 5px;">最多可买'+getMaxBuy(proid)+'件</div>';
//			obj.parent().append(str);
//		}
//		num = pronum; //设置成限购数
		//alert('此商品每个用户仅限购'+cartlist.order[i].commodity[k].maxBuy+'件');
//		obj.val(getMaxBuy(proid)); //设置成最大限购数
//	}
    //价格变动量
    var p_price = parseFloat($('#'+proid+'_price').val(),2);//商品价格
    var p_total_price = parseFloat($('#'+proid+'_total_price').html(),2);//商品小计
    var total_price = parseFloat($('.totalcart').html(),2);//商品总计
    p_total_price = (p_total_price+(Number(p_price*num))).toFixed(2);
    total_price = (total_price+(Number(p_price*num))).toFixed(2);
//    p_total_price = p_total_price+p_price*num;
//    total_price = total_price+p_price*num;

    $('#'+proid+'_total_price').html(p_total_price);//商品小计
    $('.totalcart').html(total_price);//商品小计
    return ;
//	$('#cartinfo tr[data-id='+proid+']').find('.proprice').text(11); //更新头部购物车价格
//	obj.parent().parent().find('.price.total').text('￥');//小计
//	obj.parent().parent().find('.unit_price').text('￥22'); //价格
//	$.ajax({
//		type:'post',
//		url:'./shoppingAction!modifyNum.go',
//		data:'commlist='+proid+',,'+num,
//		async:false,
//		success:function(data){
//
//		}
//	})
//	//获取总价
//	$('#shopcart .price.total').each(function(){
//		var text = $(this).text();
//		total += parseFloat(text.substr(1,text.length));
//	})
//	$('#shopcart .totalcart').text(total.toFixed(2)); //总价
//	$('#cart-total .total,#cart .mini-cart-total .total').text('￥'+total.toFixed(2));//头部购物车总价
//	var count = 0;
//	$('#shopcart input.quantity').each(function(){
//		count += parseInt($(this).val());
//	})
//	$('#cart .cart_holder .count').text(count);
//	$('.shopcart-count').text(count);
}

//获取价格
function getPrice(num,id){
	for(var i = 0,carlen = cartlist.order.length;i<carlen;i++){
		for(var k = 0;k<cartlist.order[i].commodity.length;k++){
			if(cartlist.order[i].commodity[k].cid==id){
				var flist = cartlist.order[i].commodity[k] ;//多个商品
				if(flist.fdjglist!=''){
					for(var j = 0; j < flist.fdjglist.length; j ++){ //分段价循环
						if(num>=parseInt(flist.fdjglist[j].beginNum)){
							return flist.fdjglist[j].fdjg;
						}
					}
				}else{
					return flist.price/100;
				}
			}
		}
	}
}

//获取限购数
function getMaxBuy(id){
	for(var i = 0,carlen = cartlist.order.length;i<carlen;i++){
		for(var k = 0;k<cartlist.order[i].commodity.length;k++){
			var flist = cartlist.order[i].commodity[k] ;//多个商品
			if(cartlist.order[i].commodity[k].cid==id){
				return cartlist.order[i].commodity[k].maxBuy;
			}
		}
	}
}

</script>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
 <div id="cboxOverlay" style="display: none;"></div>

 

<div id="cboxOverlay" style="display: none;"></div><div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none;"><div id="cboxWrapper"><div><div id="cboxTopLeft" style="float: left;"></div><div id="cboxTopCenter" style="float: left;"></div><div id="cboxTopRight" style="float: left;"></div></div><div style="clear: left;"><div id="cboxMiddleLeft" style="float: left;"></div><div id="cboxContent" style="float: left;"><div id="cboxTitle" style="float: left;"></div><div id="cboxCurrent" style="float: left;"></div><a id="cboxPrevious"></a><a id="cboxNext"></a><button id="cboxSlideshow"></button><div id="cboxLoadingOverlay" style="float: left;"></div><div id="cboxLoadingGraphic" style="float: left;"></div></div><div id="cboxMiddleRight" style="float: left;"></div></div><div style="clear: left;"><div id="cboxBottomLeft" style="float: left;"></div><div id="cboxBottomCenter" style="float: left;"></div><div id="cboxBottomRight" style="float: left;"></div></div></div><div style="position: absolute; width: 9999px; visibility: hidden; display: none; max-width: none;"></div></div></body></html>