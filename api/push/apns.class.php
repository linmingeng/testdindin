 <?php 
        class APNS
        {
           private $ctx;
           private $ssl_connect;
           private $push_body;
           private $ssl_sandbox_url;
           public $certificate; 
           private $db;
           public $mode;
		   public $result;
           
           public function __construct($db,$mode)
           {
           		require_once 'config.inc.php';
                $this->db = $db;
                $this->mode = $mode;
                if($this->mode == 'Development'){
                	$this->certificate = DevelopmentCer;
                	$this->ssl_sandbox_url = DevelopmentSSL;
                }else{
                	$this->certificate = ProductionCer;
                	$this->ssl_sandbox_url = ProductionSSL;
                }
           }
           
         
           public function registerDrevice($args)
           {
             if($args != null && $args != array()){
                $this->db->table = data_tab;
                $token = $this->formatToken($args['devices_token']) ;
                $email = str_replace("'", "\”", $args['devices_name']);
                $args['email'] = $email;
                $mode = $args['mode'];
                $args['devices_token'] = 1;
                $args['devices_token'] = $token;
                $args['badge_number'] = 0;
                $args['update_time'] = time();
                $args['status'] = 1;
                $args['addtime'] = time();
                unset($args['devices_name']);
                $this->mode = $mode;
                $res = $this->db->select(array('where'=>"email like '$email' and devices_token like '$token' and mode like '$this->mode'"));
               if($res['row'] == 0){
                   $res = $this->db->insert(array('data'=>$args)); 
				   echo "添加完成";
               }else{
                  $this->db->update(array('filed'=>array('key'=>'update_time','value'=> time()),'where'=>"email like '$email' and devices_token like '$token' and mode like '$this->mode'"));
               		$this->cleanBadgeNumberByToken($token, 0);
					echo "设备存在";
               }
            }
           }
		   public function formatToken($token)
		   {
		  	 	return str_replace('<','',str_replace('>', '', str_replace(' ', '', $token)));
		   }
           public function createSSLConnect()
           {
			  require_once 'config.inc.php';
			  $this->passphrase = passphrase;
              $this->ctx = stream_context_create();			
              stream_context_set_option($this->ctx, 'ssl', 'local_cert', $this->certificate); 
			  stream_context_set_option($this->ctx, 'ssl', 'passphrase', $this->passphrase);
              $this->ssl_connect = stream_socket_client($this->ssl_sandbox_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $this->ctx);
			if (!$this->ssl_connect){
					exit("Failed to connect: $err $errstr" . PHP_EOL);}
          }

           public function closeSSLConnect()
           {
                fclose($this->ssl_connect);
           }
           public function cleanBadgeNumberByToken($token,$badge)
           {
        		$this->db->table = data_tab;
        		$where = 'devices_token like "'.$this->formatToken($token).'" and mode like "'.$this->mode.'"';
        		$res = $this->db->update(array('filed'=>array('key'=>'badge_number','value'=>$badge),'where'=>$where));
        		return ;
           }
           public function cleanBadgeNumberById($id,$badge)
           {
           	  	$this->db->table = data_tab;
        		$where = 'deviceid ='.$id;
        		$res = $this->db->update(array('filed'=>array('key'=>'badge_number','value'=>$badge),'where'=>$where));
        		return ;
           }
           public function sendALLMessage($message,$badge=0,$sound='received5.caf')
           {
            if(mb_strlen($message,'utf8') > 32){
            	$message = mb_substr($message,0,31,'utf-8')."...";
            }
              $this->db->table = data_tab;
              $res = $this->db->select(array('filed'=>array('deviceid','devices_token','badge_number'),'where'=>'mode like "'.$this->mode.'" and status = 1'));
              $this->push_body['aps'] = array('alert'=>$message,'badge'=>$badge,'sound'=>$sound); 
              $res['row'] == 0;//不可群发
              
              if($res['row'] != 0){
                foreach ($res['data'] as $value) {
                	echo $value->devices_token."<br/>";
  	               $this->push_body['aps']['badge'] = $value->badge_number + 1;  
				   echo  $this->push_body['aps']['badge'];            
  	               $this->push_body['aps']['id'] = $value->id;
  	          	   $res =  $this->db->update(array('filed'=>array('key'=>'badge_number','value'=>$this->push_body['aps']['badge']),'where'=>'mode like "'.$this->mode.'" and status = 1 and deviceid ='.$value->id.''));
                   $this->newMessage($value->devices_token);
				  
                }
				
              }
           }
		    public function pushMessageToone($message,$id,$badge=0,$sound='received5.caf')
           {
            if(mb_strlen($message,'utf8') > 32){
            	$message = mb_substr($message,0,31,'utf-8')."...";
            }
              $this->db->table = data_tab;
              if(isset($_REQUEST['email'])){
                $res = $this->db->select(array('filed'=>array('deviceid','devices_token','badge_number'),'where'=>'mode like "'.$this->mode.'" and status = 1 and email like "'.$_REQUEST['email'].'" '));
              }else{
                $res = $this->db->select(array('filed'=>array('deviceid','devices_token','badge_number'),'where'=>'mode like "'.$this->mode.'" and status = 1 and deviceid ='.$id.''));
              }
              $this->push_body['aps'] = array('alert'=>$message,'badge'=>$badge,'sound'=>$sound); 
              if($res['row'] != 0){
                foreach ($res['data'] as $value) {
                	//echo $value->devices_token."<br/>";
  	               //$this->push_body['aps']['badge'] = $value->badge_number + 1;  	               
  	               $this->push_body['aps']['badge'] = 1;  	               
  	               $this->push_body['aps']['id'] = $value->deviceid;
  	          	    $res =  $this->db->update(array('filed'=>array('key'=>'badge_number','value'=>$this->push_body['aps']['badge']),'where'=>'mode like "'.$this->mode.'" and status = 1 and deviceid ='.$value->deviceid.''));
                   $this->newMessage($value->devices_token);
				  
                }
				
              }
           }
           public function newMessage($dreviceToken,$push_body = null)
           {
             if($push_body != null) $this->push_body = $push_body;
             $jsonBody = json_encode($this->push_body);
             $message = chr(0) . pack("n",32) . pack('H*', $dreviceToken) . pack("n",strlen($jsonBody)) . $jsonBody; 
             $this->pushMessage($message);
           }


           public function pushMessage($message)
           {
               $this->result  =  fwrite($this->ssl_connect, $message);
			  // fwrite($this->ssl_connect, $message);			    

				  if ($this->result !== false) {
				 		echo '<font color=green>发送成功。请关闭本窗口。</font><br>';					 
				 	 } else {				  
				 		echo '<font color=red>发送失败。请关闭本窗口。</font><br>';
				 		var_dump($apns->errno(), $apns->errmsg());   
				 	}  
			}

		}

    ?>