function initLoadData(objId)
{
	var url = "searchAction!search.go?test=true";
	//��������
	var type = 1;
	var ctgId = 0;
	var ycdId =0;
	var ctgName = "";
	//��ȡ����
	var orderType = $(".sort_list .acvt").attr("id");
	if(orderType != null && orderType!=undefined)
	{
		url += "&spc.orderStyle=" + orderType.substring(3);
	}
	if(location.href.indexOf("q_show=") > -1){
		var content = location.href.substring(location.href.indexOf("q_show=")+7,location.href.indexOf("&q="));
		content = decodeURI(decodeURI(content));
		url+="&spc.content="+content;
	}else{
		//�������
		type = 2;
		ctgId = location.href.substring(location.href.indexOf("classId=")+8,location.href.indexOf("&subClassId="));
		ctgId = decodeURI(decodeURI(ctgId));
		url+="&spc.ctgId="+ctgId;
		ctgName = location.href.substring(location.href.indexOf("subClassId=")+11,location.href.indexOf("&PTAG="))
	}
	if(objId!=null && objId!=undefined){
		var spans = $(".point_center").find("span");
		if(spans !=null && spans!=undefined && spans.length>0){			
			for(var i=0;i<spans.length;i++){
				var obj = $(spans[i]);
				if(obj.find("a")!=null && obj.find("a")!=undefined && obj.find("a").length>0){
					var id = obj.find("a").attr("id");
					if(id.indexOf("ctgId")>-1 && objId!=id){
						ctgId = id.substring(id.indexOf("ctgId")+5,id.length);
					}else if(id.indexOf("ycd")>-1 && objId!=id){
						ycdId = id.substring(id.indexOf("ycd")+3,id.length);
					}
				}
			}
			if(ctgId!=null && ctgId!=undefined && ctgId!=0)
			{
				url += "&spc.ctgId=" + ctgId;
			}
			if(ycdId!=null && ycdId!=undefined && ycdId!=0){
				url += "&spc.ycdId="+ycdId;
			}
		}
	}
	$.ajax({
		type : "POST",
		async : false,  //ͬ������
		//async : true,  //�첽����
		dataType:'json',
//		url : "searchAction!search.go?test=true&spc.ycdId="+ycdId,
		url :url,
		success:function(datas){
		
			if(datas.keyWord!=undefined){
				$("#txtKeyWord").text(datas.keyWord);
			}   
			
			if(datas.Property!=undefined){
				showLi(datas);
			}else{
				showCtgLi(ctgId,decodeURI(decodeURI(ctgName)),datas.totalNum);
			}
			showDiv(datas);

			if(datas.totalPage >0){
				loadPage(datas.pageIndex,datas.totalPage);
			}
		}
	});
}

function loadPage(index,count){
	if(!createPageBar(index,count)){//����ҳ����
		alert("page error!");
		$("#search_page").css("display","none");
	}
}

//��������
function searchOrderUrl(type,obj){
	var defUrl = "searchAction!search.go?test=true";
	//��������
	var ctgId = 0;
	var ycdId=0;
	var ctgName = "";
	if(location.href.indexOf("q_show=") > -1){
		var content = location.href.substring(location.href.indexOf("q_show=")+7,location.href.indexOf("&q="));
		content = decodeURI(decodeURI(content));
		defUrl+="&spc.content="+content;
		
		var spans = $(".point_center").find("span");
		if(spans !=null && spans!=undefined){			
			for(var i=0;i<spans.length;i++){
				var obj = $(spans[i]);
				if(obj.find("a")!=null && obj.find("a")!=undefined && obj.find("a").length>0){
					var id = obj.find("a").attr("id");
					if(id.indexOf("ctgId")>-1){
						ctgId = id.substring(id.indexOf("ctgId")+5,id.length);
					}else if(id.indexOf("ycd")>-1){
						ycdId = id.substring(id.indexOf("ycd")+3,id.length);
					}
				}
			}
			
		}else{
			ctgId = null;
			ycdId = null;
		}
		if(ctgId!=null && ctgId!=undefined && ctgId!=0)
		{
			defUrl += "&spc.ctgId=" + ctgId;
		}
		if(ycdId!=null && ycdId!=undefined && ycdId!=0){
			defUrl += "&spc.ycdId="+ycdId;
		}
	}else{
		//�������
		ctgId = location.href.substring(location.href.indexOf("classId=")+8,location.href.indexOf("&subClassId="));
		ctgId = decodeURI(decodeURI(ctgId));
		defUrl+="&spc.ctgId="+ctgId;
		ctgName = location.href.substring(location.href.indexOf("subClassId=")+11,location.href.indexOf("&PTAG="))
	}
	
	if(type != null && type!=undefined)
	{
		defUrl += "&spc.orderStyle=" + type;
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
//��ҳ����
window.onload=function(){
    searchPageUrl(1);
}
function searchPageUrl(pageNum){
	var defUrl = "?goods/list";
	// //��������
	// var ctgId = 0;
	// var ycdId = 0;
	// var ctgName = "";
	var sub_groupid = $('#search_sub_groupid').val();
	$.ajax({
		type : "POST",
		async : false,  //ͬ������
		//async : true,  //�첽����
		dataType:'json',
		data:{sub_groupid:sub_groupid,page:pageNum},
		url : defUrl,
		success:function(datas){
			if(datas.code == 200){
				var html = '';
				for(key in datas.goods_list.results){
					html += '<li style="margin-right: 2%;"><dl>';
					html += '<dt><a href="?goodsDetail/view/goodsid/'+datas.goods_list.results[key].goodsid+'"><img src="'+datas.goods_list.results[key].image+'" title="'+datas.goods_list.results[key].name+'"></a></dt>';
                    html += '<dd><p>';

					html += '<a class="a_overflow namesubstr" href="http://www.dindin.com/goodsDetail_id_19348.htm" title="'+datas.goods_list.results[key].name+'">'+datas.goods_list.results[key].name+'</a>';
					html += '</p><div>';
					html += '<span class="srarch_price"><em>￥</em>'+datas.goods_list.results[key].price+'</span> ';
					html += '</div></dd></dl></li>';
				}
				$('#search_list').html(html);
			}
			// showDiv(datas);
			if(datas.goods_list.totalPage >0){
				loadPage(pageNum,datas.goods_list.totalPage);
			}
		}
	});
}
//��������
function searchCtgUrl(pageNum,ctgId){
	var ctgIdStr = "ctgId"+ctgId;
	var obj = $("#"+ctgIdStr).val();
	if(obj!=undefined){
		initLoadData(ctgIdStr);
	}else{
		var defUrl = "searchAction!search.go?test=true";
		//��������
		var ctgId = ctgId;
		var ycdId = 0;
		var ctgName = "";
		if(location.href.indexOf("q_show=") > -1){
			var content = location.href.substring(location.href.indexOf("q_show=")+7,location.href.indexOf("&q="));
			content = decodeURI(decodeURI(content));
			defUrl+="&spc.content="+content;
			
			if(ctgId != null)
			{
				defUrl += "&spc.ctgId=" + ctgId;				
			}
			
		var spans = $(".point_center").find("span");
		if(spans !=null && spans!=undefined){			
			for(var i=0;i<spans.length;i++){
				var obj = $(spans[i]);
				if(obj.find("a")!=null && obj.find("a")!=undefined && obj.find("a").length>0){
					var id = obj.find("a").attr("id");
					if(id.indexOf("ycd")>-1){
						ycdId = id.substring(id.indexOf("ycd")+3,id.length);
					}
				}
			}			
		}else{
			ycdId = null;
		}
		if(ycdId!=null && ycdId!=undefined && ycdId!=0){
			defUrl += "&spc.ycdId="+ycdId;
		}
			
		}else{
			//�������
			ctgId = location.href.substring(location.href.indexOf("classId=")+8,location.href.indexOf("&subClassId="));
			ctgId = decodeURI(decodeURI(ctgId));
			defUrl+="&spc.ctgId="+ctgId;
			ctgName = location.href.substring(location.href.indexOf("subClassId=")+11,location.href.indexOf("&PTAG="))
		}
		
		//��ȡ����
		var orderType = $(".sort_list .acvt").attr("id");
		if(orderType != null && orderType!=undefined)
		{
			defUrl += "&spc.orderStyle=" + orderType.substring(3);
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
}
//ԭ��������
function searchCountryCtgUrl(ycdId)
{
	var ycdIdStr = "sycd"+ycdId;
	var obj = $("#"+ycdIdStr).val();
	if(obj!=undefined){
		initLoadData(ycdIdStr);
	}else{
		var defUrl = "searchAction!search.go?test=true";
		//��������
		var ctgId = 0;
		var ctgName = "";
		if(location.href.indexOf("q_show=") > -1){
			var content = location.href.substring(location.href.indexOf("q_show=")+7,location.href.indexOf("&q="));
			content = decodeURI(decodeURI(content));
			defUrl+="&spc.content="+content;
			
			if(ycdId != null)
			{
				defUrl += "&spc.ycdId=" + ycdId;
			}
			
			var spans = $(".point_center").find("span");
			if(spans !=null && spans!=undefined){			
				for(var i=0;i<spans.length;i++){
					var obj = $(spans[i]);
					if(obj.find("a")!=null && obj.find("a")!=undefined && obj.find("a").length>0){
						var id = obj.find("a").attr("id");
						if(id.indexOf("ctgId")>-1){
							ctgId = id.substring(id.indexOf("ctgId")+5,id.length);
						}
					}
				}
			
			}else{
				ctgId = null;
			}
			if(ctgId!=null && ctgId!=undefined && ctgId!=0)
			{
				defUrl += "&spc.ctgId=" + ctgId;
			}
			
		}else{
			//�������
			ctgId = location.href.substring(location.href.indexOf("classId=")+8,location.href.indexOf("&subClassId="));
			ctgId = decodeURI(decodeURI(ctgId));
			defUrl+="&spc.ctgId="+ctgId;
			ctgName = location.href.substring(location.href.indexOf("subClassId=")+11,location.href.indexOf("&PTAG="))
		}
		
		//��ȡ����
		var orderType = $(".sort_list .acvt").attr("id");
		if(orderType != null && orderType!=undefined)
		{
			defUrl += "&spc.orderStyle=" + orderType.substring(3);
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
		
}

//������--����
function showLi(datas){
	var lis = "";
	for(var i=0;i<datas.Property.length;i++){
		lis += "<a href=\"\" id=\""+datas.Property[i].clusterId+"\" onclick=\"searchCtgUrl(null,"+datas.Property[i].clusterId+");return false;\">"+datas.Property[i].clusterName+"("+datas.Property[i].clusterCount+")</a>";
	}
	$("#menus").html(lis);
}
function showCtgLi(ctgId,ctgName,sunNum){
	var lis = "";
	lis += "<a href=\"\" id=\""+ctgId+"\" onclick=\"searchCtgUrl(null,"+ctgId+");return false;\">"+ctgName+"("+sunNum+")</a>";
	$("#menus").html(lis);
}

//������--��Ʒ
function showDiv(datas){
	var pros ="";
	for(var j=0;j<datas.list.length;j++){
		$("#tempTitle").html(datas.list[j].title);
		var title = $("#tempTitle").text();
        pros +=  "<li>";
        pros +=  "<dl>";
        pros +=  "<dt><a href=\"goodsDetail_id_"+datas.list[j].commId+".htm\"><img src=\"./"+datas.list[j].imgL+"\" title=\""+title+"\"/></a></dt>";
        pros +=  "<dd>";
        pros +=  "<p><a class=\"a_overflow namesubstr\" href=\"goodsDetail_id_"+datas.list[j].commId+".htm\" title=\""+title+"\">"+datas.list[j].title+"</a></p>";
        pros +=  "<div>";
		pros +=  " <span class=\"srarch_price\"><em>��</em>"+datas.list[j].price+"</span> ";
		//pros +=  "<span class=\"search_discount\">5.3��</span>";
		if(datas.list[j].ycdNationalFlag!=undefined && datas.list[j].ycdNationalFlag!=""){
			pros +=  "<span class=\"search_adder\"><img width=\"35\" src=\"./"+datas.list[j].ycdNationalFlag+"\" title=\""+datas.list[j].ycdName+"\" /></span>";
		}		
		pros +=  "</div>";
		pros +=  "</dd>";
		pros +=  "</dl>";
		pros +=  "</li>";		
	}	
	$("#search_list").html(pros);
	loadColorBox();
	titleText();
	sort(); 
	 if($(window).width()<330){
    	nameSubStr($('.namesubstr'),10);
    }else if($(window).width()<500){
    	nameSubStr($('.namesubstr'),15);
    }else{
    	nameSubStr($('.namesubstr'),25);
    }
}

function updateTitle(maxwidth)
{
	var len = maxwidth;
	$("#search_list .a_overflow").each(function(){
		var maxwidth=len;
		if($(this).text().length>maxwidth){
			$(this).text($(this).text().substring(0,maxwidth)+'��');
			//$(this).html($(this).html()+'��');
		}
	});
}
function titleText(){
	var w=$(window).width();
	if(w<480){
		updateTitle(10);			
	}else if(w<1000){
		updateTitle(15);
	}else{
		updateTitle(20);		
	}
}

function loadColorBox(){
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
}

/*����JS*/
/*
 *����ҳ����
 *@param	cp			int		current page��ǰҳ
 *@param	tp			int		total page��ҳ��
 *@return	true-�ɹ�����ҳ����,false-����ʧ��,��������
 */
function createPageBar(currentPage,totalPage)
{
	cp			= parseInt(currentPage,10);
	tp			= parseInt(totalPage,10);
	if(cp<1 || cp>tp){return false;}//��������
	
    var str="";
	if(cp > 1)//�ɵ���һҳ
	{
		var beforPage = cp - 1;
		str += "<a href='javascript:void(0);' onclick='searchPageUrl("+beforPage+")'>&lt;</a>";
	}else{
		str += "<a href='javascript:void(0);'>&lt;</a>";
	}
	str += "<p><span>"+cp+"</span>/<em>"+tp+"</em></p>";
	//�����һҳ
	if(cp < tp)
	{
		var nextPage = cp +1;
		str += "<a href='javascript:void(0);' onclick='searchPageUrl("+nextPage+")'>&gt;</a>";
	}else{
		str += " <a href='javascript:void(0);'>&gt;</a>";		
	}
	$("#search_page").html(str);
	return true;
}

 /***********�жϿ�� ʹ�����з�ʽ************/
    function sort(){   
      var w=$(window).width();   
      if(w<=740){
        $('.search_list li').css({'marginRight':'2%'});
        rigmarg2();
      }else if(w<=920){
        $('.search_list li').css({'marginRight':'2%'});
        rigmarg3();
      }else{        
        $('.search_list li').css({'marginRight':'2%'});
        rigmarg4();
      }
    }    

    /***********����************/
    function rigmarg4(){
      $('.search_list li').each(function(index){
        if((index+1)%4==0){   
          $(this).css({'marginRight':'0'});      
        }
      });
    }

    /***********����************/
    function rigmarg3(){
      $('.search_list li').each(function(index){
        if((index+1)%3==0){   
          $(this).css({'marginRight':'0'});      
        }
      });
    }

    /***********����************/
    function rigmarg2(){
       $('.search_list li').each(function(index){
        if((index+1)%2==0){   
          $(this).css({'marginRight':'0'});      
        }
      });
    }

$(function(){   
	initLoadData(null);
    sort(); 
    
    $(window).resize(function(){
       sort();
       titleText();
    })

     //���� Ϊ�������id
      obj_id($('.duoxuan_box'));

    /***********�۸�����************/
    $('.sort_list a').on('click',function(){
      $(this).addClass('acvt');
      $(this).siblings().removeClass('acvt'); 
      /*if($(this).is('.top')){
        if($(this).is('.acvt') & $(this).is('.top')){        
          $(this).addClass("bottom");        
          $(this).removeClass("top");         
        }
      }else if($(this).is('.acvt') & $(this).is('.bottom')){   
        $(this).addClass("top");        
        $(this).removeClass("bottom");  
      }*/     
    })


    /***********��ѡ/��ѡ************/
    $('#duoxuan_btn').on('click',function(){ 
      var text=$(this).text();
      if(text=='��ѡ +'){
        $(this).text('ȡ�� -');
      }else{
        $(this).text('��ѡ +');
        $('#duoxuan_box0 a').removeClass('on');
        $('#box0 a').remove();
      }
    }) 

  /***********duoxuan_box ���id************/
      function obj_id(objid){        
        objid.each(function(index){
          var _this=$(this); 
          _this.attr('id',(_this.attr('class')+''+index)); 
          var dadtobj1=_this.attr('id');  
          var dadtobj1_string=dadtobj1.substring(8,12); 
          var dadtobj=$('#'+dadtobj1).find('a');    
          obj_dadt(dadtobj);          
          appendon(dadtobj1_string); 

          dadtobj.on('click', function(){
            var copyThis = $(this).clone();
            var duoxuan_btn_text= $('#duoxuan_btn').text();  
            var thisdadt=$(this).attr('dadt');
            var thisdadt_string=thisdadt.substring(8,12);            
            clickon($(this),duoxuan_btn_text,thisdadt_string,copyThis);   

      
            /***********�����ѡ��ǩȥ��ѡ����ʽ************/
            $('.point_center a').on('click', function () {
              var _thisall = $(this);
              var _this_dadt = _thisall.attr('dadt');
              var _this_dadt_text = _this_dadt.substring(0, 12);
              $(this).remove();
              //alert($("dd a[dadt="+_this_dadt+"]").text())
              $("dd a[dadt="+_this_dadt+"]").removeClass('on');
            })           
          })


        });

      }



  /***********duoxuan_box a���dadt************/
  function obj_dadt(dadtobj){
    dadtobj.each(function(index){
      $(this).attr('dadt',dadtobj.parent('dd').attr('id')+"_"+index);
    });
  }

  /***********�����ѡ��ǩ����************/
    function appendon(appobjid){
      $('.point_center i').before('<span class="point_tips" id="'+appobjid+'"></span>');
    }

  /***********����¼�************/
    function clickon(_thisa,duoxuan_btn_text,thisdadt_string,copyThis){    
      if(thisdadt_string=='box0' & duoxuan_btn_text=='ȡ�� -'){
        if(_thisa.is('.on')){        
          _thisa.removeClass('on'); 
          removeobjs(copyThis,thisdadt_string,_thisa);

        }else{
          _thisa.addClass('on');
          addobjs(copyThis,thisdadt_string);
  
        }     
      }else{
        if(_thisa.is('.on')){
          _thisa.removeClass('on');
          removeobj(copyThis,thisdadt_string,_thisa);
        }else{
          _thisa.addClass('on').siblings('a').removeClass('on');
          addobj(copyThis,thisdadt_string);
        }
   
      }

    }


  /***********��ѡ �����ѡ��ǩ************/
    function addobj(copyThis,thisdadt_string){
      $('#'+thisdadt_string).find('a').remove();
      $('#'+thisdadt_string).append(copyThis.attr("class", "on")); 
      var oldId = copyThis.attr("id");
      var newId="";
      if(oldId.indexOf("ycd")>-1)
      {
    	  newId = "s"+oldId;
      }else{
    	  newId = "ctgId"+oldId;
      }
     
      $('#'+thisdadt_string).append(copyThis.attr("id",newId));
    }

  /***********��ѡ ɾ����ѡ��ǩ************/
  function removeobj(copyThis,thisdadt_string,_thisa){
    $('#'+thisdadt_string).find('a').each(function(){
      $('#'+thisdadt_string).find('a').remove();
    })
  }

  /***********��ѡ �����ѡ��ǩ************/
  function addobjs(copyThis,thisdadt_string){
    // $('#'+thisdadt_string).find('a').remove();
    $('#'+thisdadt_string).append(copyThis.attr("class", "on")); 
  }

  /***********��ѡ ɾ����ѡ��ǩ************/
  function removeobjs(copyThis,thisdadt_string,_thisa){
    $('#'+thisdadt_string+' a').each(function(i,e){
      if($(this).attr('dadt')==$(_thisa).attr('dadt')){
          $('#'+thisdadt_string+' a').eq(i).remove();
      }
    })
  }

});