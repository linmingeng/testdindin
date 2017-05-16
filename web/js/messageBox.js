
/**
 * 登陆js
 */
//消息弹出框
var messageBox='<div id="messageBoxId" class="modal fade bs-example-modal-sm in" tabindex="-1" role="dialog" aria-hidden="false" style="display: none;"><div class="modal-dialog modal-sm" style="width:300px;"><div class="modal-content" style="padding-bottom: 10px;"><div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h4 class="modal-title">{0}</h4></div><div class="modal-body">{1}</div><div class="row" style="text-align: center;"></div></div></div></div>';
/**
 * 添加messagebox 代码
 * @param {Object} messageBoxStr
 */
function showTipMessageBox(title,message){
	$("#messageBoxId").remove();
	var messageBox1=messageBox.replace("{0}",title).replace("{1}",message);	
	$("body").append(messageBox1);
	$("#messageBoxId").modal('show');
}
