<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
               
            });
            function sendPushNotification(id){
                var data = $('form#'+id).serialize();				
                $('form#'+id).unbind('submit'); 				 
				var div = '.id'+id; 			          
                $.ajax({
                    url: "apns.php?action=pushMessageToone",
                    type: 'GET',
                    data: data,
                    beforeSend: function() {
						   html = "<b>请等待，正在发送中……</b>";
						   $(div).html(html);
                         
                    },
                    success: function(data, textStatus, xhr) {
                          $('.txt_message').val("");
						   $(div).html(data);
						   
						  
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        
                    }
                });
                return false;
            }
			 function sendPushall(){
                var data = $('form#all').serialize();
                $('form#all').unbind('submit');  
				             
                $.ajax({
                    url: "apns.php?action=pushMessageToALL",
                    type: 'GET',
                    data: data,
                    beforeSend: function() {
						
						   html = "<b>请等待，正在发送中……</b>";
						    $('.info').html(html);
                        
                    },
                    success: function(data, textStatus, xhr) {
                          $('.txt_message').val("");						  
						  $('.info').html(data);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        
                    }
                });
                return false;
            }
		 function add(){
                var data = $('form#add').serialize();
                $('form#add').unbind('submit');			             
                $.ajax({
                    url: "apns.php?action=registerDevices",
                    type: 'GET',
                    data: data,
                    beforeSend: function() {
						
						   html = "<b>请等待，正在提交……</b>";
						    $('.add').html(html);
                        
                    },
                    success: function(data, textStatus, xhr) {
                          $('.txt_message').val("");						  
						  $('.add').html(data);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        
                    }
                });
                return false;
            }
        </script>
     
    </head>
    <body>
    	<div class="head">
         <h1>ios 消息推送管理</h1>
         <span><a href="index.php">发送推送消息</a></span>
         <span><a href="index.php?action=add">增加客户端设备</a></span>
         <span><a href="index.php?action=del">删除客户设备</a></span>
         <span><a href="index.php?action=update">修改客户设备</a></span>
          <hr/>
        </div>
        <?php
        include_once 'config.inc.php';
		require_once('db.class.php');
        $db = new DB();
		$sql = "SELECT * FROM `".data_tab."` ORDER BY `deviceid` ASC ";
		 
        $users = mysql_query($sql);
		//$info1 = mysql_fetch_row($users); 
		//print_r($info1);
		
        if ($users != false)
            $no_of_users = mysql_num_rows($users);
        else
            $no_of_users = 0;
		
		 switch ($_REQUEST['action']){
        	case 'add':
			  ?>
              <div class="container">
               <ul class="devices">
              <li>
                 <form id="add" name="" method="post" onsubmit="return add()">
                   
                     <p><label>姓名: </label> <span><input type="text" name="devices_name"/></span></p>
                     <p><label>设备: </label> <span><input type="text" name="devices_type"/></span></p>
                     <p><label>设备版本:</label><span><input type="text" name="devices_version"/></span></p>
                     <div class="clear"></div>                              
                     <p><label>devices_token:</label><span><input type="text" name="devices_token"/></span></p>				 <div class="clear"></div>                     
                      <p><input type="submit" class="send_btn" value="Send" onclick=""/></p>
                       <p>回馈信息：<br><span style="float:none; color:#F00;" class="add"></span></p>
                            </form>
                        </li>
                   </ul>
                </span>
               </div>
              <?php
        		break;
			case 'del':
			    echo "del";
        		break;
			case 'update':
			    echo "update";
        		break;
		   default:  
        ?> 
        <div class="container">
            <h2>IOS消息推送:可推送用户为<?php echo $no_of_users; ?>个</h2>
            <hr/>
            <h2>全部发送推送消息</h2>
            <ul class="devices">
                <?php
                if ($no_of_users > 0) {?>
                 <li>
                <form id="all" method="post" onsubmit="return sendPushall()">  
                 <p><label>消息内容:</label>                   
                <textarea rows="3" name="message" cols="105" class="txt_message" placeholder="Type message here"></textarea><p>
              <p><label>发送模式：</label><select name="mode" ><option value="Development">开发测试</option><option value="Production">正式发送</option></select></p>
               <p><input type="submit" class="send_btn" value="Send" onclick=""/></p>
                 </form>    
                </li>
                <li>
                <h3>回馈信息：</h3>
                <div class="info">
                </div>
                </li>
                    <?php } else { ?> 
                    <li>
                        No Users Registered Yet!
                    </li>
                <?php } ?>
            </ul>
            <hr/>
            <h2>单独发送推送消息</h2>
              <p>请选择相应的用户，点对点发送推送消息。</p>
            <hr/>
             <ul class="devices">
                <?php
                if ($no_of_users > 0) {
                    ?>
                    <?php
                    while ($row = mysql_fetch_array($users)) {
                        $row['id'] = $row['deviceid'];
                        ?>
                        <li>
                            <form id="<?php echo $row["id"] ?>" name="" method="post" onsubmit="return sendPushNotification('<?php echo $row["id"] ?>')">
                             	<p><label>编号: </label> <span><?php echo $row["id"] ?></span></p>
                                <p><label>姓名: </label> <span><?php echo $row["devices_name"] ?></span></p>
                                <p><label>邮箱: </label> <span><?php echo $row["email"] ?></span></p>
                                 
                                <p><label>设备: </label> <span><?php echo $row["devices_type"] ?></span></p>
                                <p><label>设备版本:</label><span><?php echo $row["devices_version"] ?></span></p>
                                 
                                <p><label>已发送数:</label><span><?php echo $row["badge_number"] ?></span></p>
                                <p><label>是否活跃:</label><span><?php if($row["status"] == 1)echo('是');else echo('否'); ?></span></p>
                                <div class="clear"></div>                              
                                 <p><label>devices_token:</label> <span><?php echo $row["devices_token"] ?></span></p>				           <div class="clear"></div>
                                 <p><label>消息内容:</label>                   
                                    <textarea rows="3" name="message" cols="55" class="txt_message" placeholder="Type message here"></textarea><p>
                                    <p><label>发送模式：</label><select name="mode" ><option value="Development">开发测试</option><option value="Production">正式发送</option></select></p>
                                    <input type="hidden" name="id" value="<?php echo $row["id"] ?>"/>                                 
                                  <p><input type="submit" class="send_btn" value="Send" onclick=""/></p>
                                 <p>回馈信息：<br><span style="float:none; color:#F00;" class="id<?php echo $row["id"] ?>"</span></p>
                            </form>
                        </li>
                    <?php }
                } else { ?> 
                    <li>
                        No Users Registered Yet!
                    </li>
                <?php } ?>
            </ul>
           
        </div>
            <?php }  ?> 
           <style type="text/css">
		    
			 .head,
            .container{
                width: 950px;
                margin: 0 auto;
                padding: 0;
            }
			
			 .head span a{
				 color:#F00;
				 font-size:16px;
				 border:1px #FF0 solid;
				 
				 
				 
			 }
			
            h1{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 24px;
                color:#900;
            }
			h2{
				font-size: 16px;
				color:#00F;
				
			}
            div.clear{
                clear: both;
            }
            ul.devices{
                margin: 0;
                padding: 0;
            }
            ul.devices li{                
                list-style: none;
                border: 1px solid #dedede;
                padding: 10px;
                margin: 0 15px 25px 0;
                border-radius: 3px;
                -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                -moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: #555;
            }
            ul.devices li label, ul.devices li span{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: bold;
                color: #393939;
                display: block;
                float: left;
            }
			ul.devices li span{
				margin-right:10px;
				font-weight:300;
			}
				
			ul.devices li p{
				
				display:block;
				margin-right:10px;
			}
            ul.devices li label{
				color:#06F;
				margin-right:10px;
			            
            }
            ul.devices li textarea{
                
                resize: none;
            }
            ul.devices li .send_btn{
                background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -webkit-linear-gradient(0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -moz-linear-gradient(center top, #0096FF, #005DFF);
                background: linear-gradient(#0096FF, #005DFF);
                text-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
                border-radius: 3px;
                color: #fff;
            }
			
			 b{
				 color:#F00;
			 }
			 .info{ 
				 color:#F00;
			 }
        </style>
    </body>
</html>
