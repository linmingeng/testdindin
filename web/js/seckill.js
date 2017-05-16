var haomiao= 1000;
/**
 * 
 * @param {Object} xsId 限时抢购时间Id
 * @param {Object} type 
 */


function xsqgGoodsListFun(xsId,type,o){
	
	if(type=='ing'){
		
		return;
	}
	
	//修改样式

	changeStyle(o);
	
	$.ajax({
		async : true,
		type : 'POST',
		dataType : "json",
		url : 'seckillAction!xsqgGoodsListByXsqgId.go',
		data:{xsqgId:xsId},
		beforeSend: function(){
			var str = '<div style="position:absolute;left:50%;top:50%;margin:-17px 0 0 -17px!important;"><img src="./images/loader6.gif" ></div>';
		 	$("#seckillPlan").html(str);
		},
		success : function(data) {
			if(data!=null){
					//服务器时间
					var serverTime =data.serverTime;
					//开始时间
					var xgBeginTime =data.xgBeginTime;
					//结束时间
					var xgEndTime =data.xgEndTime;
					
					var html="";
					if(data.xgGoodsList.length>0){
						
						
						for(var i=0;i<data.xgGoodsList.length;i++){
							//存放替换参数的数组
							var arr = new Array();	
							var starFlag="1";
							var item = data.xgGoodsList[i];
							//商品Id
							arr.push(item.ids);
							//商品图片
							arr.push(item.picLink);
							//商品名称
							arr.push(item.itemName);

							if(item.ycdNationalFlag!=''){
								arr.push("<img src='./"+item.ycdNationalFlag+"' width='30' style='height: 20px!important;vertical-align: bottom;' />");
								arr.push(item.ycdName+"品牌");
							}else{
								arr.push('');
								arr.push('');
							}
							/*if('7400102'==item.ycdId){//美国
								
								//国家国旗 ./images/countryImg/deguo.jpg
								arr.push("<img src='./images/countryImg/meiguo.jpg' width='30' style='height: 20px!important;vertical-align: bottom;'>");
								//国家
								arr.push('美国品牌');
							}else if("7200102"==item.ycdId){//德国
								
								//国家国旗 ./images/countryImg/deguo.jpg
								arr.push("<img src='./images/countryImg/deguo.jpg' width='30' style='height: 20px!important;vertical-align: bottom;'>");
								//国家
								arr.push('德国品牌');
							}else if("7100102"==item.ycdId){//韩国
								
								//国家国旗 ./images/countryImg/deguo.jpg
								arr.push("<img src='./images/countryImg/hanguo.jpg' width='30' style='height: 20px!important;vertical-align: bottom;'>");
								//国家
								arr.push('韩国品牌');
							}else{
								
								//国家国旗 ./images/countryImg/deguo.jpg
								arr.push('');
								//国家
								arr.push('');
							}*/
							
							//商品Id
							arr.push(item.ids);
							//商品名称
							arr.push(item.itemName);
							
							
							if(serverTime>=xgBeginTime&&serverTime<=xgEndTime){//正在抢购
								
								//当前价格
								arr.push(item.itemPrice);
								//划掉的价格
								arr.push(item.marketPrice);
								
								//商品Id
								arr.push(item.ids);
								
								//折扣
								arr.push(item.discount);
								arr.push("立即购买");
							}else{//还未开始抢购
								//当前价格
								arr.push(item.marketPrice);
								//划掉的价格
								arr.push(item.itemPrice);								
						
								//商品Id
								arr.push(item.ids);
								
								//折扣
								arr.push(item.discount);
								if(serverTime>xgEndTime){
									arr.push("已经结束");
									starFlag="2";
								}else{
									arr.push("即将开始");
									starFlag="1";
								}
							}
							//商品简介
							arr.push(item.spjj);
							//商品剩余数量
							arr.push(item.qgTotal);
							if(starFlag=="1"){
								arr.push("限量");
							}else{
								arr.push("剩余数量");
							}
							html +=strFormat(arr);
						}
						
					}else{
						html = "<div style='text-align:center;padding:20px;font-size:17px;color:#959494;'>暂无抢购内容。</div>";
					}
				$(".owl-wrapper1").eq(1).html(html);
				
				var times3 ;
				 
				if(serverTime<xgBeginTime){//尚未开始
					
					times3= xgBeginTime-serverTime;
					$("#xsLabel").html("距本场开始仅剩");
					//限量
				}else if(serverTime>=xgBeginTime&&serverTime<=xgEndTime){
					
					times3= xgEndTime-serverTime;
					$("#xsLabel").html("距本场结束仅剩");
				}else{
					$("#xsLabel").html("本场已经结束");
					//剩余数量
				}				
				
				//定时器
				if(timer!=null){
					
					window.clearInterval(timer);
				}
				timer =setInterval(function(){
							times3 = times3 -1*haomiao; //秒数减1
							spKillTimeFun(times3,1);
					},haomiao);
				
				nameSubStr($('.namesubstr'),45);
			}
		}});
}

function changeStyle(o){
	
	$(o).addClass("soon").siblings().removeClass("soon grey").addClass("grey");
}

/**
 * 
 * @param {Object} arr 参数数组
 */
function strFormat(arr){
	var template ='<div class=\"owl-item\"><div class=\"product-info\"><div class=\"left\"><div class=\"image\" style=\"max-width:360px;\"><a href=\"goodsDetail_id_{0}.htm\" target=\"_blank\" ><img src=\"{1}\" title=\"{2}\"></a>';
		template+='</div></div>';
		template+='<div class=\"seckill-time\">';
  	//	template+="<span class='times'><span>0</span> <span>2</span> : <span>4</span> <span>8</span> :  <span>1</span> <span>4</span></span>";
  		template+='</div>';
		template+='<div class=\"right\"><div style=\"color: #757575;font-size:16px;\">{3} {4}</div>';
		template+='<h2 style=\"max-width:328px;\" class=\"nameEllipsis\"><a href=\"goodsDetail_id_{5}.htm\" target=\"_blank\" title=\"{6}\">{6}</a></h2><div class=\"product-desc namesubstr\">{12}</div>';
		template+='';
		template+='<div class=\"prices\" style=\"margin-top:15px;\"><span><span class=\"nowprice\">￥{7}</span> <del>￥{8}</del></span>';
		template+='</div>';
		template+='<div style=\"margin: 5px 0 10px 0;;color: #666;font-weight:bold;\">{14}：<span>{13}</span><div class=\"btn-holder\" style=\"float:right;margin-top:0;\"><a style=\"display:block;background: #d9f2ec;color:#28cab3;\" type=\"button\" class=\"btn btn-primary gotostore\" href=\"goodsDetail_id_{9}.htm\" target=\"_blank\">{11}</a></div></div>';
		template+='</div> </div><div class=\"discount\">{10}</div></div>';

	return template.format(arr[0],arr[1],arr[2],arr[3],arr[4],arr[5],arr[6],arr[7],arr[8],arr[9],arr[10],arr[11],arr[12],arr[13],arr[14]);	
}

/**
 * 模板参数转换
 * @returns 
 * 			替换后完整的字段
 * @author ljy
 */
String.prototype.format=function()
{
  if(arguments.length==0) return this;
  for(var s=this, i=0; i<arguments.length; i++)
    s=s.replace(new RegExp("\\{"+i+"\\}","g"), arguments[i]);
  return s;
};

var spKillTimeFun = function(times,spanIndex){
		
		$(".times").eq(spanIndex).html('<span>0</span> <span>0</span> : <span>0</span> <span>0</span> :  <span>0</span> <span>0</span>');
		if(times>0){
			var day=Math.floor(times/(60*60*24*1000));
			var hour=Math.floor((times-day*24*60*60*1000)/3600000); 
			var minute=Math.floor((times-day*24*60*60*1000-hour*3600*1000)/60000); 
			var second=Math.floor((times-day*24*60*60*1000-hour*3600*1000-minute*60*1000)/1000);
			//毫秒
			var misSeccond=Math.floor((times-day*24*60*60*1000-hour*3600*1000-minute*60*1000-second*1000)/100);
	
			$(".times").eq(spanIndex).html(formatSpan(hour)+" : "+formatSpan(minute)+" :  "+formatSpan(second)/*+" :   "+formatSpan(misSeccond)*/);
		
		}else{
			
			if(!isNaN(times)){
				setTimeout(function(){
					window.location.reload();
				},2000); 
				
			}
			
		}
}

function formatSpan(time){
	var timeStr = "";
	if(time>9){
		timeStr = "<span>"+time.toString().substring(0,1)+"</span> <span>"+time.toString().substring(1,2)+"</span>";
	}else{
		timeStr = "<span>0</span> <span>"+time+"</span>";
	}
	return timeStr;
}