<?php 
class CryptoApi
{
	private $api_url = null;
	private $api_url_test = null;
    
	public function __construct($token=null)
    {
        $this->ci = &get_instance();
        $this->ci->load->database();
        $this->api_url = $this->ci->config->item('node_url');
        $this->api_url_test = $this->ci->config->item('node_url_test');
		
		$this->api_url = $this->ci->config->item('node_url');
        $this->db_vnoc = $this->ci->load->database('vnoc', TRUE);
        $this->db_crypto = $this->ci->load->database('crypto', TRUE);
        $this->domain_id = $this->ci->config->item('servicechain_domainid');
        $this->domain_name = 'servicechain.com';
        $this->esh_amount = $this->ci->config->item('token_amount');
    }
    
    
	public static function createApiCall($url, $method, $headers, $data = array(),$user=null,$pass=null)
    {
        if ($method == 'PUT')
        {
            $headers[] = 'X-HTTP-Method-Override: PUT';
        }

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        if ($user){
         curl_setopt($handle, CURLOPT_USERPWD, $user.':'.$pass);
        } 

        switch($method)
        {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'PUT':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'DELETE':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        $response = curl_exec($handle);
        return $response;
    }
    
    
   public function getethvalue(){
	   	$headers = array('Accept: application/json');
	   	$url = 'https://crypto.contrib.com/api/GetConversion';
	   	$result =  $this->createApiCall($url, 'GET', $headers, array());
	   	$res = json_decode($result,true);
	   	$array = $res['data']['rates'];
	   	return $array['ETH'];
   } 
   
   public function getnetworkurl($address){
   	  $network = 1;
   	  $sql = "SELECT network FROM `project_contracts` WHERE address= '$address'";
   	   $query = $this->ci->db->query($sql);
    	if ($query->num_rows() > 0){
			foreach ($query->result() as $row)
			 {
			 	$network = $row->network;
			 }
    	}	 
    	
    	if ($network == 'main'){
    		$url = $this->api_url;
    	}else {
    		$url = $this->api_url_test;
    	}
    	return $url;
   }
   
   public function getsellprice($tokenaddress){
   	$headers = array('Accept: application/json');
   	$price = 0;
   	$url = $this->getnetworkurl($tokenaddress)."/getSellPrice?address=".$tokenaddress;
	$result =  $this->createApiCall($url, 'GET', $headers, array());
	$res = json_decode($result,true);
	if (isset($res['sellPrice'])){	
		$price = $res['sellPrice'] ;
	}
	
	return $price;
   } 
   
   
   public function setprice($contract_address,$usd_price,$account,$passphrase){
   	  	$headers = array('Accept: application/json');
   	  	$buy_price= $usd_price * $this->getethvalue();
   	  	$buy_price = round($buy_price,8);
   	  	$url = $this->getnetworkurl($contract_address)."/setPrices";
   	  	$sell_price = $this->getsellprice($contract_address);
   	  	$param = array('token'=>$contract_address,'sellprice'=>$buy_price,'buyprice'=>$buy_price,'account'=>$account,'passphrase'=>$passphrase,'gp'=>$this->getGweiPrice('fast'));
		$result =  $this->createApiCall($url, 'POST', $headers, $param);
		$res = json_decode($result,true);	
		if (isset($res['txHash'])){
			return true;
		}else {
			return false;
		}
				
   }
   
    public function createwallet($password){
   	    $address = '';
   	    $headers = array('Accept: application/json');
   	    $url = $this->api_url.'/createAccount?passphrase='.$password;
		$result =  $this->createApiCall($url, 'GET', $headers, array());
		$res = json_decode($result,true);
		if (($res['address'] != null) && ($res['address'] !='')) {
			$address = 	$res['address'];
		}
		return $address;
   }
   
   public function getGweiPrice($param = 'fast'){
      
       $headers = array('Accept: application/json');
       $gwei = 1;
       $url = 'https://ethgasstation.info/json/ethgasAPI.json';
       $result =  $this->createApiCall($url, 'GET', $headers, array());
       $res = json_decode($result,true);
       
       if (isset($res[$param])){
           $gwei = round($res[$param]/10);
       }
       return $gwei;
   }
   
   public function getethbalance($wallet_address,$network){
       $headers = array('Accept: application/json');
       $eth = 0;
       if ($network == 'test'){
           $url = $this->ci->config->item('c_account_node_test').'getAccntEthBalance?address='.$wallet_address;
       }else {
           $url = $this->ci->config->item('c_account_node').'getAccntEthBalance?address='.$wallet_address;
       }
       
       $result =  $this->createApiCall($url, 'GET', $headers, array());
       $res = json_decode($result,true);
       if (isset($res['balance'])){
           $eth = $res['balance'];
       }
       
       return $eth;
   }
   
   public function ismember($project_id,$member_address,$data_address,$url=''){
       $headers = array('Accept: application/json');
       $found = false;
	   
	   if(!empty($url)){
		$url = $url.'isMemberV2';
	   }else{
		$url = $this->getnetworkurl($data_address).'/isMemberV2';
	   }
       $param = array('member'=>$member_address,'address'=>$data_address,'gp'=>$this->getGweiPrice('fast'));
       $result =  $this->createApiCall($url, 'POST', $headers, $param);
       $res = json_decode($result,true);
	   
       if (isset($res['result'])){
           if ($res['result']){
               $found = true;
           }
       }
       return $found;
   }
   
   
   public function addmember($owner_address,$data_address,$passphrase,$wallet_address,$project_id,$member_id){
       $headers = array('Accept: application/json');
       $url = $this->getnetworkurl($data_address).'/addMemberV2';
       $user_name = $this->ci->membersdata->getinfobyid('username',$member_id);
       if ($user_name != ""){
           $param = array('tokenaddress'=>$data_address,'account'=>$owner_address,'passphrase'=>$passphrase,'memberaddress'=>$wallet_address,'membername'=>$user_name,'role'=>'Contributor');
           $result =  $this->createApiCall($url, 'POST', $headers, $param);
           $res = json_decode($result,true);
           if (isset($res['txHash'])){
               return true;
           }else {
               return false;
           }
       }else {
           return false;
       }
   }   
   
   public function adddaoasmember($project_id){
       $added = false;
       $headers = array('Accept: application/json');
       $dan_address = "";
       $data_address = "";
       $network = "";
       
       $sql = "SELECT * FROM `project_contracts` WHERE `project_id` = '$project_id'  AND  `contract_type` = 'dan'";
       $query = $this->ci->db->query($sql);
       if ($query->num_rows() > 0){
           foreach ($query->result() as $row){
               $dan_address = $row->address;
           }
       }
       
       $sql = "SELECT * FROM `project_contracts` WHERE `project_id` = '$project_id'  AND  `contract_type` = 'data'";
       $query = $this->ci->db->query($sql);
       if ($query->num_rows() > 0){
           foreach ($query->result() as $row){
               $data_address = $row->address;
               $network = $row->network;
               if ($network=='test'){
                   $owner_address = $this->ci->config->item('c_account_owner_test');
                   $passphrase = $this->ci->config->item('c_account_password_test');
               }else {
                   $owner_address = $this->ci->config->item('c_account_owner');
                   $passphrase = $this->ci->config->item('c_account_password');
               }
               
           }
       }
       
       if (($dan_address !="") && ($data_address!="")){
           if ($network == 'test'){
               $url = $this->ci->config->item('c_account_node_test').'addMemberV2';
           }else {
               $url = $this->ci->config->item('c_account_node').'addMemberV2';
           }
           
           
           if ($this->ismember($project_id, $dan_address, $data_address)===false){
               $param = array('tokenaddress'=>$data_address,'account'=>$owner_address,'passphrase'=>$passphrase,'memberaddress'=>$dan_address,'membername'=>'Dan Address','role'=>'dan contract','gp'=>$this->getGweiPrice('fast'));
               $result =  $this->createApiCall($url, 'POST', $headers, $param);
               $res = json_decode($result,true);
               if (isset($res['txHash'])){
                   $added = true;
               }
           }else {
               $added = true;
           }
       }
       
       
       return $added;
   }
   
   public function mintESH($project_id,$amount){
       $transaction = false;
       $headers = array('Accept: application/json');
       $dan_address = "";
       
       $sql = "SELECT * FROM `project_contracts` WHERE project_id='$project_id' AND contract_type='dan'";
       $query = $this->ci->db->query($sql);
       if ($query->num_rows() > 0){
           foreach ($query->result() as $row)
           {
               $network = $row->network;
               $dan_address = $row->address;
           }
       }
       
       if ($network == 'test'){
           $esh_address = $this->ci->config->item('c_account_esh_test');
           $owner_address = $this->ci->config->item('c_account_owner_test');
           $passphrase = $this->ci->config->item('c_account_password_test');
       }else {
           $esh_address = $this->ci->config->item('c_account_esh');
           $owner_address = $this->ci->config->item('c_account_owner');
           $passphrase = $this->ci->config->item('c_account_password');
       }
       
       
        if (($esh_address != '') && ($dan_address != ''))  {
           $url = $this->getnetworkurl($dan_address).'/mint';
           $amount = number_format($amount, 18, '', '');
           $param = array('tokenaddress'=>$esh_address,'account'=>$owner_address,'passphrase'=>$passphrase,'tokenvalue'=>$amount,'targetaddress'=>$dan_address,'gp'=>$this->getGweiPrice('fast'));
           $result = $this->createApiCall($url, 'POST', $headers, $param);
           $res = json_decode($result,true);
           if (isset($res['txHash'])){
               $transaction = $res['txHash'];
           }
           
       }
       return $transaction;
   }
   
   public function geteshbalance($project_id){
       $balance = 0;
       $network = "test";
       $esh_token = "";
       $esh_address = ""; 
       $dan_address = "";
       $headers = array('Accept: application/json');
       $sql = "SELECT * FROM `project_contracts` WHERE `project_id` = '$project_id'  AND  `contract_type` = 'dan'";
       $query = $this->ci->db->query($sql);
       if ($query->num_rows() > 0){
           foreach ($query->result() as $row){
               $dan_address = $row->address;
               $network = $row->network;
           }
       }
       
       if ($network == 'test'){
           $esh_address = $this->ci->config->item('c_account_esh_test');
       }else {
           $esh_address = $this->ci->config->item('c_account_esh_main');
       }
       
       if (($esh_address != '') && ($dan_address != ''))  {
           $url2 = $this->getnetworkurl($dan_address).'/getBalanceOf?token='.$esh_address.'&account='.$dan_address;
           $result2 =  $this->createApiCall($url2, 'GET', $headers, array());
           $res2 = json_decode($result2,true);
           $balance =  $res2['balance18'];
       }
       
       return $balance;
       
   }
   
   public function addcontribution($owner_address,$contract_address,$passphrase,$token_amount,$from_address,$description='',$tokenreward,$key){
       $headers = array('Accept: application/json');
       $url =  $this->getnetworkurl($contract_address).'/newContributionV2';
       $param = array('tokenaddress'=>$contract_address,'account'=>$owner_address,'passphrase'=>$passphrase,'beneficiary'=>$from_address,'ethamount'=>$token_amount,'description'=>$description,'tokenreward'=>$tokenreward,'key'=>$key,'gp'=>$this->getGweiPrice('fast'));
       $result =  $this->createApiCall($url, 'POST', $headers, $param);
       $res = json_decode($result,true);
       if (isset($res['txHash'])){
           return $res;
       }else {
           return false;
       }
   }
   
   
   public function addcashcontribution($owner_address,$data_address,$passphrase,$token_amount,$from_address,$description='',$tokenreward=0,$key){
       $headers = array('Accept: application/json');
       $url =  $this->getnetworkurl($data_address).'/setV2';
       $param = array('tokenaddress'=>$data_address,'account'=>$owner_address,'passphrase'=>$passphrase,'beneficiary'=>$from_address,'ethamount'=>$token_amount,'description'=>$description,'tokenreward'=>$tokenreward,'key'=>$key,'gp'=>$this->getGweiPrice('fast'));
       $result =  $this->createApiCall($url, 'POST', $headers, $param);
       $res = json_decode($result,true);
       if (isset($res['txHash'])){
           return $res;
       }else {
           return false;
       }
   }
   
   
   public function executecontribution($dan_address,$contract_owner,$contract_password,$key){
      
       $headers = array('Accept: application/json');
       $url = $this->getnetworkurl($dan_address).'/executeContributionV2';
       $param = array('tokenaddress'=>$dan_address,'account'=>$contract_owner,'passphrase'=>$contract_password,'key'=>$key,'gp'=>$this->getGweiPrice('fast'));
       $result =  $this->createApiCall($url, 'POST', $headers, $param);
       $res = json_decode($result,true);
       return $res;
   }
   
   public function executedatacontribution($data_address,$contract_owner,$contract_password,$key){
       
       $headers = array('Accept: application/json');
       $url = $this->getnetworkurl($data_address).'/executeContribDataV2';
       $param = array('tokenaddress'=>$data_address,'account'=>$contract_owner,'passphrase'=>$contract_password,'key'=>$key,'gp'=>$this->getGweiPrice('fast'));
       $result =  $this->createApiCall($url, 'POST', $headers, $param);
       $res = json_decode($result,true);
       return $res;
   }
   
   public function gettranscost($transaction,$network){
       $headers = array('Accept: application/json');
       $cost = 0;
       if ($network == 'test'){
           $url = $this->ci->config->item('c_account_node_test').'getTransactionDetails?tx='.$transaction;
       }else {
           $url = $this->ci->config->item('c_account_node').'getTransactionDetails?tx='.$transaction;
       }
       
       $result =  $this->createApiCall($url, 'GET', $headers, array());
       $res = json_decode($result,true);
       if (isset($res['cost'])){
           $cost = $res['cost'];
       }
       
       return $cost;
   }
   
   public function sendEshEmail($email,$name,$subject,$message){
        require $this->ci->config->item('sendgrid_path');
        $data = array('name' => ucwords($name),'message' => $message);
        $msg = $this->ci->load->view('project-owner/email_templates/generic_template',$data,true);
        $html_content = wordwrap($msg);
        $from = new SendGrid\Email("Contrib Admin", "admin@contrib.com");
        $to = new SendGrid\Email($name, $email);
        $reply_to = new SendGrid\Email('Contrib Admin', "support@contrib.com");
        $content = new SendGrid\Content("text/html", $html_content);
        $mail = new SendGrid\Mail($from, $subject, $to,  $content);
        $mail->setReplyTo($reply_to);
        $sg = new \SendGrid($this->ci->config->item('sendgrid_key'));
        $response = $sg->client->mail()->send()->post($mail);
		return $response;
    }
	
	private function sendCryptoEmail($email,$name,$password,$wallet_address){
        require $this->ci->config->item('sendgrid_path');
        $subject =  "Crypto Contrib Account Details";
        $data = array('name' => $name,'email' => $email,'password'=>$password,'wallet_address'=>$wallet_address);
        $msg = $this->ci->load->view('home/esh_crypto_email',$data,true);
        $html_content = wordwrap($msg);
        $from = new SendGrid\Email("Contrib Admin", "admin@contrib.com");
        $to = new SendGrid\Email($name, $email);
        $reply_to = new SendGrid\Email('Contrib Admin', "support@contrib.com");
        $content = new SendGrid\Content("text/html", $html_content);
        $mail = new SendGrid\Mail($from, $subject, $to,  $content);
        $mail->setReplyTo($reply_to);
        $sg = new \SendGrid($this->ci->config->item('sendgrid_key'));
        $response = $sg->client->mail()->send()->post($mail);
    }
	
	public function sendservicechainesh($member_id,$wallet_address,$new_token_amt,$network,$notes,$onboarding_task_id){
        $exist_crypto = false;
        $domain_id = $this->domain_id;
        $data_address = "";
        $esh_address = "";
        $message = "";
        $is_member = false;
        $status = false;
        $purchase_id = '';
        $transaction_id = '';

        if($new_token_amt !== NULL || !empty($new_token_amt)) {
            $this->esh_amount = $new_token_amt;
        }
        
        $email = $this->ci->membersdata->getinfobyid('email',$member_id);
        $password = $this->ci->membersdata->getinfobyid('password',$member_id);
        $user_name = $this->ci->membersdata->getinfobyid('username',$member_id);
    
        if ($this->ci->vnocdata->CheckFieldExists('members','email',$email)===false){
            $result =  $this->createApiCall('http://api1.contrib.co/crypto/AddMember', 'POST', array('Accept: application/json'),['email'=>$email,'domain_id'=>$domain_id,'name'=>$user_name]);
            sleep(3);
        }
        
        $vnoc_member_id = $this->ci->vnocdata->GetInfo('member_id','members','email',$email);
        
        if($this->ci->cryptocontribdata->CheckFieldExists('members','email',$email)){
            $exist_crypto = true;
        }
    
        if ($exist_crypto===false){
            $mem_data =array(
                'email'=>$email,
                'name'=>ucwords($user_name),
                'password'=>$password,
                'name_slug'=>strtolower(trim(preg_replace('/[^a-zA-Z0-9\-]/', '-', $user_name),'\-')),
                'email_verified'=>1,
                'is_verified'=>1
            );
            $crypto_member_id = $this->ci->cryptocontribdata->update('members','member_id',0,$mem_data);
        } else {
            $crypto_member_id = $this->ci->cryptocontribdata->GetInfo('member_id','members','email',$email);
        }
        
        if ($exist_crypto===false){
            $this->ci->cryptocontribdata->update('members_ether','id',0,array('member_id'=>$crypto_member_id,'account_address'=>$wallet_address));
            $this->sendCryptoEmail($email,$user_name,$password,$wallet_address);
        }
        
        $info = $this->getdaoinfo($domain_id);
        
        if (isset($info['contract_address'])){
            $dao_address = $info['contract_address'];
            $data_address =  $info['data_address'];
            $owner_address = $info['account'];
            $passphrase = $info['passphrase'];
        }
    
        if (isset($info['esh_address'])){
            $esh_address = $info['esh_address'];
        }
		
		if ($network == 'test'){
			$esh_address = $this->ci->config->item('c_account_esh_test');
			$owner_address = $this->ci->config->item('c_account_owner_test');
			$passphrase = $this->ci->config->item('c_account_password_test');
			
			$dao_address = $this->ci->config->item('c_dan_address_test');
			$data_address = $this->ci->config->item('c_data_address_test');
		}
    
        if (($data_address != "") && ($esh_address != "")){
            if($this->ismember($domain_id,$wallet_address,$data_address)===false){
                $member= array();
                    
                if ($this->ci->cryptocontribdata->CheckFieldExists('addmember_transactions','member_id',$crypto_member_id,'domain_id',$domain_id,'status','pending')===false){
                    $tx = $this->addmemberwithtx($owner_address,$data_address,$passphrase,$wallet_address,$domain_id,$user_name);

                    if ($tx === false){
                        $message = 'Something went wrong while adding member to dao contract';
                    } else {
                        $i = 0;
                        $done = $this->transactiondone($tx,$data_address);

                        while ($done===false){
                            $done = $this->transactiondone($tx,$data_address);
                            if ($i==10){
                                break;
                            }
                            $i=$i+1;
                        }
                        
                        if ($this->transactiondone($tx,$data_address)===false){
                            $transaction_id = $tx;
                            $at_array = array('member_id'=>$crypto_member_id,'domain_id'=>$domain_id,'transaction_id'=>$tx,'domain'=>$this->domain_name);
                            $this->ci->cryptocontribdata->update('addmember_transactions','id',0,$at_array);
                            $p_array = array('member_id'=>$crypto_member_id,'amount'=>$this->esh_amount,'domain_id'=>$domain_id,'note'=>$notes,'currency'=>'SCESH','purchase_type_id'=>7,'from_vnoc'=>1,'from_equity_id'=>1);
                            $purchase_id = $this->ci->cryptocontribdata->update('purchase_token_transactions','id',0,$p_array);
                            $message = "Sorry it takes time to process distribute esh this time. Transaction has been saved and will be processed later.";
                        } else {
                            $is_member = true;
                        }
                    }
                } else {
                    $message = "A transaction under this user still pending. Please wait and try again later.";
                }
            } else {
                $is_member= true;
            }

            if ($is_member){
                $at_id = $this->ci->cryptocontribdata->GetInfo('id','addmember_transactions','member_id',$crypto_member_id,'domain_id',$domain_id);
                $at = array('status'=>'done');

                if ($at_id != ''){
                    $this->ci->cryptocontribdata->update('addmember_transactions','id',$at_id,$at);
                }
                
                if($this->ci->cryptocontribdata->CheckFieldExists('purchase_token_transactions','member_id',$crypto_member_id,'domain_id',$domain_id,'cron_status','pending')===false) {
                    $p_array = array('member_id'=>$crypto_member_id,'amount'=>$this->esh_amount,'domain_id'=>$domain_id,
						'note'=>$notes,'currency'=>'SCESH','purchase_type_id'=>7,
						'from_vnoc'=>1,'from_equity_id'=>1,'domain'=>$this->domain_name);
                    $purchase_id = $this->ci->cryptocontribdata->update('purchase_token_transactions','id',0,$p_array);
                    $key = 'ts'.$purchase_id;
                    $transaction_id = $this->executeauto($key,$dao_address,$owner_address,$passphrase,$wallet_address,$this->esh_amount,'distribute esh',$esh_address);

                    if ($transaction_id != ""){
                        $p_array = array('trans_id'=>$transaction_id,'contribution_key'=>$key,'cron_status'=>'done');
                        $this->ci->cryptocontribdata->update('purchase_token_transactions','id',$purchase_id,$p_array);

                        $this->ci->tokentransactions->update(0,['member_id'=>$member_id,'trans_id'=>$transaction_id,
						'token_amount'=>$this->esh_amount,'notes'=>$notes,'contribution_key'=>'obt'.time(),
						'domain_id'=>$domain_id,
						'onboarding_task_id'=>$onboarding_task_id]);
						
                        $status = true;
                    } else {
                        $message = 'Something went wrong while adding contribution to dan contract';
                    }
                } else {
                    $message = 'A pending transaction already exists. Please wait and try again later.';
                }
            } 
        }
		$email_response = '';
        if($message == '') {
            $esh_subject = 'Crypto Contrib ESH Distribution';
            $esh_message = 'Congratulations! You have just been given SCESH in your wallet. Included here are details of your membership with '.$this->domain_name.'.<br>';
            $esh_message .= 'Included also are your accounts for VNOC and Crypto.contrib.com.<br>';
            $esh_message .= 'You can now login to VNOC and Crypto using the same access info.<br>';
            
            $vnoc_member_id = $this->ci->vnocdata->GetInfo('member_id','members','email',$email);
            $datac =  array(
                'c_type_id'=>2,
                'domain_id'=>$domain_id,
                'member_id'=>$vnoc_member_id,
                'amount'=>$this->esh_amount,
                'description'=>'Has received SCESH tokens from '.$this->domain_name,
                'in_blockchain'=>1,
                'blockchain_transaction'=>$transaction_id,
                'link'=>'https://etherscan.io/tx/'.$transaction_id,
                'currency'=>'SCESH'
            );
            
            $this->ci->vnocdata->update('domain_contributions','c_id',0,$datac);
            $email_response = $this->sendEshEmail($email,$user_name,$esh_subject,$esh_message);
        }

        return ['status'=>$status,'esh_address'=>$esh_address,'data_address'=>$data_address,'message'=>$message,
			'purchase_id'=>$purchase_id,'transaction_id'=>$transaction_id,'wallet_address'=>$wallet_address,
			'email_response'=>$email_response];
    }


     public function getdaotokenbalance($domain_id){
       // $domain_id = $this->domain_id;
        $headers = array('Accept: application/json');
        $ctb_token = "";
        $esh_token = "";
        $contrib_esh_token = "";
        $dao_address = "";
        $token="";
        $balance = array();
        $balance['esh'] = 0;
        $balance['ctb'] = 0;
        $balance['token'] = 0;
        $balance['contribesh'] = 0;
        
        
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 1 AND domain_crytpo_account.`network_id` = 2 LIMIT 1";
        $query = $this->db_vnoc->query($sql);
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $ctb_token = trim($row->address);
            }
        }
        
        
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 6 AND domain_crytpo_account.`network_id` = 2 LIMIT 1";
        $query = $this->db_vnoc->query($sql);
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $token = trim($row->address);
            }
        }
        
        
        
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 2 AND domain_crytpo_account.`network_id` = 2 LIMIT 1";
        $query = $this->db_vnoc->query($sql);
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $esh_token = trim($row->address);
            }
        }
       
        
        
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 3  ORDER BY domain_crytpo_account.`id` DESC LIMIT 1";
        $query = $this->db_vnoc->query($sql);
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $dao_address = trim($row->address);
            }
        }
        
        
        if (($ctb_token!="") && ($dao_address !="")){
            $url = $this->api_url.'/getBalanceOf?token='.$ctb_token.'&account='.$dao_address;
            $result =  $this->createApiCall($url, 'GET', $headers, array());
            $res = json_decode($result,true);
            $balance['ctb']= $res['balance'];
            
        }
        
        
        if (($esh_token!="") && ($dao_address !="")){
            $url2 = $this->api_url.'/getBalanceOf?token='.$esh_token.'&account='.$dao_address;
            $result2 =  $this->createApiCall($url2, 'GET', $headers, array());
            $res2 = json_decode($result2,true);
            $balance['esh']= $res2['balance18'];
        }
        
        
        if (($contrib_esh_token!="") && ($dao_address !="")){
            $url2 = $this->api_url.'/getBalanceOf?token='.$contrib_esh_token.'&account='.$dao_address;
            $result2 =  $this->createApiCall($url2, 'GET', $headers, array());
            $res2 = json_decode($result2,true);
            $balance['contribesh']= $res2['balance18'];
            
            
        }
        
        if (($token!="") && ($dao_address !="")){
            $url3 = $this->api_url.'/getBalanceOf?token='.$token.'&account='.$dao_address;
            $result3 =  $this->createApiCall($url3, 'GET', $headers, array());
            $res3 = json_decode($result3,true);
            if (isset($res3['balance18'])){
                $balance['token']= $res3['balance18'];
            }
        }
        
        return $balance;
        
    }

    public function gettokenbalance(){
      $balance = $this->getdaotokenbalance($this->domain_id);
      return $balance['esh'];
    }
    
    public function gettokensold(){
      $supply = 1000000;
      $balance = $this->getdaotokenbalance($this->domain_id);
      return $supply-$balance['esh'];
    }

	public function addmemberwithtx($owner_address,$data_address,$passphrase,$memberaddress,$domain_id,$user_name){
        $headers = array('Accept: application/json');
		
        //$url = $this->api_url.'/addMemberV2';
		$url = $this->getnetworkurl($data_address).'/addMemberV2';
        $role = 'Contributor';
        
        if ($role != ""){
            
            $param = array('tokenaddress'=>$data_address,'account'=>$owner_address,'passphrase'=>$passphrase,'memberaddress'=>$memberaddress,'membername'=>$user_name,'role'=>$role,'gp'=>$this->getGweiPrice('fast'));
            //var_dump($param);
			//var_dump($url);
			$result =  $this->createApiCall($url, 'POST', $headers, $param);
            $res = json_decode($result,true);
            if (isset($res['txHash'])){
                return $res['txHash'];
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function transactiondone($transaction,$contract_address){
        $done = false;
		$url = $this->getnetworkurl($contract_address).'/getTransactionReceipt?tx='.$transaction;
        //$url = $this->api_url.'/getTransactionReceipt?tx='.$transaction;
        $call =  $this->createApiCall($url, 'GET', array('Accept: application/json'),[]);
        $callr = json_decode($call,true);
        if (isset($callr['blockNumber'])){
            $done = true;
        }
        return $done;
    }
	
	public function getdaoinfo($domain_id){
        $info = array();
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
                WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 3 AND domain_crytpo_account.`network_id` = 2 ORDER BY domain_crytpo_account.`id` DESC LIMIT 1";

        $query = $this->db_vnoc->query($sql);

        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $info['contract_address'] = $row->address;
                $info['account'] = $row->owner_address;
                $info['passphrase'] = $row->password;
            }
        }
        
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
                WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 1 AND domain_crytpo_account.`network_id` = 2 LIMIT 1";

        $query = $this->db_vnoc->query($sql);

        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $info['ctb_address'] = $row->address;
            }
        }
        
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
                WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 2 AND domain_crytpo_account.`network_id` = 2 LIMIT 1";

        $query = $this->db_vnoc->query($sql);

        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $info['esh_address'] = $row->address;
            }
        }
        
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
                WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 5 AND domain_crytpo_account.`network_id` = 2 LIMIT 1";

        $query = $this->db_vnoc->query($sql);
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $info['data_address'] = $row->address;
            }
        }
        
        $sql = "SELECT `domain_crytpo_account`.* FROM `domain_crytpo_account` INNER JOIN `domain_crytpo_conversion` ON (`domain_crytpo_conversion`.`domain_id` = `domain_crytpo_account`.`domain_id` )
                WHERE domain_crytpo_account.`domain_id` = '$domain_id' AND `ctype_id` = 6 AND domain_crytpo_account.`network_id` = 2 LIMIT 1";

        $query = $this->db_vnoc->query($sql);

        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $info['token_address'] = $row->address;
            }
        }
        
        return $info;
    }
	
	public function executeauto($key,$dao_address,$owner_address,$passphrase,$wallet_address,$amount,$description,$tokenreward){
        $transaction_id = "";
        $headers = array('Accept: application/json');
		$url = $this->getnetworkurl($dao_address).'/createexecuteV2';
        //$url = $this->api_url.'/createexecuteV2';
        $amount = number_format($amount, 18, '', '');
        $param = array('key'=>$key,'tokenaddress'=>$dao_address,'account'=>$owner_address,'passphrase'=>$passphrase,'beneficiary'=>$wallet_address,'ethamount'=>$amount,'description'=>$description,'tokenreward'=>$tokenreward,'gp'=>$this->getGweiPrice('fast'));
        $result =  $this->createApiCall($url, 'POST', $headers, $param);
        $res = json_decode($result,true);
        if (isset($res['txHash'])){
            $transaction_id = 	$res['txHash'];
        }
        return $transaction_id;
        
    }
}