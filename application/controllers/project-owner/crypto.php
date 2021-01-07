<?

class Crypto extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('cryptoapi');
        $this->load->model('membersdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('projectcontractdata');
        $this->load->model('taskapplicationsdata');
        $this->load->model('mintransdata');
		$this->load->model('memberwalletdata');
		$this->load->model('vnocdata');
		$this->load->model('cryptocontribdata');
		$this->load->model('tokentransactions');
        $this->load->database();
    }
    
    public function createApiCall($url, $method, $headers, $data = array(),$user=null,$pass=null)
    {
        if (($method == 'PUT') || ($method=='DELETE'))
        {
            $headers[] = 'X-HTTP-Method-Override: '.$method;
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
                /* curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
                 curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'POST');
                 curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                 curl_setopt($handle, CURLOPT_POSTREDIR, 3); */
                curl_setopt($handle, CURLOPT_TIMEOUT, 0);
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
    
    public function ajaxfetchaddress(){
        $transaction = trim($this->input->post('hash'));
        $network = trim($this->input->post('network'));
        $address = '';
        $status = false;
        if ($network == 'test'){
            $url = $this->config->item('c_account_node_test').'getTransactionReceipt?tx='.$transaction;
        }else {
            $url = $this->config->item('c_account_node').'getTransactionReceipt?tx='.$transaction;
        }
        
        $call =  $this->createApiCall($url, 'GET', array('Accept: application/json'),[]);
        $callr = json_decode($call,true);
        if (isset($callr['contractAddress'])){
            $address = $callr['contractAddress'];
            $status = true;
        }else {
            if (isset($callr['blockNumber'])){
                $status = true;
            }
        }
		$url = '';
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('status'=>$status,'address'=>$address,'url'=>$url)));
    }
    
    public function godeploy()
    {
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$upgrade_plan = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',5));
			$status = false;
			$address='';
			$hash='';
			$message = '';
			$network = $this->input->post('c_data_network');
			if ($network == ""){
				$network = $this->input->post('c_dan_network');
			}
			if ($network == 'test'){
				$url = $this->config->item('c_account_node_test').'godeploy';
				$passphrase = $this->config->item('c_account_password_test');
				$account = $this->config->item('c_account_owner_test');
			}else {
				if($upgrade_plan){ // make sure user is premium accnt
					$url = $this->config->item('c_account_node').'godeploy';
					$passphrase = $this->config->item('c_account_password');
					$account = $this->config->item('c_account_owner');
				}else{
					$url = $this->config->item('c_account_node_test').'godeploy';
					$passphrase = $this->config->item('c_account_password_test');
					$account = $this->config->item('c_account_owner_test');
				}
			}
			$sourcecode = $this->input->post('sourcecode');
			$contract = $this->input->post('contract');
			$params = $this->input->post('param');
			$balance = $this->cryptoapi->getethbalance($account,$network);
			
			$param = array(
				'sourcecode'=>$sourcecode,
				'contract'=>$contract,
				'param'=>$params,
				'account'=>$account,
				'passphrase'=>$passphrase,
				'gp'=>$this->cryptoapi->getGweiPrice('fast')
			);
			
			if ($balance > 0.5){
				$result = $this->createApiCall($url,'POST',array('Accept: application/json'),$param);
				//var_dump($result);
				$resk = json_decode($result,true);
				if (isset($resk['Hash'])){
					$hash = $resk['Hash'];
					$status = true;
				}
			}else {
				$message = 'Owner '.$account.' should atleast have 0.5 ETH balance to create contract. ';
				$message .= 'Current balance is '.$balance;
			}
			
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status'=>$status,'Hash'=>$hash,'Address'=>$address,'message'=>$message,'network'=>$network)));
		}
    }
    
    
    
    public function autosavecontract(){
		if ($this->session->userdata('logged_in')){
			$project_id = $this->db->escape_str($this->input->post('project_id'));
			$address = trim($this->db->escape_str($this->input->post('address')));
			$type = $this->db->escape_str($this->input->post('ctype'));
			$network = $this->db->escape_str($this->input->post('network'));
			$hash = $this->db->escape_str($this->input->post('hash'));
			$added="";
			
			if (($address != '') && ($address != null)){
				if ($this->projectcontractdata->checkexist('project_id',$project_id,'contract_type',$type)===false){
					
					
					$cdata = array('project_id'=>$project_id,'address'=>$address,'network'=>$network,'contract_type'=>$type,'trans_id'=>$hash);
					$this->projectcontractdata->update(0,$cdata);
					$added="";
					if ($type=='dan'){
						$added = $this->cryptoapi->adddaoasmember($project_id);
					}
					
				}
			}
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status'=>true,'added'=>$added)));
		}
    }
    
    
    public function updatemint(){
		if ($this->session->userdata('logged_in')){
			$id = $this->db->escape_str($this->input->post('id'));
			$t_array = array('status'=>'done');
			$this->mintransdata->update($id,$t_array);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status'=>true)));
		}
    }
    
    private function fetchaddress($transaction,$network='test'){
        if ($network == 'test'){
            $url = $this->config->item('c_account_node_test').'getTransactionReceipt?tx='.$transaction;
        }else {
            $url = $this->config->item('c_account_node').'getTransactionReceipt?tx='.$transaction;
        }
        
        $call =  $this->createApiCall($url, 'GET', array('Accept: application/json'),[]);
        $callr = json_decode($call,true);
        return $callr;
    }
    
    
    public function minteshdao(){
		if ($this->session->userdata('logged_in')){
			$status = false;
			$blocknum = '';
			$message = '';
			$project_id = $this->input->post('project_id');
			$esh_address = "";
			$hash = "";
			$mint_id = "";
			$userid = $this->session->userdata('userid');
			$owner_esh = [];
			
			if ($this->mintransdata->checkexist('project_id',$project_id,'status','pending')===false){
			
				$sql = "SELECT * FROM `project_contracts` WHERE project_id='$project_id' AND contract_type='dan'";
				$query = $this->db->query($sql);
				if ($query->num_rows() > 0){
					foreach ($query->result() as $row)
					{
						$network = $row->network;
						$dan_address = $row->address;
					}
				}	 
				
				if ($network == 'test'){
					$esh_address = $this->config->item('c_account_esh_test');
					$owner_address = $this->config->item('c_account_owner_test');
					$passphrase = $this->config->item('c_account_password_test');
				}else {
					$esh_address = $this->config->item('c_account_esh');
					$owner_address = $this->config->item('c_account_owner');
					$passphrase = $this->config->item('c_account_password');
				}
				
				
				if ($esh_address != ''){
					$balance = $this->cryptoapi->getethbalance($owner_address,$network);
					if ($balance > .05){
						$transaction = $this->cryptoapi->mintESH($project_id,$this->config->item('c_esh_amount'));
						if ($transaction) {
							$t_array = array('userid'=>$userid,
								'project_id'=>$project_id,
								'trans_id'=>$transaction,
								'notes'=>'mint',
								'amount'=>$this->config->item('c_esh_amount'),
								'dan_address'=>$dan_address,
								'network'=>$network
							);
							
						   $mint_id =  $this->mintransdata->update(0,$t_array);
							
							$hash = $transaction;
							$status = true;
						}
						
						//send SCESH to home owner for first project
						if(empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',3))){
							if($this->memberwalletdata->checkexist('member_id',$userid) == TRUE) {
								$memberWalletData = $this->memberwalletdata->getbyattribute('member_id',$userid);
								
								if($memberWalletData->num_rows() > 0) {
									$owner_esh = $this->cryptoapi->sendservicechainesh($memberWalletData->row()->member_id,
										$memberWalletData->row()->wallet_address,$this->config->item('onboarding_token'),$network,
										'Onboarding task Add your first project for '.$this->config->item('onboarding_token').' SCESH Tokens',3);
								}
							}
						}
					}else {
						$message = 'Owner '.$owner_address.' should atleast have 0.05 ETH balance to send ESH. ';
						$message .= 'Current balance is '.$balance;
					}
				}else {
					$message .= 'Project has no ESH token';
				}
			
			}else {
				$message .= 'A pending mint transaction still on process';
			}
			//$status = true;
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status'=>$status,'Hash'=>$hash,'message'=>$message,'network'=>$network,'mint_id'=>$mint_id,'owner_esh'=>$owner_esh)));
		}
    }
	
	public function sendcoworkesh() {
		if ($this->session->userdata('logged_in')){
			$userId = $this->session->userdata('userid');
			$userEmail = $this->membersdata->getinfobyid('email',$userId);

			if($this->memberwalletdata->checkexist('member_id',$userId) == TRUE) {
				$memberWalletData = $this->memberwalletdata->getbyattribute('member_id',$userId);
				
				if($memberWalletData->num_rows() > 0) {
					$result = $this->cryptoapi->sendcoworkesh($memberWalletData->row()->member_id,$memberWalletData->row()->wallet_address);

					echo '<pre>';
					var_dump($result);
					echo '</pre>';
					exit;
				}
			} else {
				$cryptoMemberId = $this->cryptocontribdata->GetInfo('member_id','members','email',$userEmail);
				$memberWallet = $this->cryptocontribdata->GetInfo('account_address','members_ether','member_id',$cryptoMemberId);
		
				if(!empty($memberWallet)) {
					$walletAddressData = [
						'member_id' => $userId,
						'wallet_address' => $memberWallet,
						'password' => generatecode()
					];
		
					$memberWalletId = $this->memberwalletdata->update(0,$walletAddressData);
					$memberWalletData = $this->memberwalletdata->getbyattribute('id',$memberWalletId);
					
					if($memberWalletData->num_rows() > 0) {
						$result = $this->cryptoapi->sendcoworkesh($memberWalletData->row()->member_id,$memberWalletData->row()->wallet_address);
		
						echo '<pre>';
						var_dump($result);
						echo '</pre>';
						exit;
					}
				}
			}
		}
	}
    
}