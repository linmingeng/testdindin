//�ж��Ƿ�Ϊ��
function isNull(obj){
	var vals = $(obj).val();
	var emailReg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var phoneNumberReg = /^1[3|4|5|8|7][0-9]\d{8,8}$/;
	if(vals=='' ||vals==null){
		$(obj).css('border','1px solid rgb(243, 170, 170)');
		return null;
	}else{
		if($(obj).attr('id')=='userNumber'){
			if(emailReg.test(vals)||phoneNumberReg.test(vals)){ //ƥ���ֻ�������
				$(obj).css('border','1px solid #eaeaea');
				return true;
			}else{
				$(obj).css('border','1px solid rgb(243, 170, 170)')
				return false;
			}
		}else{
			return true;
		}
		
	}
}

function getURLVar(key) {
var value = [];
var query = String(document.location).split('?');
if (query[1]) {
var part = query[1].split('&');
for (i = 0; i < part.length; i++) {
var data = part[i].split('=');
if (data[0] && data[1]) {
value[data[0]] = data[1];
}}

if (value[key]) {
return value[key];
} else {
return '';
}}}

$(window).resize(function(){
// Hide mobile menu etc on window resize
if ($(window).width() > 767) {
$('.mini_menu .has-sub > ul').attr('style', function(i, style){return style.replace(/display[^;]+;?/g, ''); });
$('#column-left .wrapper').attr('style', function(i, style){return style.replace(/display[^;]+;?/g, ''); });
}
if ($(window).width() > 992) {
$('.main_menu .wrapper').attr('style', 
		function(i, style){
			if(style!=undefined && style!=null){
				return style.replace(/display[^;]+;?/g, ''); 
			}
		}
);
$('.main_menu .has-sub > ul').attr('style', function(i, style){return style.replace(/display[^;]+;?/g, ''); });
}
var scroll_right = 0;
if($(".container").offset()!=undefined){
	 scroll_right = $(".container").offset().left;
}
$(".scroll_top").css('right', (scroll_right - 100) + 'px');
});

$(document).ready(function() {
// Quickview //
	if($(".quickview").colorbox!=undefined){
		$(".quickview").colorbox({
			iframe:true,
			width:900,
			maxWidth:"90%",
			height:705,
			maxHeight:"90%",
			className: "quickview",
			onClosed: function() {
				//$('#cart').load('index.php?route=common/cart/info #cart > *');
				//$('#header_wishlist').load('index.php?route=common/header_wishlist_compare/info #header_wishlist');
				//$('#header_compare').load('index.php?route=common/header_wishlist_compare/info #header_compare');
			}
		});
	}

// Popup login //
/*$("#popup_login").colorbox({
className: "login",
width:370,
maxWidth:"90%",
height:390,
maxHeight:"90%",
initialWidth:"150",
initialHeight:"200"
});*/

// Equal height on product items
if($('#content .product-grid.eq_height .item').matchHeight!=undefined){
	$('#content .product-grid.eq_height .item').matchHeight();
}

// Highlight any found errors
$('.text-danger').each(function() {
var element = $(this).parent().parent();

if (element.hasClass('form-group')) {
element.addClass('has-error');
}});

/* Search */
$('.button-search').bind('click', function() {
url = 'index.php?route=product/search';
var search = $('input[name=\'search\']').prop('value');
if (search) {
url += '&search=' + encodeURIComponent(search);
}
var category_id = $('select[name=\'category_id\']').prop('value');
if (category_id > 0) {
url += '&category_id=' + encodeURIComponent(category_id);
url += '&sub_category=true';
}
location = url;
});
$('input[name=\'search\']').bind('keydown', function(e) {
if (e.keyCode == 13) {
$('.button-search').trigger('click');
}
});
$('select[name=\'category_id\']').on('change', function() {
if (this.value == '0') {
$('input[name=\'sub_category\']').prop('disabled', true);
} else {
$('input[name=\'sub_category\']').prop('disabled', false);
}});
$('select[name=\'category_id\']').trigger('change');

/* Mega Menu */
var activeurl = window.location;
$('a[href="'+activeurl+'"]').first().closest('li.top').addClass('current');

/* Avoid drop down to go outside container */
if ($(window).width() > 992) {
$('.menu_wrapper ul.categories .wrapper').each(function() {
var menu = $('.header').offset();
var dropdown = $(this).parent().offset();
var i = (dropdown.left + $(this).outerWidth()) - (menu.left - 15 + $('.header').outerWidth());
if (i > 0) {
$(this).css('margin-left', '-' + i + 'px');
}
});
}

$('.has-sub > ul').attr('style', function(i, style){return style.replace(/display[^;]+;?/g, ''); });
$('.menu_wrapper .categories .wrapper').attr('style', function(i, style){return style.replace(/display[^;]+;?/g, ''); });
$('#column-left .categories .wrapper').attr('style', function(i, style){return style.replace(/display[^;]+;?/g, ''); });

$('.v_menu_trigger').click(function() {
$('.menu_wrapper .mini_menu_trigger').removeClass('active');
$('.menu_wrapper ul.links_holder').hide();
$(this).toggleClass('active')
$('.menu_wrapper ul.categories').toggleClass('active');
});




// Move breadcrumb to header //
$('.breadcrumb').appendTo($('.breadcrumb_wrapper'));

// Fix for the header login/search field
$('.login_input').focus(function( ){
$('.login_drop_heading').stop(true,true).addClass('active');
});
$('.login_input').focusout(function( ){
$('.login_drop_heading').stop(true,true).removeClass('active');
});

$('.search_input').focus(function( ){
$('#search').stop(true,true).addClass('active');
});
$('.search_input').focusout(function( ){
$('#search').stop(true,true).removeClass('active');
$('#ajax_search_results').hide(200);
});	

// Open external links in new tab //
$('a.external').on('click',function(e){
e.preventDefault();
window.open($(this).attr('href'));
});

// Image thumb swipe  //
$(".product-list .item, .product-grid .item").hover(function() {
$(this).find(".image_hover").stop(true).fadeTo(600,1);
}, function() {
$(this).find(".image_hover").stop(true).fadeTo(300,0);
});

// Show special countdown on hover
$('.product-list .item, .product-grid .item').mousemove(function(e) {
$(this).find('.offer_popup').stop(true, true).fadeIn();
$(this).find('.offer_popup').offset({
top: e.pageY + 50,
left: e.pageX + 50
});
}).mouseleave(function() {
$('.offer_popup').fadeOut();
});



// Add correct class to footer  //
$(".footer_modules").has(".full_width_wrapper").addClass("has_full_width");
$(".footer_modules").has(".box").addClass("has_content");

// Side widgets //
//$(".side_widgets .btn-icon").click(function(){
//	if ($(this).parent().hasClass('open')) {
//		$(this).parent().removeClass('open');
//	} else {
//		$(".side_widgets .widget").removeClass('open');
//		$(this).parent().toggleClass('open');
//	}
//});

// tooltips on hover
//$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

// Makes tooltips work on ajax generated content
$(document).ajaxStop(function() {
	//$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
});
});
// ���ﳵ

//��̬���ع��ﳵ��Ϣ
		function getCartInfo(cart){
			var cartlist = '';
			$.ajax({
				type:"POST",
				url:"./shoppingAction!getViewData.go",
				//url:"./index.jsp",
				async:false,
				//dataType:'json',
				success:function(data){
					var shopCartText = JSON.parse(data);
					cartlist = shopCartText;
					//console.log(data)
					var total = 0;
					var totalcart = 0;
					if(shopCartText.order.length==0){
						//alert($('#shopcart'));
						$('#cart .content table#cartinfo tbody,#shopcart table tbody').html('<tr><td>���Ĺ��ﳵ��û����Ʒ���Ͻ�ѡ���ɣ�</td></tr>');
						$('#cart .mini-cart-total').hide();
						if(cart){
							$('#shopcart tbody').html('<tr><td colspan="7" align="center"><div><i class="" style="display:inline-block;vertical-align: middle;margin-right: 10px;background: url(./images/header-cart-blue.png) no-repeat;width: 41px;height: 41px;"></i>���Ĺ��ﳵ�ǿյ�!</div></td></tr>');
						}
						
						$('.checkoutbuttons').hide();
						$('#cart .mini-cart-total .total,#cart .cart_holder .total').text('��'+0.00);
						$('#cart .count,.cart-fixed .cartNum,.fixed-button .cartNum').html(0);
					}else{ 
						$('#cart .mini-cart-total').show();
						$('#cart .content table#cartinfo tbody').html(''); //�ÿ���ʾ
						if(cart){
							$('#shopcart tbody').html('');
						}
						
						//var count = 0;
						$('.checkoutbuttons').show();
						
						for(var i = 0,slen = shopCartText.order.length;i<slen;i++){//ѭ���ж��ٸ�����
				            var productlist = shopCartText.order[i].commodity; //ÿ��������Ʒ����
				            for(var k = 0,plen = productlist.length; k < plen; k ++){ //ѭ����Ʒ
								if(cart){ //����������������乺�ﳵҳ������
									
									var shopcart = '<tr>';
									shopcart +='<td class="image">';
									shopcart +='<a id='+productlist[k].cid+' href="./goodsDetail_id_'+productlist[k].cid+'.htm">';
									shopcart +='<img src='+productlist[k].image+' alt='+productlist[k].cName+' title='+productlist[k].cName+'></a>';
									shopcart +='</td>';
									shopcart +='<td class="name"><a href="./goodsDetail_id_'+productlist[k].cid+'.htm">'+productlist[k].cName+'</a><br>';
									if(productlist[k].goodSpecificationStr!=null&&productlist[k].goodSpecificationStr!='null'&&productlist[k].goodSpecificationStr!=''){
										shopcart +='<span style="font-size:12px;">'+productlist[k].goodSpecificationStr +'</span>';
									}
									
									shopcart +='</td>';
									//shopcart +='<td class="model mobile_hide">'+productlist[k].cid+'</td>';
									var number = parseInt(productlist[k].num); //����
									if(productlist[k].fdjglist!=''){ //����зֶμۣ�ȡ�����ͷֶμ���������Ƚ�ȡ��Ӧ�۸�
										var subtotal = 0;
										for(var j = 0,jlen = productlist[k].fdjglist.length; j < jlen; j ++){ //�ֶμ�ѭ��
											if(number>=parseInt(productlist[k].fdjglist[j].beginNum)){
												subtotal = number*productlist[k].fdjglist[j].fdjg;//С��
												totalcart =parseFloat(totalcart+ subtotal);
												shopcart +='<td class="unit_price">��'+productlist[k].fdjglist[j].fdjg+'</td>';
												shopcart +='<td class="quantity">';
												shopcart +='<input type="text" name="quantity" value='+number+' size="1" class="quantity" data-id='+productlist[k].cid+'>';
												shopcart +='</td>';
												shopcart +='<td class="price total">��'+subtotal.toFixed(2)+'</td>';
												break;
											}
										}
										
									}else{ //���û�зֶμ�
										subtotal = (number*(productlist[k].price))/100;
										totalcart += parseFloat(subtotal);
										shopcart +='<td class="unit_price">��'+(productlist[k].price)/100+'</td>';
										shopcart +='<td class="quantity">';
										shopcart +='<input style="width:43px;" type="text" name="quantity" value='+number+' size="1" class="quantity" data-id='+productlist[k].cid+'>';
										shopcart +='</td>';
										shopcart +='<td class="price total">��'+subtotal.toFixed(2)+'</td>';
									}
									
									shopcart +='<td class="remove">';
									//shopcart +='<a onclick="$(&#39;#basket&#39;).submit();" data-toggle="tooltip" title="" class="btn btn-dark btn-icon-sm" data-original-title="Update"><i class="fa fa-refresh"></i></a>';
									shopcart +='<a onclick="cart.remove('+productlist[k].cid+');" data-toggle="tooltip" title="" class="btn btn-dark btn-icon-sm" data-original-title="Remove"><i class="fa fa-times"></i></a>';
									shopcart +='</td>';
									shopcart +='</tr>';
									
									$('#shopcart tbody').append(shopcart);
									
								}else{	 //���ͷ�����ﳵ����	
									var cartstr = '<tr data-id='+productlist[k].cid+'>';
									cartstr +='<td class="image border">';
									cartstr +='<a id='+productlist[k].cid+' href="./goodsDetail_id_'+productlist[k].cid+'.htm">';
									cartstr +='<img src='+productlist[k].image+' alt='+productlist[k].cName+' title='+productlist[k].cName+' width="60" height="60"></a>';
									cartstr +='</td>';
									cartstr +='<td class="name border">';
									cartstr +='<a class="contrast_font" href="./goodsDetail_id_'+productlist[k].cid+'.htm" class="goodsname">'+productlist[k].cName+'</a>';
									var number = parseInt(productlist[k].num); //����
									if(productlist[k].fdjglist!=''){ //����зֶμۣ�ȡ�����ͷֶμ���������Ƚ�ȡ��Ӧ�۸�										
										var subtotal = 0;
										for(var j = 0,jlen = productlist[k].fdjglist.length; j < jlen; j ++){ //�ֶμ�ѭ��
											if(number>=parseInt(productlist[k].fdjglist[j].beginNum)){
												subtotal = number*parseFloat(productlist[k].fdjglist[j].fdjg);//С��
												cartstr +=number+'&nbsp;x&nbsp;��'+productlist[k].fdjglist[j].fdjg+'<div>';
												break;
											}
										}
										
									}else{ //���û�зֶμ�
										subtotal = parseFloat((number*productlist[k].price)/100);
										cartstr +='<span class="pronum">'+number+'</span>&nbsp;x&nbsp;��<span class="proprice">'+productlist[k].price/100+'</span>';
									}
									total += subtotal;
									cartstr +='</div></td>';
									cartstr +='<td class="remove border"><a class="abc" id='+productlist[k].cid+' title="Remove" onclick="cart.remove('+productlist[k].cid+')"><i class="fa fa-times"></i></a></td>';
									cartstr +='</tr>';
									$('#cart .content table#cartinfo tbody').append(cartstr);
								}
				            }
				           
						}
						$('#cart .count,.cart-fixed .cartNum,.fixed-button .cartNum').html(shopCartText.count);
						$('#cart .mini-cart-total .total,#cart .cart_holder .total').text('��'+total.toFixed(2));
						if(cart){
							$('#shopcart tbody').append('<tr><td colspan="7" align="right" style="font-size:20px;color: red;">�ܼƣ���<span class="totalcart">'+totalcart.toFixed(2)+'</span></td></tr>');
						}
					}
				},
				error:function(XMLHttpRequest){
					//alert(arguments[1]);
					//alert(XMLHttpRequest.status); // 200
	                //alert(XMLHttpRequest.readyState); // 4
	                //alert(textStatus);
				}
			})
			return cartlist;
		}

var cart = {
	//���빺�ﳵ
	'add': function(product_id, quantity, imgsrc,flag,addagain) {
		var cart = $.cookie('cart');
		var cart_info = eval('('+cart+')');
		if(cart_info == null){
			var cart_info = {};
		}
		if(cart_info[product_id] == undefined){
            cart_info[product_id] = quantity;
		}else{
            cart_info[product_id] = parseInt(cart_info[product_id])+parseInt(quantity);
		}
		var count = 0;
		for(goodsid in cart_info){
            var qua = parseInt(cart_info[goodsid]);
            count = count+qua;
		}
		$('.count').html(count);
		var car_str = JSON.stringify(cart_info);
        $.cookie('cart',car_str);
		return ;
		//var imgsrc = $(this).parent().parent().parent().next().find('img').attr('src');
		//var proname = 'Ipsum Dolor Adipiscing 15, 2.5 fl oz (75ml)';
		//$('#cboxContent').html('<div class="cart_notification"><div class="product"><img src="' + imgsrc + '"/><span>' + proname + '</span></div><div class="bottom"><a class="btn btn-default" href="../shopping_cart.html">去购物车</a><a class="btn btn-primary" href="../checkout.html">购买</a></div></div>');
		var proper = '';
		var properid = '';
		
		if(flag=='detail'){
			if($('#propertys .property-item').length>0){ //��ʾ������
				//��ȡ����
				proper += '<span>';
				if($("#info_message:has(span)").length>0){
					$('#propertys .property-item').each(function(){
						proper+=$(this).find('span.active').attr('title')+'['+$(this).find('span.active').text()+']'
					})
					proper +='</span>'
				}
				//alert(proper+'-------'+encodeURI(encodeURI(proper)))
				proper = encodeURI(encodeURI(proper));
				properid = $('#goodSpecificationId').val();
			}
			
		}
		//alert(proper)
		var urls = '';
		if(addagain==true){
			urls = "./shoppingAction!add.go?commlist="+product_id+","+proper+","+quantity+","+properid+"&type=1&t="+Math.random();
		}else{
			urls = "./shoppingAction!add.go?commlist="+product_id+","+proper+","+quantity+","+properid+"&type=0&t="+Math.random();
		}
		$.ajax({
			url:urls,
			type: 'post',
			//data: '<span>��ɫ[��ɫ]����[M]</span>',
			success: function(json) {
				var data = JSON.parse(json);
				if(data.flag=='false'){
					if(data.errCode=='err002'){//���ﳵ����ͬ��Ʒ
						//$('#colorbox1 .product');
						$('#colorbox1 .product').html('���ﳵ������ͬ��Ʒ����ϣ����').css('padding-top','50px');
						$('#colorbox1 .bottom.same a').eq(0).remove();
						$('#colorbox1 .bottom.same').prepend('<a class="btn btn-primary addagain" href="javascript:void(0)" onclick="cart.add('+product_id+','+quantity+',\''+imgsrc+'\',\''+flag+'\',true)">����һ��</a>');
						$('#colorbox1 .bottom.same').show();
						$('#colorbox1 .bottom.gotodetail').hide();
						$('#colorbox1 .bottom.success').hide();
						$('#cboxOverlay').css('opacity','0.9').fadeIn();
						$('#colorbox1 .cart_notification img').attr('src',imgsrc);
						$('#colorbox1').fadeIn();
						//getCartInfo();//���»�ȡ���ﳵ��Ϣ
						return;
					}
					else if(data.errCode=='err001'){
						//alert($('#colorbox1'))
						$('#colorbox1 .product').html('����Ʒ�й�����ԣ��뵽����ҳ���');
						$('#colorbox1 .product').css('padding-top','50px');
						$('#colorbox1 .bottom.gotodetail a').eq(0).remove();
						$('#colorbox1 .bottom.gotodetail').prepend('<a class="btn btn-primary" href="javascript:void(0)"  onclick="$(\'#colorbox1\').hide();$(\'#cboxOverlay\').hide();$(this).attr(\'href\',\'goodsDetail_id_'+product_id+'.htm\');" target="_blansk">ȥ��������ҳ��</a>');
						$('#colorbox1 .bottom.gotodetail').show();
						$('#colorbox1 .bottom.same').hide();
						$('#colorbox1 .bottom.success').hide();
						$('#cboxOverlay').css('opacity','0.9').fadeIn();
						$('#colorbox1 .cart_notification img').attr('src',imgsrc);
						$('#colorbox1').fadeIn();
						
						return;
						//$('#colorbox1 .same').next().hide();
					}//��ʾδѡ�����ԣ�������ҳ
					else if(data.errCode=='err003'){
						var infostr = '';
					infostr +='<img src='+imgsrc+' width="80" style="height:80px!important;">';
					infostr +='<span style="display:block;padding-top:34px;">����Ʒ��治��</span>';
						//$('#colorbox1 .product').html(infostr);
						$('#colorbox1 .product').css('padding-top','25px');
						$('#colorbox1 .product').html('').append(infostr);
						$('#colorbox1 .bottom.gotodetail a').eq(0).remove();
						$('#colorbox1 .bottom.gotodetail').prepend('<a href="javascript:void(0)"   target="_blansk"></a>');
						$('#colorbox1 .bottom.gotodetail').show();
						$('#colorbox1 .bottom.same').hide();
						$('#colorbox1 .bottom.success').hide();
						$('#cboxOverlay').css('opacity','0.9').fadeIn();
						$('#colorbox1 .cart_notification img').attr('src',imgsrc);
						$('#colorbox1').fadeIn();
				}
				}else{
					var infostr = '';
					infostr +='<img src='+imgsrc+' width="80" style="height:80px!important;">';
					infostr +='<span style="display:block;padding-top:34px;">���ѳɹ����';
					//infostr +='Ipsum Dolor Adipiscing 15, 2.5 fl oz (75ml)</a>';
					infostr +='������<a href="./shopping_cart.jsp" target="_blank">���ﳵ</a>!</span>';
					$('#colorbox1 .product').css('padding-top','25px');
					$('#colorbox1 .product').html('').append(infostr);
					$('#colorbox1 .bottom.success').show();
					$('#colorbox1 .bottom.same').hide();
					$('#colorbox1 .bottom.gotodetail').hide();
					$('#cboxOverlay').css('opacity','0.9').fadeIn();
					$('#colorbox1 .cart_notification img').attr('src',imgsrc);
					$('#colorbox1').fadeIn();
					getCartInfo();//���»�ȡ���ﳵ��Ϣ
				}
				
				
			}
			});
		
	},
	'update': function(key, quantity) {
		$.ajax({
		url: 'index.php?route=checkout/cart/edit',
		type: 'post',
		data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
		dataType: 'json',
		beforeSend: function() {
		$('#cart > button').button('loading');
		},
		success: function(json) {
		$('#cart > button').button('reset');
		$('#cart-total').html(json['total']);
		if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
		location = 'index.php?route=checkout/cart';
		} else {
		$('#cart').load('index.php?route=common/cart/info #cart > *'); //Added
		}}
		});
	},
	//ɾ�����ﳵ
	'remove': function(id,price) {
		//删除商品cookie
        var cart = $.cookie('cart');
        var cart_info = eval('('+cart+')');
        var count = 0;
        for(goodsid in cart_info){
        	if(goodsid==id){
        		delete cart_info[goodsid];
			}
        }
        var car_str = JSON.stringify(cart_info);
        $.cookie('cart',car_str);
        //刷新当前页
        location.reload();

		// var _this = $(this);
		// $.ajax({
		// 	url: './shoppingAction!del.go',
		// 	type: 'post',
		// 	data: 'commlist=' + id+','+Math.random(),
		// 	//dataType: 'json',
		// 	//beforeSend: function() {
		// 	//$('#cart > button').button('loading');
		// 	//},
		// 	//dataType : 'json',
		// 	success: function(datass) {
		// 	var data = JSON.parse(datass);
		// 		if(data.flag=='true'){
		// 			location.reload(); //ˢ�µ�ǰҳ��
		// 			//getCartInfo(); //���»�ȡ���ﳵ��Ϣ
        //
		// 		}else{
		// 			alert('ɾ��ʧ�ܣ�������ˢ��ҳ��');
		// 		}
		// 	}
		//
		// });
	}
}
//��Ʒ����������ȡ
var nameSubStr=function(name,number){
	name.each(function(index,ele){
		var text  = $(ele).text();
		if(text.length>number){
			$(ele).text(text.substr(0,number)+'...');
		}
		$(ele).attr('title',text);
	})
	
}
var voucher = {
'add': function() {
},
'remove': function(key) {
$.ajax({
url: 'index.php?route=checkout/cart/remove',
type: 'post',
data: 'key=' + key,
dataType: 'json',
beforeSend: function() {
$('#cart > button').button('loading');
},
complete: function() {
$('#cart > button').button('reset');
},
success: function(json) {
$('#cart-total').html(json['total']);
if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
location = 'index.php?route=checkout/cart';
} else {
$('#cart').load('index.php?route=common/cart/info #cart > *'); //Added
}
}
});
}
}

var wishlist = {
'add': function(product_id) {
$.ajax({
url: 'index.php?route=account/wishlist/add',
type: 'post',
data: 'product_id=' + product_id,
dataType: 'json',
success: function(json) {
$('.alert').remove();
if (json['success']) {
$.colorbox({
html:'<div class="cart_notification"><div class="product"><img src="' + json['image'] + '"/><span>' + json['success'] + '</span></div><div class="bottom"><a class="btn btn-primary" href="' + json['link_wishlist'] + '">' + json['text_wishlist'] + '</a></div></div>',
className: "login",
initialHeight:50,
initialWidth:50,
width:"90%",
maxWidth:400,
height:"90%",
maxHeight:200
});
}

if (json['info']) {
$.colorbox({
html:'<div class="cart_notification"><div class="product"><img src="' + json['image'] + '"/><span>' + json['info'] + '</span></div><div class="bottom"><a class="btn btn-primary" href="' + json['link_wishlist'] + '">' + json['text_wishlist'] + '</a></div></div>',
className: "login",
initialHeight:50,
initialWidth:50,
width:"90%",
maxWidth:400,
height:"90%",
maxHeight:200
});
}}
});
},
'remove': function() {
}
}

var compare = {
'add': function(product_id) {
$.ajax({
url: 'index.php?route=product/compare/add',
type: 'post',
data: 'product_id=' + product_id,
dataType: 'json',
success: function(json) {
$('.alert').remove();

if (json['success']) {
$.colorbox({
html:'<div class="cart_notification"><div class="product"><img src="' + json['image'] + '"/><span>' + json['success'] + '</span></div><div class="bottom"><a class="btn btn-primary" href="' + json['link_compare'] + '">' + json['text_compare'] + '</a></div></div>',
className: "login",
initialHeight:50,
initialWidth:50,
width:"90%",
maxWidth:400,
height:"90%",
maxHeight:200
});
$('#compare-total').html(json['total']);
$('#header_compare').html(json['compare_total']);
}}
});
},
'remove': function() {
}}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
e.preventDefault();
$('#modal-agree').remove();
var element = this;
$.ajax({
url: $(element).attr('href'),
type: 'get',
dataType: 'html',
success: function(data) {
html  = '<div id="modal-agree" class="modal">';
html += '  <div class="modal-dialog">';
html += '    <div class="modal-content">';
html += '      <div class="modal-header">';
html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
html += '      </div>';
html += '      <div class="modal-body">' + data + '</div>';
html += '    </div';
html += '  </div>';
html += '</div>';
$('body').append(html);
$('#modal-agree').modal('show');
}
});
});

// Autocomplete */
(function($) {
$.fn.autocomplete = function(option) {
return this.each(function() {
this.timer = null;
this.items = new Array();

$.extend(this, option);
$(this).attr('autocomplete', 'off');

// Focus
$(this).on('focus', function() {
this.request();
});

// Blur
$(this).on('blur', function() {
setTimeout(function(object) {
object.hide();
}, 200, this);				
});

// Keydown
$(this).on('keydown', function(event) {
switch(event.keyCode) {
case 27: // escape
this.hide();
break;
default:
this.request();
break;
}				
});

// Click
this.click = function(event) {
event.preventDefault();
value = $(event.target).parent().attr('data-value');
if (value && this.items[value]) {
this.select(this.items[value]);
}}

// Show
this.show = function() {
var pos = $(this).position();

$(this).siblings('ul.dropdown-menu').css({
top: pos.top + $(this).outerHeight(),
left: pos.left
});

$(this).siblings('ul.dropdown-menu').show();
}

// Hide
this.hide = function() {
$(this).siblings('ul.dropdown-menu').hide();
}		

// Request
this.request = function() {
clearTimeout(this.timer);

this.timer = setTimeout(function(object) {
object.source($(object).val(), $.proxy(object.response, object));
}, 200, this);
}

// Response
this.response = function(json) {
html = '';

if (json.length) {
for (i = 0; i < json.length; i++) {
this.items[json[i]['value']] = json[i];
}

for (i = 0; i < json.length; i++) {
if (!json[i]['category']) {
html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
}
}

// Get all the ones with a categories
var category = new Array();

for (i = 0; i < json.length; i++) {
if (json[i]['category']) {
if (!category[json[i]['category']]) {
	category[json[i]['category']] = new Array();
	category[json[i]['category']]['name'] = json[i]['category'];
	category[json[i]['category']]['item'] = new Array();
}

category[json[i]['category']]['item'].push(json[i]);
}}

for (i in category) {
html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

for (j = 0; j < category[i]['item'].length; j++) {
html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
}}}

if (html) {
this.show();
} else {
this.hide();
}

$(this).siblings('ul.dropdown-menu').html(html);
}

$(this).after('<ul class="dropdown-menu"></ul>');
$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));	

});
}
})(window.jQuery);