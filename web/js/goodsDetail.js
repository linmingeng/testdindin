/**
 * ��Ʒ����js
 */
function loadGoogsDetail(goodID){
	initComnentView(goodID,'1','all');
	loadSaleRecordsList(goodID,'1');
}


//��ʼ�����б�
function initComnentView(goodsId,currPage,grade){
	
	loadCommentList(goodsId,currPage,grade);
	
	
}

/**
 * ��ȡ�����б�����
 * @param {Object} pageSize
 * @param {Object} currPage
 */
function loadCommentList(goodsId,currPage,grade){
	/**
	 * ��ȡ��������
	 * @param {Object} data
	 */
	$.ajax({type: "POST",dataType:'json',async: false,data:"currPage="+currPage+"&goodsId="+goodsId+"&grade="+grade,url:"goodsCommentList!getCommentList.go",success: function(data){
			var totalCount = data.totalRecord;
			var totalPage =  data.totalPage;
			var commentList = data.goodsCommentList;
			var spanHtml = "";
			for(var i=0;i<commentList.length;i++){
				//����
				var arr = new Array();
				//��������
				arr[0] = commentList[i].content;//����
				arr[1] = commentList[i].createTime;//ʱ��
				arr[2] = commentList[i].usrNumber;//������
				arr[3] = ""
				if(commentList[i].isAnonymity==1){
					
					arr[3] = "(����)";//����					
				}
				
				spanHtml +=strFormat(arr);
			}
			$("#commentUlId").html(spanHtml);
			
			//���ɷ�ҳbtn
			createPageTools(currPage,totalPage,goodsId,grade);
	}});
}

function loadSaleRecordsList(goodsId,currPage){
	$.ajax({type: "POST",dataType:'json',async: false,data:"currPage="+currPage+"&goodsId="+goodsId,url:"saleRecords!getSaleRecords.go",success: function(data){
			var totalCount = data.totalRecord;
			var totalPage =  data.totalPage;
			var saleRecordsList = data.saleRecordsVOList;
			var spanHtml = "<tr><th>���</th><th>��ʽ/�ͺ� </th><th>����</th><th>�ɽ�ʱ��</th></tr>";
			for(var i=0;i<saleRecordsList.length;i++){
				var arr = new Array();
				arr[0] = saleRecordsList[i].buyerName;
				arr[1] = saleRecordsList[i].style;
				arr[2] = saleRecordsList[i].num;
				arr[3] = saleRecordsList[i].time;
				
				spanHtml += saleRecordsHtml(arr);
			}
			$("#table-saleRecords").html(spanHtml);
			
			//���ɷ�ҳbtn
			createPageLink(currPage,totalPage,goodsId);
	}});
}

/**
 * ������ҳtool
 */
function createPageTools(currPage,totalPage,goodsId,grade){
	var pageTools = "";
	for(var i=1;i<=totalPage;i++){
		//��һҳ��ͷ
		if(i==1){
			if(currPage==1){
	
				pageTools +=pageStrFormat("loadCommentList("+goodsId+","+i+",'"+grade+"')",'<',false);
			}else{
				
				pageTools +=pageStrFormat("loadCommentList("+goodsId+","+i+",'"+grade+"')",'<',true);
			}
			
		}
		
		pageTools +=pageStrFormat("loadCommentList("+goodsId+","+i+",'"+grade+"')",i,true);
		
		if(i==totalPage){

			pageTools +=pageStrFormat("loadCommentList("+goodsId+","+i+",'"+grade+"')",">",true);			
		}
		
	}
	
	$("#pageToolsUiID").html(pageTools);
	
}

function createPageLink(currPage,totalPage,goodsId){
	var pageTools = "";
	for(var i=1;i<=totalPage;i++){
		//��һҳ��ͷ
		if(i==1){
			if(currPage==1){
	
				pageTools +=pageStrFormat("loadSaleRecordsList("+goodsId+","+i+")",'<',false);
			}else{
				
				pageTools +=pageStrFormat("loadSaleRecordsList("+goodsId+","+i+")",'<',true);
			}
			
		}
		
		pageTools +=pageStrFormat("loadSaleRecordsList("+goodsId+","+i+")",i,true);
		
		if(i==totalPage){

			pageTools +=pageStrFormat("loadSaleRecordsList("+goodsId+","+i+")",">",true);			
		}
		
	}
	
	$("#saleRecordsPageLink").html(pageTools);
	
}

/**
 * �ַ�����ʽ��
 * @param {Object} arr
 * @return {TypeName} 
 */
function strFormat(arr){
	
	return '<li><table><tbody><tr><td><div class="p-con"><div class="first-text"><p>{0}</p></div> </div><p class="date">{1}</p> </td><td><span class="show-user">{2}</span><span class="show-name">{3}</span></td></tr></tbody></table></li>'.format(arr[0],arr[1],arr[2],arr[3]);
}

function saleRecordsHtml(arr){	
	//var template =	"<tr><th>���</th><th>��ʽ/�ͺ� </th><th>����</th><th>�ɽ�ʱ��</th></tr>";
	var	template = "<tr><td>{0}</td><td>{1}</td><td>{2}</td>";		
		template += "<td><div>{3}</div></td>";				
		template += "</tr>";
		
	return template.format(arr[0],arr[1],arr[2],arr[3]);	
}

/**
 * 
 * @param {Object} url ���ӵ�ַ
 * @param {Object} pageNum ҳ��
 * @param {Object} usableFlag ���ñ�ʶ
 * @return {TypeName} 
 */
function pageStrFormat(url,pageNum,usableFlag){
	var str = '<li><a onclick="'+url+'">';
	if(!usableFlag){
		
		str = '<li><a onclick="'+url+'" disabled="disabled">';
	}
	str+=pageNum+'</a></li>';
	return str;
}

/**
 * ģ�����ת��
 * @returns 
 * 			�滻���������ֶ�
 * @author ljy
 */
String.prototype.format=function()
{
  if(arguments.length==0) return this;
  for(var s=this, i=0; i<arguments.length; i++)
    s=s.replace(new RegExp("\\{"+i+"\\}","g"), arguments[i]);
  return s;
};
