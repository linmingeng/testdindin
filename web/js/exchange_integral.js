$(function(){
	$.ajax({
		type : "POST",
		async : false,  //同步请求
		//async : true,  //异步请求
		dataType:'json',
		url : "integralConvertGoods!getIntegralConvertGoods.go",
		success:function(datas){
//			showLi(datas);
			showDiv(datas);
//			loadPage(1,5);
/*			if(datas.totalPage >0){
				loadPage(1,datas.totalPage);
			}*/
		}
	});
	
	//初始化积分商城排行榜
	initIntegralConvertGoodsTop();
})
//搜索地址
function searchUrl(pageNum,ctgId){
	var ycdId = location.href.substring(location.href.indexOf("ycdId=")+6);
	var defUrl = "searchAction!search.go?test=true&spc.ycdId="+ycdId;
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
	var defUrl = "searchAction!search.go?test=true&spc.ycdId="+ycdId;
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
			if(datas.totalNum >0){
				loadPage(datas.pageIndex,datas.totalPage);
			}
		}
	});
}

//初始化积分商城排行榜
function initIntegralConvertGoodsTop(){
	$.ajax({
		type : "POST",
		async : false,  //同步请求
		//async : true,  //异步请求
		dataType:'json',
		url : "integralConvertGoods!getIntegralConvertGoodsTop.go",
		success:function(datas){
			if(datas.totalNum >0){
				showGoodsTopDiv(datas);
			}
		}
	});
}

//绑定数据--积分商城排行榜
function showGoodsTopDiv(datas){
	var html ="";
	for(var j=0;j<datas.list.length;j++){
		html +="<div>";
		html +="<a href=\"goodsViewByJf_id_"+datas.list[j].id+".htm\" class=\"lead-con clearfix\">";
		html +="<img src=\""+datas.list[j].logPath+"\" alt=\""+datas.list[j].name+"\" width=\"88\" style=\"height: 88px!important;\">";
		html +="<i>"+(j+1)+"</i>";
		html +="<p class=\"nameEllipsis\">"+datas.list[j].name+"</p>";
		html +=" <p><span>￥0 </span> + <span>"+datas.list[j].jf+"</span> 积分</p>";
		html +="</a>";
		html +="</div>";
	}
	$("#owe-main").html(html);
}

//绑定数据--积分商品
function showDiv(datas){
	var pros ="";
	pros +="<ul class=\"clearfix\">";
	for(var j=0;j<datas.list.length;j++){
		pros +="<li><div class=\"product-img\">";
		pros +="<a href=\"goodsViewByJf_id_"+datas.list[j].id+".htm\"><img src=\""+datas.list[j].logPath+"\" alt=\"\"></a>";
        pros +="</div>";
        pros +="<div class=\"product-text\">";
        pros +="<a href=\"\" title=\"\" class=\"nameEllipsis\">"+datas.list[j].name+"</a>";
        pros +=" <del>参考价格￥"+datas.list[j].tgjg+"</del>";
        pros +=" <p><span>￥0 </span> + <span>"+datas.list[j].jf+"</span> 积分</p>";
        pros +="</div>";
	    pros +="<div class=\"product-buy\">";
        pros +="<a href=\"goodsViewByJf_id_"+datas.list[j].id+".htm\" class=\"gift\">立即兑换</a>";
        pros +="</div>";
 		pros +="</li>";
	}
	pros+="</ul>";
	$("#content").html(pros);
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
		var s = '<a  onclick="{event}"><span class="before_page">上一页</span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp-1);
		str.push(s);
	}else{
		var s = '<a href="javascript:void(0);" style="color : darkgray"><span class="before_page">上一页</span></a>'.replace('{event}' , evntTpl);
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
		var s = '<a  onclick="{event}"><span class="next_page">下一页</span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp+1);
		str.push(s);
	}else{
		var s = '<a href="javascript:void(0);" style="color : darkgray"><span class="next_page">下一页</span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp+1);
		str.push(s);
		
	}
	for(var i = 0; i< e.length; i++){
		e[i].innerHTML = str.join('');
	}
	return true;
}