<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<title>上传文件</title>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>

<div id="container">
	<a id="selectfiles" href="javascript:void(0);" class='btn'>选择文件</a>
	<a id="postfiles" href="javascript:void(0);" class='btn'>开始上传</a>
	<br>
	<span id="list"></span>
	<br>
	<span id="pre"></span>
	<br>
	<span id="log"></span>
</div>

</body>
<script type="text/javascript" src="../plupload-2.1.2/js/plupload.full.min.js"></script>
<script type="text/javascript" >

var accessid = '',
accesskey = '',
host = '',
policyBase64 = '',
signature = '',
callbackbody = '',
filename = '',
key = '',
expire = 0,
g_object_name = '',
g_object_name_type = 'local_name',   //local_name random_name
now = timestamp = Date.parse(new Date()) / 1000;

function send_request(){
    var xmlhttp = null;
    if (window.XMLHttpRequest){
        xmlhttp=new XMLHttpRequest();
    }else if (window.ActiveXObject){
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (xmlhttp!=null){
        serverUrl = './uploadPolicy.php'
        xmlhttp.open( "GET", serverUrl, false );
        xmlhttp.send( null );
        return xmlhttp.responseText
    }else{
        alert("Your browser does not support XMLHTTP.");
    }
};

function get_signature(){
    //可以判断当前expire是否超过了当前时间,如果超过了当前时间,就重新取一下.3s 做为缓冲
    now = timestamp = Date.parse(new Date()) / 1000; 
    if (expire < now + 3){
        body = send_request()
        var obj = eval ("(" + body + ")");
        host = obj['host'];
        policyBase64 = obj['policy'];
        accessid = obj['accessid'];
        signature = obj['signature'];
        expire = parseInt(obj['expire']);
        callbackbody = obj['callback'] ;
        key = obj['dir'];
        return true;
    }
    return false;
};

function random_string(len) {
　　len = len || 32;
　　var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';   
　　var maxPos = chars.length;
　　var pwd = '';
　　for (i = 0; i < len; i++) {
    　　pwd += chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}

function get_suffix(filename) {
    var pos = filename.lastIndexOf('.');
    var suffix = '';
    if (pos != -1) {
        suffix = filename.substring(pos)
    }
    return suffix;
}

function calculate_object_name(filename){
    if (g_object_name_type == 'local_name'){
        g_object_name += "${filename}";
    }else if (g_object_name_type == 'random_name'){
        var suffix = get_suffix(filename);
        g_object_name = key + random_string(10) + suffix;
    }
    return '';
}

function get_uploaded_object_name(filename){
    if (g_object_name_type == 'local_name'){
        var tmp_name = g_object_name;
        tmp_name = tmp_name.replace("${filename}", filename);
        return tmp_name;
    }else if(g_object_name_type == 'random_name'){
        return g_object_name;
    }
}

function set_upload_param(up, filename, ret){
    if (ret == false){
        ret = get_signature();
    }
    g_object_name = key;
    if (filename != '') {
        calculate_object_name(filename);
    }
    new_multipart_params = {
        'key' : g_object_name,
        'policy': policyBase64,
        'OSSAccessKeyId': accessid, 
        'success_action_status' : '200', //让服务端返回200,不然，默认会返回204
        'callback' : callbackbody,
        'signature': signature,
    };

    up.setOption({
        'url': host,
        'multipart_params': new_multipart_params
    });

    up.start();
}
//doc: http://www.cnblogs.com/2050/p/3913184.html
var uploader = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',
    browse_button : 'selectfiles', 
    multi_selection: true,
    container: document.getElementById('container'),
    flash_swf_url : 'js/Moxie.swf',
    silverlight_xap_url : 'js/Moxie.xap',
    //chunk_size: 10,
    max_retries: 3,
    resize: {
      width: 100,
      height: 100,
      crop: true,
      quality: 60,
      preserve_headers: false
    },
    url : 'http://oss.aliyuncs.com',
    filters: {
        mime_types : [              //只允许上传图片和zip,rar文件
            { title : "Image files", extensions : "webp,jpg,gif,png,bmp,jpe,jpeg,psd,cod,ief,jfif,svg,tif,tiff,ras,cmx,ico,pnm,pbm,pgm,ppm,rgb,xbm,xpm,xwd" }, 
            { title : "Zip files", extensions : "zip,rar,tar,7z,cab,ace,gzip,gz" }, 
            { title : "Doc files", extensions : "pdf,xls,xlsx,et,ett,xlt,xlsm,dbf,csv,prn,dif,xlts,xltm,doc,docx,wps,wpt,xml,mhtml,dot,rtf,dotx,docm,ppt,pptx,dps,dpt,pod,pps,pptm,potx,potm,ppsx,ppsm,txt,html,htm" }, 
            { title : "Audio files", extensions : "mp3,wma,rm,ram,wav,amr,m3u,ogg,ape,au,snd,mid,midi,rmi,aifc,aiff,ra,aac,mmf,flac" }, 
            { title : "Video files", extensions : "mp2,mpa,mpe,mpeg,mpg,mpv2,mov,qt,lsf,lsx,asf,asr,asx,avi,movie,mp4,swf,flv,rmvb,wmv,dat" }
        ],
        max_file_size : '100mb',     //最大只能上传10mb的文件
        prevent_duplicates : false   //不允许选取重复文件
    },

    init: {
        PostInit: function() {
            document.getElementById('pre').innerHTML = '';
            document.getElementById('postfiles').onclick = function() {
                set_upload_param(uploader, '', false);
                return false;
            };
        },
        FilesAdded: function(up, files) {
            uploader.disableBrowse(true);
            var list = '';
            for(var k in files ){
                list+=files[k].name+',<br>';
            }
            document.getElementById('list').innerHTML = list;
            
        },
        BeforeUpload: function(up, file) {
            set_upload_param(up, file.name, true);
        },

        UploadProgress: function(up, file) {
            document.getElementById('pre').innerHTML = '' + file.percent + '%';
        },

        FileUploaded: function(up, file, info) {
            document.getElementById('pre').innerHTML = '';
            uploader.disableBrowse(false);
            if (info.status == 200){
                document.getElementById('log').innerHTML = JSON.stringify(file);
            }else{
                document.getElementById('log').innerHTML = info.response;
            } 
        },

        Error: function(up, err) {
            if (err.code == -600) {
                alert("选择的文件太大了,可以根据应用情况，在upload.js 设置一下上传的最大大小");
            }else if (err.code == -601) {
                alert("选择的文件后缀不对,可以根据应用情况，在upload.js进行设置可允许的上传文件类型");
            }else if (err.code == -602) {
                alert("这个文件已经上传过一遍了");
            }else {
                alert("Error xml:" + err.response);
            }
        }
    }
});

uploader.init();
    
</script>
</html>
