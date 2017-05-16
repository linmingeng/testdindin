$(function(){
	var countryArr = [
		{id:7300101,bigsrc:'./images/country/country_big_1.jpg',bighref:'http://www.dindin.com/goodsDetail_id_7560.htm',righttopsrc:'./images/country/country_rightt_1.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_7570.htm',rightbtsrc:'./images/country/country_rightb_1.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_5227.htm'},
		{id:7200102,bigsrc:'./images/country/sebamed.jpg',bighref:'http://www.dindin.com/goodsDetail_id_13739.htm',righttopsrc:'./images/country/Schaebens.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_14208.htm',rightbtsrc:'./images/country/Theramed.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_15466.htm'},
		{id:7200103,bigsrc:'./images/country/country_big_3.jpg',bighref:'http://www.dindin.com/goodsDetail_id_10227.htm',righttopsrc:'./images/country/country_rightt_3.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_4879.htm',rightbtsrc:'./images/country/country_rightb_3.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_4753.htm'},
		{id:7200104,bigsrc:'./images/country/country_big_4.jpg',bighref:'http://www.dindin.com/goodsDetail_id_14305.htm',righttopsrc:'./images/country/country_rightt_4.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_14500.htm',rightbtsrc:'./images/country/country_rightb_4.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_14434.htm'},
		{id:7100102,bigsrc:'./images/country/country_big_5.jpg',bighref:'http://www.dindin.com/goodsDetail_id_14693.htm',righttopsrc:'./images/country/country_rightt_5.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_14654.htm',rightbtsrc:'./images/country/country_rightb_5.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_14200.htm'},
		{id:7400102,bigsrc:'./images/country/country_big_6.jpg',bighref:'http://www.dindin.com/goodsDetail_id_8654.htm',righttopsrc:'./images/country/country_rightt_6.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_8783.htm',rightbtsrc:'./images/country/country_rightb_6.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_8800.htm'},
		{id:7100201,bigsrc:'./images/country/country_big_7.jpg',bighref:'http://www.dindin.com/goodsDetail_id_15057.htm',righttopsrc:'./images/country/country_rightt_7.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_14018.htm',rightbtsrc:'./images/country/country_rightb_7.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_14316.htm'},
		{id:7100202,bigsrc:'./images/country/country_big_8.jpg',bighref:'http://www.dindin.com/goodsDetail_id_12243.htm',righttopsrc:'./images/country/country_rightt_8.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_12219.htm',rightbtsrc:'./images/country/country_rightb_8.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_10790.htm'},
		{id:7200101,bigsrc:'./images/country/country_big_9.jpg',bighref:'http://www.dindin.com/goodsDetail_id_10384.htm',righttopsrc:'./images/country/country_rightt_9.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_9697.htm',rightbtsrc:'./images/country/country_rightb_9.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_11766.htm'},
		{id:7200105,bigsrc:'./images/country/country_big_10.jpg',bighref:'http://www.dindin.com/goodsDetail_id_4732.htm',righttopsrc:'./images/country/country_rightt_10.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_5029.htm',rightbtsrc:'./images/country/country_rightb_10.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_4753.htm'},
		{id:7500101,bigsrc:'./images/country/country_big_11.jpg',bighref:'http://www.dindin.com/goodsDetail_id_12157.htm',righttopsrc:'./images/country/country_rightt_11.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_12393.htm',rightbtsrc:'./images/country/country_rightb_11.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_11696.htm'},
		//{id:7100302,bigsrc:'./images/country/country_big_12.jpg',bighref:'http://www.dindin.com/goodsDetail_id_9027.htm',righttopsrc:'./images/country/country_rightt_12.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_10528.htm',rightbtsrc:'./images/country/country_rightb_12.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_8672.htm'},
		{id:7100302,bigsrc:'./images/country/country_big_13.jpg',bighref:'http://www.dindin.com/goodsDetail_id_10524.htm',righttopsrc:'./images/country/country_rightt_13.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_10803.htm',rightbtsrc:'./images/country/country_rightb_13.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_8666.htm'},
		{id:7400102,bigsrc:'./images/country/country_big_14.jpg',bighref:'http://www.dindin.com/goodsDetail_id_8797.htm',righttopsrc:'./images/country/country_rightt_14.jpg',righttophref:'http://www.dindin.com/goodsDetail_id_8840.htm',rightbtsrc:'./images/country/country_rightb_14.jpg',rightbthref:'http://www.dindin.com/goodsDetail_id_8791.htm'},
		];
	var ycdId = location.href.substring(location.href.indexOf("ycdId=")+6);
	//设置每个国家页面里的三张广告图的路径及链接
	for(var j = 0;j<countryArr.length;j++){
		//console.log(ycdId+'---'+countryArr[j].id)
		if(ycdId == countryArr[j].id){
			
			$("#bigImage").attr("src",countryArr[j].bigsrc);
			$("#bigImage").attr("data-src",countryArr[j].bigsrc);
			$("#bigImage").css("background-image",'url('+countryArr[j].bigsrc+')');
			$("#imageRight").attr("src",countryArr[j].righttopsrc);
			$("#imageRight").parent().attr("href",countryArr[j].righttophref);
			$("#imageRightDown").attr("src",countryArr[j].rightbtsrc);
			$("#imageRightDown").parent().attr("href",countryArr[j].rightbthref);
			$("#counryLink").attr("href",countryArr[j].bighref);
		}
	}
	if(ycdId=="7100102"){ //韩国
//		$("#bigImage").attr("src","images/america.jpg");
//		$("#bigImage").attr("data-src","images/america.jpg");
//		$("#bigImage").css("background-image","url(images/america.jpg)");
//		
//		$("#imageRight").attr("src","images/america_right.jpg");
//		$("#imageRight").parent().attr("href","http://www.dindin.com/goodsDetail_id_9036.htm");
//		$("#imageRightDown").attr("src","images/america_right_down.jpg");
//		$("#imageRightDown").parent().attr("href","http://www.dindin.com/goodsDetail_id_8343.htm");
		
		//$("#malai").addClass("active_select").siblings().removeClass("active_select")
		if($(window).width()>1200){
			$('.left_bg img').attr('src','./images/country/han-left.jpg');
			$('.right_bg img').attr('src','./images/country/han-right.jpg');
			$('.left_bg,.right_bg').css('display','block').width($('.container.main').offset().left+15);
			$('.left_bg').css('left','0');
		}
		$('.bg').css({'background':'#4ac4fa'});
		//$('.top_bg').css({'display':'block','top':'183px','height':'18px','background':'#6ab0e3'});
	}else if(ycdId=="7200102"){//德国
		//$("#malai").addClass("active_select").siblings().removeClass("active_select")
		if($(window).width()>1200){
			$('.left_bg img').attr('src','./images/country/deguo_left.jpg');
			$('.right_bg img').attr('src','./images/country/deguo_right.jpg');
			$('.left_bg,.right_bg').css('display','block').width($('.container.main').offset().left+5);
		}
		$('.bg').css({'background-color':'#f938ae'});
	}else if(ycdId=="7100201"){//马来西来
		$("#malai").addClass("active_select").siblings().removeClass("active_select")
		if($(window).width()>1200){
			$('.left_bg img').attr('src','./images/country/malai_left.jpg');
			$('.right_bg img').attr('src','./images/country/malai_right.jpg');
			$('.left_bg,.right_bg').css('display','block').width($('.container.main').offset().left+5);
		}
		$('.bg').css({'background-color':'#ff7257'});
	}else if(ycdId=="7100202"){ //泰国
		//$("#malai").addClass("active_select").siblings().removeClass("active_select")
		if($(window).width()>1200){
			$('.top_bg').show().css('background','url(./images/country/tai_top_bg.png) repeat')
			$('.bg').css('position','relative');
			$('.left_bg img').attr('src','./images/country/tai_left.jpg');
			$('.right_bg img').attr('src','./images/country/tai_right.jpg');
			$('.left_bg,.right_bg').css({'display':'block','z-index':'9999','top':'198px'}).width($('.container.main').offset().left+5);
			$('.left_bg').css('left','0');
		}
		$('.bg').css({'background':'url(./images/country/tai_small_bg.jpg) repeat'});
	}else if(ycdId=="7500101"){ // 澳大利亚
		//$("#malai").addClass("active_select").siblings().removeClass("active_select");
		if($(window).width()>1200){
			$('.left_bg img').attr('src','./images/country/ao-left.jpg');
			$('.right_bg img').attr('src','./images/country/ao-right.jpg');
			$('.left_bg,.right_bg').css('display','block').width($('.container.main').offset().left+5);
		}
		$('.bg').css({'background':'url(./images/country/ao-bg.jpg) repeat'});
	}else if(ycdId =="7100302"){	//香港购物节
		//$("#malai").addClass("active_select").siblings().removeClass("active_select");
		if($(window).width()>1200){
			$('.left_bg img').attr('src','./images/country/xg-left.jpg');
			$('.right_bg img').attr('src','./images/country/xg-right.jpg');
			$('.left_bg,.right_bg').css('display','block').width($('.container.main').offset().left+15);
			$('.left_bg').css('left','0');
		}
		$('.bg').css({'background':'#d5e9fa'});
		$('.top_bg').css({'display':'block','top':'183px','height':'18px','background':'#a6c6eb'});
	}else if(ycdId =="7400102"){  //美国购物节
		//$("#malai").addClass("active_select").siblings().removeClass("active_select");
		if($(window).width()>1200){
			$('.left_bg img').attr('src','./images/country/mg-left.jpg');
			$('.right_bg img').attr('src','./images/country/mg-right.jpg');
			$('.left_bg,.right_bg').css('display','block').width($('.container.main').offset().left+15);
			$('.left_bg').css('left','0');
			//console.log("7400102");
		}
		$('.bg').css({'background':'#fdecef'});
		$('.top_bg').css({'display':'block','top':'183px','height':'18px','background':'#a6c6eb'});
	}else if(ycdId =="71002"){
		$("#bigImage").attr("src","images/america.jpg");
		$("#bigImage").attr("data-src","images/america.jpg");
		$("#bigImage").css("background-image","url(images/america.jpg)");
		
		$("#imageRight").attr("src","images/SoutheastAsia_right.jpg");
		$("#imageRight").parent().attr("href","http://www.dindin.com/goodsDetail_id_7722.htm");
		$("#imageRightDown").attr("src","images/SoutheastAsia_right_down.jpg");
		$("#imageRightDown").parent().attr("href","http://www.dindin.com/goodsDetail_id_7700.htm");
		
		$("#dlyLi").addClass("active_select").siblings().removeClass("active_select")
		$("#counryLink").attr("href",'./index.html');
		
	}else if(ycdId =="72001"){
		$("#bigImage").attr("src","images/europe.jpg");
		$("#bigImage").attr("data-src","images/europe.jpg");
		$("#bigImage").css("background-image","url(images/europe.jpg)");
		
		$("#imageRight").attr("src","images/europe_right.jpg");
		$("#imageRight").parent().attr("href","http://www.dindin.com/goodsDetail_id_8506.htm");
		$("#imageRightDown").attr("src","images/europe_right_down.jpg");
		$("#imageRightDown").parent().attr("href","http://www.dindin.com/goodsDetail_id_4753.htm");
		
		$("#ozLi").addClass("active_select").siblings().removeClass("active_select")
		$("#counryLink").attr("href",'./index.html');
		
	}else{
		$("#allCountry").addClass("active_select").siblings().removeClass("active_select");
	
	}
	

	$.ajax({
		type : "POST",
		async : false,  //同步请求
		//async : true,  //异步请求
		dataType:'json',
		url : "searchAction!search.go?test=true&spc.orderStyle=cunt&spc.ycdId="+ycdId,
		success:function(datas){
			showLi(datas);
			showDiv(datas);
			$(".quickview").colorbox({
				iframe:true,
				width:900,
				maxWidth:"90%",
				height:705,
				maxHeight:"90%",
				className: "quickview",
				onClosed: function() {
				}
			});
//			loadPage(1,5);
			if(datas.totalPage >0){
				loadPage(1,datas.totalPage);
			}
		}
	});
	
})
//搜索地址
function searchUrl(pageNum,ctgId){
	var ycdId = location.href.substring(location.href.indexOf("ycdId=")+6);
	var defUrl = "searchAction!search.go?test=true&spc.orderStyle=cunt&spc.ycdId="+ycdId;
	if(pageNum != null){
		defUrl += "&turnPage=true&lookup.viewPage=" + pageNum;
	}
	if(ctgId != null)
	{
		defUrl += "&spc.ctgId=" + ctgId;
	}
	$.ajax({
		type : "POST",
		async : false,  //同步请求
		//async : true,  //异步请求
		dataType:'json',
		url : defUrl,
		success:function(datas){
			showDiv(datas);
			if(datas.totalPage >0){
				loadPage(datas.pageIndex,datas.totalPage);
			}
		}
	});
}

//分页查询
function searchPageUrl(pageNum,ctgId){
	var ycdId = location.href.substring(location.href.indexOf("ycdId=")+6);
	var defUrl = "searchAction!search.go?test=true&spc.orderStyle=cunt&spc.ycdId="+ycdId;
	if(pageNum != null){
		defUrl += "&turnPage=true&lookup.viewPage=" + pageNum;
	}
	
	var ctgId = $("#tabs-0 .active").attr("id");
	if(ctgId != null)
	{
		defUrl += "&spc.ctgId=" + ctgId;
	}
	$.ajax({
		type : "POST",
		async : false,  //同步请求
		//async : true,  //异步请求
		dataType:'json',
		url : defUrl,
		success:function(datas){
			showDiv(datas);
			if(datas.totalPage >0){
				loadPage(datas.pageIndex,datas.totalPage);
			}
		}
	});
}

//绑定数据--分类
function showLi(datas){
	var lis = "";
	if(datas!=null && datas.Property!=undefined && datas.Property!=null){
		for(var i=0;i<datas.Property.length;i++){
			lis += "<li class=\"\" id="+datas.Property[i].clusterId+"><a href=\"\" data-toggle=\"tab\" aria-expanded=\"false\" onclick=\"searchUrl(null,"+datas.Property[i].clusterId+")\">"+datas.Property[i].clusterName+"</a></li>";
		}
	}
	$("#tabs-0").html(lis);
}

//绑定数据--产品
function showDiv(datas){
	var pros ="";
	pros +="<div class=\"product-grid 0 carousel owl-carousel owl-theme\" style=\"opacity: 1; display: block;\"><div class=\"owl-wrapper-outer\"><div class=\"owl-wrapper1\">";
	for(var j=0;j<datas.list.length;j++){
		pros +="<div class=\"owl-item\"><div class=\"item\"><div class=\"image_wrap\"><div class=\"btn-holder top\">";
		pros +="<a class=\"btn btn-icon wishlist\" onclick=\"wishlist.add('56');\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"\" data-original-title=\"Add to Wish List\"><i class=\"fa fa-heart\"></i></a>";
        pros +="<a class=\"btn btn-icon compare\" onclick=\"compare.add('50');\" data-toggle=\"tooltip\" title=\"\" data-original-title=\"Add to Compare\"><i class=\"icon-resize-small\"></i></a>";
        pros +="<div class=\"centered\"><div class=\"centered_cell\">";
        pros +="<a class=\"btn btn-dark qlook quickview cboxElement\" href=\"goodsView_id_"+datas.list[j].commId+".htm\" title=\"预览\"><i class=\"fa fa-eye\"></i><span>预览</span></a>";
        pros +="<span class=\"style-4-break\"></span>";
        pros +="<a class=\"btn btn-primary cart\" onclick=\"cart.add("+datas.list[j].commId+",1,'../"+datas.list[j].imgL+"','other',false);\" title=\"加入购物车\"><i class=\"icon-basket\"></i><span>加入购物车</span></a>";
        pros +="</div></div></div><div class=\"image\">";
	    pros +="<div class=\"image_hover\"><a href=\"\"><img style=\"width:190px;height:190px!important;\" src=\"../"+datas.list[j].imgL+"\" alt=\""+datas.list[0].title+"\"></a></div>";
        pros +="<a class=\"pro-img\" target=\"_blank\" href=\"goodsDetail_id_"+datas.list[j].commId+".htm\"><img src=\"../"+datas.list[j].imgL+"\" alt=\""+datas.list[j].title+"\"></a>";
        pros +="</div></div><div class=\"details_wrap\"><div class=\"information_wrapper\">";
 		pros +="<div class=\"name nameEllipsis\"><a style=\"font-size: 12px\" target=\"_blank\" href=\"goodsDetail_id_"+datas.list[j].commId+".htm\">"+datas.list[j].title+"</a></div>";
        pros +="<div class=\"price_rating_table \"><div class=\"price\"><span>&yen; "+datas.list[j].price+"</span></div></div></div>";
        pros +="<div class=\"guanzhu \">关注度："+datas.list[j].cuntNum+"</div>";
        pros +="<div class=\"btn-holder bottom\"><a class=\"btn btn-primary\" onclick=\"cart.add("+datas.list[j].commId+",1,'../"+datas.list[j].imgL+"','other',false);\"><span>加入购物车</span></a>";
        pros +="<a class=\"btn btn-icon wishlist\" onclick=\"wishlist.add('50');\" data-toggle=\"tooltip\" title=\"\" data-original-title=\"Add to Wish List\"><i class=\"fa fa-heart\"></i></a>";
        pros +="<a class=\"btn btn-icon compare\" onclick=\"compare.add('50');\" data-toggle=\"tooltip\" title=\"\" data-original-title=\"Add to Compare\"><i class=\"icon-resize-small\"></i></a>";
        pros +="</div></div></div></div>";
	}
	pros+="</div></div></div>";
	$("#tab00").html(pros);
}

function loadPage(index,count){
	if(!createPageBar($("[name='itemList_pagebar']"),'<a  onclick="{event}"><span>{i}</span></a>','<a  class="on"><span>{i}</span></a>','searchPageUrl({i},null)',index,count)){//无须页码条
		alert("page error!");
		$("[name='pagebarWrap']").hide();
	}else{//须页码条
		$("[name='pagebarWrap']").show();
	}
}


/*公共JS*/
/*
 *生成页码条
 *@param	targetId	string	页码条标签id
 *@param	normalTpl	string	普通页码模板
 *@param	onTpl		string	当前页码模板
 *@param	evntTpl		string	事件模板
 *@param	cp			int		current page当前页
 *@param	tp			int		total page总页数
 *@return	true-成功生成页码条,false-生成失败,参数有误
 */
function createPageBar(targetId,normalTpl,onTpl,evntTpl,currentPage,totalPage)
{
	cp			= parseInt(currentPage,10);
	tp			= parseInt(totalPage,10);
	if(!targetId || !evntTpl || !onTpl || !normalTpl || cp<1 || cp>tp){return false;}//参数错误
    e          = targetId;		 
    onTpl      = onTpl.replace('{event}' , evntTpl);     
    normalTpl  = normalTpl.replace('{event}' , evntTpl); 
    offset     = 2;	
    step	   = offset+2;
    

	//if(tp < 2){e.innerHTML='';return false;}//总页数小于2
	var lLack = 0, rLack = 0;//左右偏移的不足
	if( cp-2 < step ){ lLack = step - (cp-1); }
	if( tp-cp-1 < step ){ rLack = step - (tp-cp); }
	var le = cp-offset-rLack;
	var re = cp+offset+lLack;


	var str=[];
	if(cp > 1)//可点上一页
	{
		var s = '<a  onclick="{event}"><span class="before_page"><</span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp-1);
		str.push(s);
	}else{
		var s = '<a href="javascript:void(0);" style="" class="disabled"><span class="before_page"><</span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp-1);
		str.push(s);
	}

	if(le > 1)
	{
		str.push( normalTpl.replace(/{i}/g , 1) );	
	}
	if(le == 3)
	{
		str.push( normalTpl.replace(/{i}/g , 2) );	
	}
	if(le > 3)
	{
		str.push("<a href='#' class='on'><span>.</span></a><a href='#' class='on'><span>.</span></a>");
	}
	for(var j=cp,i=le; i<j; i++)
	{
		if(i<1){continue;}
		str.push( normalTpl.replace(/{i}/g , i) );
	}

	str.push( onTpl.replace(/{i}/g , cp) );
	
	for(var i=cp+1, j=re+1; i<j; i++)
	{
		if(i>tp){break;}
		str.push( normalTpl.replace(/{i}/g , i) );
	}
	if( re == tp-2 )
	{
		str.push( normalTpl.replace(/{i}/g , tp-1) );	
	}
	if( re < tp-2 )
	{
		str.push("<a href='#' class='on'><span>.</span></a><a href='#' class='on'><span>.</span></a>");
	}
	if(re < tp)
	{
		str.push( normalTpl.replace(/{i}/g , tp) );	
	
	}
	if(cp < tp)
	{
		var s = '<a  onclick="{event}"><span class="next_page">></span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp+1);
		str.push(s);
	}else{
		var s = '<a href="javascript:void(0);" style="color : darkgray"><span class="next_page">></span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp+1);
		str.push(s);
		
	}
	for(var i = 0; i< e.length; i++){
		e[i].innerHTML = str.join('');
	}
	return true;
}