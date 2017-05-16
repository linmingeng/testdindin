$(function(){
	$.ajax({
		type : "POST",
		async : false,  //ͬ������
		//async : true,  //�첽����
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
	
	//��ʼ�������̳����а�
	initIntegralConvertGoodsTop();
})
//������ַ
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
		async : false,  //ͬ������
		//async : true,  //�첽����
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

//��ҳ��ѯ
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
		async : false,  //ͬ������
		//async : true,  //�첽����
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

//��ʼ�������̳����а�
function initIntegralConvertGoodsTop(){
	$.ajax({
		type : "POST",
		async : false,  //ͬ������
		//async : true,  //�첽����
		dataType:'json',
		url : "integralConvertGoods!getIntegralConvertGoodsTop.go",
		success:function(datas){
			if(datas.totalNum >0){
				showGoodsTopDiv(datas);
			}
		}
	});
}

//������--�����̳����а�
function showGoodsTopDiv(datas){
	var html ="";
	for(var j=0;j<datas.list.length;j++){
		html +="<div>";
		html +="<a href=\"goodsViewByJf_id_"+datas.list[j].id+".htm\" class=\"lead-con clearfix\">";
		html +="<img src=\""+datas.list[j].logPath+"\" alt=\""+datas.list[j].name+"\" width=\"88\" style=\"height: 88px!important;\">";
		html +="<i>"+(j+1)+"</i>";
		html +="<p class=\"nameEllipsis\">"+datas.list[j].name+"</p>";
		html +=" <p><span>��0 </span> + <span>"+datas.list[j].jf+"</span> ����</p>";
		html +="</a>";
		html +="</div>";
	}
	$("#owe-main").html(html);
}

//������--������Ʒ
function showDiv(datas){
	var pros ="";
	pros +="<ul class=\"clearfix\">";
	for(var j=0;j<datas.list.length;j++){
		pros +="<li><div class=\"product-img\">";
		pros +="<a href=\"goodsViewByJf_id_"+datas.list[j].id+".htm\"><img src=\""+datas.list[j].logPath+"\" alt=\"\"></a>";
        pros +="</div>";
        pros +="<div class=\"product-text\">";
        pros +="<a href=\"\" title=\"\" class=\"nameEllipsis\">"+datas.list[j].name+"</a>";
        pros +=" <del>�ο��۸�"+datas.list[j].tgjg+"</del>";
        pros +=" <p><span>��0 </span> + <span>"+datas.list[j].jf+"</span> ����</p>";
        pros +="</div>";
	    pros +="<div class=\"product-buy\">";
        pros +="<a href=\"goodsViewByJf_id_"+datas.list[j].id+".htm\" class=\"gift\">�����һ�</a>";
        pros +="</div>";
 		pros +="</li>";
	}
	pros+="</ul>";
	$("#content").html(pros);
}

function loadPage(index,count){
	if(!createPageBar($("[name='itemList_pagebar']"),'<a  onclick="{event}"><span>{i}</span></a>','<a  class="on"><span>{i}</span></a>','searchPageUrl({i},null)',index,count)){//����ҳ����
		alert("page error!");
		$("[name='pagebarWrap']").hide();
	}else{//��ҳ����
		$("[name='pagebarWrap']").show();
	}
}


/*����JS*/
/*
 *����ҳ����
 *@param	targetId	string	ҳ������ǩid
 *@param	normalTpl	string	��ͨҳ��ģ��
 *@param	onTpl		string	��ǰҳ��ģ��
 *@param	evntTpl		string	�¼�ģ��
 *@param	cp			int		current page��ǰҳ
 *@param	tp			int		total page��ҳ��
 *@return	true-�ɹ�����ҳ����,false-����ʧ��,��������
 */
function createPageBar(targetId,normalTpl,onTpl,evntTpl,currentPage,totalPage)
{
	cp			= parseInt(currentPage,10);
	tp			= parseInt(totalPage,10);
	if(!targetId || !evntTpl || !onTpl || !normalTpl || cp<1 || cp>tp){return false;}//��������
    e          = targetId;		 
    onTpl      = onTpl.replace('{event}' , evntTpl);     
    normalTpl  = normalTpl.replace('{event}' , evntTpl); 
    offset     = 2;	
    step	   = offset+2;
    

	//if(tp < 2){e.innerHTML='';return false;}//��ҳ��С��2
	var lLack = 0, rLack = 0;//����ƫ�ƵĲ���
	if( cp-2 < step ){ lLack = step - (cp-1); }
	if( tp-cp-1 < step ){ rLack = step - (tp-cp); }
	var le = cp-offset-rLack;
	var re = cp+offset+lLack;


	var str=[];
	if(cp > 1)//�ɵ���һҳ
	{
		var s = '<a  onclick="{event}"><span class="before_page">��һҳ</span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp-1);
		str.push(s);
	}else{
		var s = '<a href="javascript:void(0);" style="color : darkgray"><span class="before_page">��һҳ</span></a>'.replace('{event}' , evntTpl);
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
		var s = '<a  onclick="{event}"><span class="next_page">��һҳ</span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp+1);
		str.push(s);
	}else{
		var s = '<a href="javascript:void(0);" style="color : darkgray"><span class="next_page">��һҳ</span></a>'.replace('{event}' , evntTpl);
			s = s.replace('{i}',cp+1);
		str.push(s);
		
	}
	for(var i = 0; i< e.length; i++){
		e[i].innerHTML = str.join('');
	}
	return true;
}