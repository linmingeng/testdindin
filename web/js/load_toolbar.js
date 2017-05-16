$(function(){
	//加载我的帐户左侧菜单
	$.ajax({
		url : './common/toolbar.jsp',
		type : 'POST',
		success : function(data){
			$('#column-left .list-group').append(data);
		}
	})
})