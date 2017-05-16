 <?php
         require_once('apns.class.php');
         require_once('db.class.php');
         $db = new DB();

        
        if(isset($_REQUEST['mode']) && $_REQUEST['mode'] != ""){
        	$mode = $_REQUEST['mode'];
        }else{
        	$mode = 'Development';
        }
        $apns = new APNS($db,$mode);
        
        switch ($_REQUEST['action']){
        	case 'registerDevices':
				$args = array();
        		$args['devices_token'] = $_REQUEST['devices_token'];
				$args['devices_name'] = $_REQUEST['devices_name'];
				$args['devices_version'] = $_REQUEST['devices_version'];
				$args['devices_type'] = $_REQUEST['devices_type'];
				//$args['badge_number'] = $_REQUEST['badge_number'];
				$args['mode'] = $mode;
          		//unset($args['action']);
            	$apns->registerDrevice($args);
        		break;
        	case 'pushMessageToALL':
        		$apns->createSSLConnect();
           		$message = $_REQUEST['message'];
            	$apns->sendALLMessage($message);
            	$apns->closeSSLConnect();
            	echo "推送消息发布完毕！";
        		break;
		   case 'pushMessageToone':
        		$apns->createSSLConnect();
           		$message = $_REQUEST['message'];
				$id = $_REQUEST['id'];
            	$apns->pushMessageToone($message,$id);
            	$apns->closeSSLConnect();
            	echo "推送消息发布完毕！";
        		break;
        	case 'cleanBadgeNumber':
        		$apns->cleanBadgeNumberById($_REQUEST['id'], $_REQUEST['badge']);
        		break;
        }
        
  
 ?>