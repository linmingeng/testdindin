$(function(){
	//����ͷ��������Ϣ
	//$.ajax({
		//async:false,
		//url : './common/header.jsp',
		//type : 'POST',
		//success : function(data){
			//$('#cont-container').prepend(data);
			//����IE�˵�
			if($(window).width()>=975){
				$('#top-menu .has-sub>ul').width(850).css('margin-left','-92px');
			}else if($(window).width()<=623){
				$('#top-menu .has-sub>ul').css({'width':'100%','margin-left':'0'});
			}else if($(window).width()<=974){
				$('#top-menu .has-sub>ul').width(600).css('margin-left','0');
			}
			window.onresize=function(){
				if($(window).width()>=975){
				$('#top-menu .has-sub>ul').width(850).css('margin-left','-92px');
				}else if($(window).width()<=623){
					$('#top-menu .has-sub>ul').css({'width':'100%','margin-left':'0'});
				}else if($(window).width()<=974){
					$('#top-menu .has-sub>ul').width(600).css('margin-left','0');
				}
			}
			// ����ENd
			
			//�ж������Ϊiphone
			var browser={
			    versions:function(){
			           var u = navigator.userAgent, app = navigator.appVersion; 
			           return {//�ƶ��ն�������汾��Ϣ 
			                iPhone: u.indexOf('iPhone') > -1 , //�Ƿ�ΪiPhone
			                iPad: u.indexOf('iPad') > -1, //�Ƿ�iPad
			            };
			         }(),
			         language:(navigator.browserLanguage || navigator.language).toLowerCase()
			} 
			//Ϊiphone��ipadʱ����¼��λΪ���Զ�λ��������������
			if(browser.versions.iPhone>0 || browser.versions.iPad>0){
				$('#colorbox.loginbox').css({'position':'fixed','top':'300px'});
			}
			if(browser.versions.iPad>0){
				$('#colorbox.loginbox').css({'position':'absolute','top':'400px'});
			}
			var text = '';
			/*
			$.ajax({  //��̬��ȡͷ���˵�
				url:'./js/menuData.js',
				type:'GET',
				//async:false,
				dataType:'json',
				success:function(data){
					text = data;
					//��̬���ط�����Ϣ   
					var categoryImgArr = [
											{imgSrc:'./images/categoryImg/ct1.jpg',linkSrc:'http://www.dindin.com/goodsDetail_id_19276.htm',icon:'./images/categoryImg/cate_icon_1.png'},
						{imgSrc:'./images/categoryImg/ct2.jpg',linkSrc:'http://www.dindin.com/goodsDetail_id_18904.htm',icon:'./images/categoryImg/cate_icon_4.png'},
						{imgSrc:'./images/categoryImg/ct3.jpg',linkSrc:'http://www.dindin.com/goodsDetail_id_18608.htm',icon:'./images/categoryImg/cate_icon_3.png'},
						{imgSrc:'./images/categoryImg/ct4.jpg',linkSrc:'http://www.dindin.com/goodsDetail_id_18607.htm',icon:'./images/categoryImg/cate_icon_5.png'},
						{imgSrc:'./images/categoryImg/ct5.jpg',linkSrc:'http://www.dindin.com/goodsDetail_id_18504.htm',icon:'./images/categoryImg/cate_icon_2.png'},
						{imgSrc:'./images/categoryImg/ct6.jpg',linkSrc:'http://www.dindin.com/goodsDetail_id_18605.htm',icon:'./images/categoryImg/cate_icon_7.png'},
						{imgSrc:'./images/categoryImg/ct7.jpg',linkSrc:'http://www.dindin.com/goodsDetail_id_19154.htm',icon:'./images/categoryImg/cate_icon_6.png'}];
	
					var imgIndex = 0;
					for(var i in text){  //һ���˵�
						//console.log(categoryImgArr);
						var first = i.substr(i.lastIndexOf('_')+1,i.length);
						var str = '<li class="top has_sub top first-cate-li">';
				    	str += '<a class="sub_trigger first-cate" onclick="showMenu(this)" id='+i.substr(0,i.lastIndexOf('_'))+'><img src='+categoryImgArr[imgIndex].icon+'>'+first+'<i class="fa fa-sort-desc"></i></a>';
				   		str += '<div class="wrapper" style=" width:630px">';
				   		str += '<div class="row">';
				   		str	+= '<div class="col-sm-6  mobile-enabled">';
				   		str += '<div class="row">';
				   		str += '<div class="col-sm-12 hover-menu">';
				   		str += '<div class="menu">';
						str += '<ul>';
						
						for(var j in text[i]){  //�����˵�
							//console.log(j+'---');
							var sec = j.substr(j.lastIndexOf('_')+1,j.length);
							var id =j.split("_")[1];
							var name =encodeURI(encodeURI(j.split("_")[2]));
							var url = "search.jsp?classId="+id+"&subClassId="+name+"&PTAG=20051.15.1";
							str += '<li class="">';
							str += '<a href="'+url+'" class="main-menu sec-cate" id='+j.substr(0,j.lastIndexOf('_'))+'>'+sec;
							str += '</a>';
							str += '<ul class="three-cate" style="color:#666;padding-left:30px;">';
							for(var k in text[i][j]){  //�����˵�
								//console.log(text[i][j][k].indexOf('/')+'---'+text[i][j][k]);
								var three = text[i][j][k].substr(0,text[i][j][k].indexOf('_'));
								
								var id =k.split("_")[1];
								var name =encodeURI(encodeURI(three));
								var url = "search.jsp?classId="+id+"&subClassId="+name+"&PTAG=20051.15.1";
								
							 	str += '<li><a href="'+url+'" id='+text[i][j][k].substr(text[i][j][k].indexOf('_')+1,text[i][j][k].length)+'>'+three+'</a></li>';
							}
							str += '</ul>';
							str += '</li>';
						}
						str += '</ul>';
				   		str += '</div>';
				   		str += '</div>';
				   		str += '</div>';
				   		str += '</div>';
				    	str += '<div class="col-sm-6  mobile-enabled">';
				    	if(categoryImgArr[imgIndex] ==undefined){
				    		str += '<a href="javascript:void(0)"><img src="" width="100%"/></a>';
				    	}else{
				 			str += '<a href='+categoryImgArr[imgIndex].linkSrc+'><img src='+categoryImgArr[imgIndex].imgSrc+' width="100%"/></a>';
				    	}
				 		str += '</div>';
				   		str += '</div>';
				   		str += '</div>';
				    	str += '</li>';
			   			$('#main-category').append(str);
			   			//console.log(i);
			   			imgIndex++;
			   			
			   		}
			//  End
				}
			});*/
			
			
			
			//getCartInfo('');//��ȡ���ﳵ��Ϣ��ӵ�ҳ��
			
			//ÿ��ҳ�����¼
	   		var username = userLogin.isLogin();
	   		if(username!=''){
				$('#nologin').hide();
				$('#user_nick').text(username).attr('title',username);
				$('#user_login').show();
			}else{
				$('#nologin').show();
				$('#user_login').hide();
			}
		//}
	//})

	//���صײ�������Ϣ
	$.ajax({
		url : './common/footer.jsp',
		type : 'POST',
		success : function(data){
			$('.footer_margin').after(data);
			// Scroll to top button //
			var scroll_right = $(".row.footer").offset().left;
			$(".scroll_top").css('right', (scroll_right - 100) + 'px');
			
			var windowScroll_t;
			$(window).scroll(function(){
				clearTimeout(windowScroll_t);
				windowScroll_t = setTimeout(function() {
						
				if ($(this).scrollTop() > 100)
				{ $('.scroll_top').addClass('active'); }
				else
				{ $('.scroll_top').removeClass('active'); }
				}, 200);
			});
			
			$('.scroll_top').click(function(){
				$("html, body").animate({scrollTop: 0}, 1000);
				return false;
			});
		}
	})
	
})

//����С��991ʱ����ʾ�Ӳ˵��¼�  
function showMenu(thiss){
	//console.log(0);
	if ($(window).width() < 991) {
            event.preventDefault();
            $(thiss).parent().find('.wrapper').stop(true, true).slideToggle(350).end().siblings().find('.wrapper').slideUp(350);
        }
}

