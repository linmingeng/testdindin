/* 
*	jQuery�ļ��ϴ����,��װUI,�ϴ������������Baidu WebUploader;
*	@Author ��צצ;
*/
var i=0;
(function( $ ) {
	
    $.fn.extend({
		/*
		*	�ϴ����� optΪ��������;
		*	serverCallBack�ص����� ÿ���ļ��ϴ�������˺�,����˷��ز���,���۳ɹ�ʧ�ܶ������ ����Ϊ������������Ϣ;
		*/
        diyUpload:function( opt, serverCallBack ) {
 			if ( typeof opt != "object" ) {
				alert('��������!');
				return;	
			}
			
			var $fileInput = $(this);
			var $fileInputId = $fileInput.attr('id');
			
			//��װ����;
			if( opt.url ) {
				opt.server = opt.url; 
				delete opt.url;
			}
			
			if( opt.success ) {
				var successCallBack = opt.success;
				delete opt.success;
			}
			
			if( opt.error ) {
				var errorCallBack = opt.error;
				delete opt.error;
			}
			
			//������Ĭ������
			$.each( getOption( '#'+$fileInputId ),function( key, value ){
					opt[ key ] = opt[ key ] || value; 
			});
			
			if ( opt.buttonText ) {
				opt['pick']['label'] = opt.buttonText;
				delete opt.buttonText;	
			}
			
			var webUploader = getUploader( opt );
			
			if ( !WebUploader.Uploader.support() ) {
				alert( ' �ϴ������֧�������������');
				return false;
       		}
			
			//���ļ���������¼�;
			webUploader.on('fileQueued', function( file ) {
				createBox( $fileInput, file ,webUploader);
			
			});
			
			//�������¼�
			webUploader.on('uploadProgress',function( file, percentage  ){
				var $fileBox = $('#fileBox_'+file.id);
				var $diyBar = $fileBox.find('.diyBar');	
				$diyBar.show();
				percentage = percentage*100;
				showDiyProgress( percentage.toFixed(2), $diyBar);
				
			});
			
			//ȫ���ϴ������󴥷�;
			webUploader.on('uploadFinished', function(){
				$fileInput.next('.parentFileBox').children('.diyButton').remove();
			});
			//�󶨷���������˷��غ󴥷��¼�;
			webUploader.on('uploadAccept', function( object ,data ){
				if ( serverCallBack ) serverCallBack( data );
			});
			
			//�ϴ��ɹ��󴥷��¼�;
			webUploader.on('uploadSuccess',function( file, response ){
				var $fileBox = $('#fileBox_'+file.id);
				var $diyBar = $fileBox.find('.diyBar');	
				$fileBox.removeClass('diyUploadHover');
				$diyBar.fadeOut( 1000 ,function(){
					$fileBox.children('.diySuccess').show();
				});
				if ( successCallBack ) {
					successCallBack( response );
				}	
			});
			
			//�ϴ�ʧ�ܺ󴥷��¼�;
			webUploader.on('uploadError',function( file, reason ){
				var $fileBox = $('#fileBox_'+file.id);
				var $diyBar = $fileBox.find('.diyBar');	
				showDiyProgress( 0, $diyBar , '�ϴ�ʧ��!' );
				var err = '�ϴ�ʧ��! �ļ�:'+file.name+' ������:'+reason;
				if ( errorCallBack ) {
					errorCallBack( err );
				}
			});
			
			//ѡ���ļ����󴥷��¼�;
			webUploader.on('error', function( code ) {
				var text = '';
				switch( code ) {
					case  'F_DUPLICATE' : text = '���ļ��Ѿ���ѡ����!' ;
					break;
					case  'Q_EXCEED_NUM_LIMIT' : text = '�ϴ��ļ�������������!' ;
					break;
					case  'F_EXCEED_SIZE' : text = '�ļ���С��������!';
					break;
					case  'Q_EXCEED_SIZE_LIMIT' : text = '�����ļ��ܴ�С��������!';
					break;
					case 'Q_TYPE_DENIED' : text = '�ļ����Ͳ���ȷ�����ǿ��ļ�!';
					break;
					default : text = 'δ֪����!';
 					break;	
				}
            	alert( text );
        	});
        }
    });
	
	//Web UploaderĬ������;
	function getOption(objId) {
		/*
		*	�����ļ�ͬwebUploaderһ��,����ֻ����Ĭ������.
		*	�������:http://fex.baidu.com/webuploader/doc/index.html
		*/
		return {
			//��ť����;
			pick:{
				id:objId,
				label:"���ѡ��ͼƬ"
			},
			//��������;
			accept:{
				title:"Images",
				extensions:"gif,jpg,jpeg,bmp,png",
				mimeTypes:"image/*"
			},
			//������������ͼ��ѡ��
			thumb:{
				width:300,
				height:300,
				// ͼƬ������ֻ��typeΪ`image/jpeg`��ʱ�����Ч��
				quality:100,
				// �Ƿ�����Ŵ������Ҫ����Сͼ��ʱ��ʧ�棬��ѡ��Ӧ������Ϊfalse.
				allowMagnify:false,
				// �Ƿ�����ü���
				crop:false,
				// Ϊ�յĻ�����ԭ��ͼƬ��ʽ��
				// ����ǿ��ת����ָ�������͡�
				type:"image/jpeg"
			},
			//�ļ��ϴ���ʽ
			method:"POST",
			//��������ַ;
			server:"",
			//�Ƿ��Ѷ����Ƶ����ķ�ʽ�����ļ������������ϴ�����php://input��Ϊ�ļ�����
			sendAsBinary:true,
			// �����Ƭ�ϴ��� thinkphp���ϴ�����Է�Ƭ��Ч,ͼƬ��ʧ;
			chunked:true,
			// ��Ƭ��С
			chunkSize:512 * 1024,
			//����ϴ����ļ�����, ���ļ���С,�����ļ���С(��λ�ֽ�);
			fileNumLimit:50,
			fileSizeLimit:5000 * 1024,
			fileSingleSizeLimit:500 * 1024
		};
	}
	
	//ʵ����Web Uploader
	function getUploader( opt ) {

		return new WebUploader.Uploader( opt );;
	}
	
	//����������;
	function showDiyProgress( progress, $diyBar, text ) {
		
		if ( progress >= 100 ) {
			progress = progress + '%';
			text = text || '�ϴ����';
		} else {
			progress = progress + '%';
			text = text || progress;
		}
		
		var $diyProgress = $diyBar.find('.diyProgress');
		var $diyProgressText = $diyBar.find('.diyProgressText');
		$diyProgress.width( progress );
		$diyProgressText.text( text );
	
	}
	
	//ȡ���¼�;	
	function removeLi ( $li ,file_id ,webUploader) {
		webUploader.removeFile( file_id );
		if ( $li.siblings('li').length <= 0 ) {
			//$li.parents('.parentFileBox').next().next().remove();
			//$li.parents('.parentFileBox').remove();
			$li.find('.viewThumb img').attr('src','./images/member-default-img.png');
		} else {
			$li.remove();
		}
		
	}
	
	//�����ļ�����div;	
	function createBox( $fileInput, file, webUploader ) {

		var file_id = file.id;
		var $parentFileBox = $fileInput.prev('.parentFileBox');
		
		//��Ӹ�ϵ����;
		//if ( $parentFileBox.length <= 0 ) {
		//	
		//	var div = '<div class="parentFileBox"> \
		//				<ul class="fileBoxUl"></ul>\
		//			</div>';
		//	$fileInput.before( div );
		//	$parentFileBox = $fileInput.prev('.parentFileBox');
		
		//}
		
		//������ť
		if ( $parentFileBox.find('.diyButton').length <= 0 ) {
			
			var div = '<div class="diyButton"> \
						<a class="diyStart" href="javascript:void(0)">��ʼ�ϴ�</a> \
						<a class="diyCancelAll" href="javascript:void(0)">ȡ��</a> \
					</div>';
			if($parentFileBox.next().next().length<=0){
				$parentFileBox.next().after( div );
			}
			var $startButton = $parentFileBox.next().next().find('.diyStart');
			var $cancelButton = $parentFileBox.next().next().find('.diyCancelAll');
			
			//��ʼ�ϴ�,��ͣ�ϴ�,�����ϴ��¼�;
			var uploadStart = function (){
				
			//	webUploader.upload();
				/*$startButton.one('click',function(){
						webUploader.stop();						$(this).text('�����ϴ�').one('click',function(){
								uploadStart();
						});
					
					
				});*/
				//alert('aa1111');
				//$startButton.hide();
				//$();
				
				//$startButton.one('click',uploadStart);
			
				if(i==0){
					
					$.ajax({
				   type: "POST",
				   url: "userinfoEditAction!uploadImage.go",
				   dataType:"json",
				   async:false,
				   data: "image="+$(".viewThumb").find("img").attr("src"),
				   success: function(msg){
					alert(msg.message);
					//��ת��ַ
					window.location.href="userInfoManageAction!edit.go";
						
				   }
				});
					i++;
				}
				
				
			}
				
			//�󶨿�ʼ�ϴ���ť;
			$startButton.one('click',uploadStart);
			
			//��ȡ��ȫ����ť;
			$cancelButton.bind('click',function(){
				var fileArr = webUploader.getFiles( 'queued' );
				$.each( fileArr ,function( i, v ){
					removeLi( $('#fileBox_'+v.id), v.id, webUploader );
				});
				$("#upload_user_img").hide();
			});
		
		}
			
		//���������;
		//var li = '<li id="fileBox_'+file_id+'" class="diyUploadHover"> \
		//			<div class="viewThumb"></div> \
		//			<div class="diyCancel"></div> \
		//			<div class="diySuccess"></div> \
		//			<div class="diyFileName">'+file.name+'</div>\
		//			<div class="diyBar"> \
		//					<div class="diyProgress"></div> \
		//					<div class="diyProgressText">0%</div> \
		//			</div> \
		//		</li>';
				
		//$parentFileBox.children('.fileBoxUl').append( li );
		
		$parentFileBox.find('.fileBoxUl').find('.diyUploadHover').attr('id',file_id); 
		$parentFileBox.find('.fileBoxUl').find('.diyFileName').text(file.name);
		//���������;
		var $width = $('.fileBoxUl>li').length * 180;
		var $maxWidth = $fileInput.parent().width();
		$width = $maxWidth > $width ? $width : $maxWidth;
		$parentFileBox.width( $width );
		
		var $fileBox = $parentFileBox.find('#fileBox_'+file_id);

		//��ȡ���¼�;
		var $diyCancel = $('.diyCancel').one('click',function(){
			removeLi( $(this).parent('li'), file_id, webUploader );	
		});
		
		if ( file.type.split("/")[0] != 'image' ) {
			var liClassName = getFileTypeClassName( file.name.split(".").pop() );
			$fileBox.addClass(liClassName);
			return;	
		}
		
		//����Ԥ������ͼ;
		webUploader.makeThumb( file, function( error, dataSrc ) {
			if ( !error ) {	
				//$fileBox.find('.viewThumb').append('<img src="'+dataSrc+'" >');
				$parentFileBox.find('.fileBoxUl').find('.viewThumb img').attr('src',dataSrc);
			}
		});	
	}
	
	//��ȡ�ļ�����;
	function getFileTypeClassName ( type ) {
		var fileType = {};
		var suffix = '_diy_bg';
		fileType['pdf'] = 'pdf';
		fileType['zip'] = 'zip';
		fileType['rar'] = 'rar';
		fileType['csv'] = 'csv';
		fileType['doc'] = 'doc';
		fileType['xls'] = 'xls';
		fileType['xlsx'] = 'xls';
		fileType['txt'] = 'txt';
		fileType = fileType[type] || 'txt';
		return 	fileType+suffix;
	}
	
})( jQuery );