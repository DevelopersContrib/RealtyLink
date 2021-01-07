<?php

class Onboarding extends CI_Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','codegenerator_helper'));
	    $this->load->library('session');
	    $this->load->library('email');
		$this->load->library('cryptoapi');
	    $this->load->library('ContribClient');
	    $this->load->library('VnocClient');
	    $this->load->model('membersdata');
		$this->load->model('projectsdata');
		$this->load->model('projecttasksdata');
		$this->load->model('onboardingtasks');
		$this->load->model('tokentransactions');
		$this->load->model('memberwalletdata');
		$this->load->model('vnocdata');
		$this->load->model('cryptocontribdata');
		
		$this->db_referrals = $this->load->database('referrals', TRUE);
		
	    $this->load->database();
	}

	public function index(){
		if ($this->session->userdata('logged_in')){
			$data['title'] = "Servicechain.com - Onboarding";
			$userid = $this->session->userdata('userid');
			$data['userid'] = $userid;
			$user_type = $this->membersdata->getinfo('user_type','id',$userid);
			$data['user_type'] = $user_type;
			$data['username'] = $this->session->userdata('username');
			$username = $data['username'];
			$data['firstname'] = $this->membersdata->getinfo('firstname','id',$userid);
			$data['lastname'] = $this->membersdata->getinfo('lastname','id',$userid);
			$email = $this->membersdata->getinfo('email','id',$userid);
			
			$testnet = $this->membersdata->getinfo('testnet','id',$userid);
			$mainnet = $this->membersdata->getinfo('mainnet','id',$userid);
			
			$upgrade_plan = false;
			$referral_program = false;
			$count_projects = 0;
			$count_join_projects = 0;
			$domain_id = $this->config->item('servicechain_domainid');
			
			$memberWalletData = $this->memberwalletdata->getbyattribute('member_id',$userid);
			$wallet_address = $memberWalletData->row()->wallet_address;
				
			if(empty($testnet)){// add member to testnet
				$owner_address = $this->config->item('c_account_owner_test');
				$passphrase = $this->config->item('c_account_password_test');
				$data_address = $this->config->item('c_data_address_test');

				if($this->cryptoapi->ismember($domain_id,$wallet_address,$data_address,$this->config->item('c_account_node_test'))===false){
					$tx = $this->cryptoapi->addmemberwithtx($owner_address,$data_address,$passphrase,$wallet_address,$domain_id,$username);
				}else{
					$member_data = [
						'testnet' => 1,
					];
					$member_id = $this->membersdata->update($userid,$member_data);
				}
			}
			
			if(empty($mainnet)){// add member to mainnet
				$data_address = $this->vnocdata->GetInfo('address','domain_crytpo_account','domain_id',$domain_id,'ctype_id',5);
				$passphrase = $this->vnocdata->GetInfo('password','domain_crytpo_account','domain_id',$domain_id,'ctype_id',5);
				$owner_address = $this->vnocdata->GetInfo('owner_address','domain_crytpo_account','domain_id',$domain_id,'ctype_id',5);				
				
				if($this->cryptoapi->ismember($domain_id,$wallet_address,$data_address,$this->config->item('c_account_node'))===false){
					$tx = $this->cryptoapi->addmemberwithtx($owner_address,$data_address,$passphrase,$wallet_address,$domain_id,$username);
				}else{
					$member_data = [
						'mainnet' => 1,
					];
					$member_id = $this->membersdata->update($userid,$member_data);
				}
			}
			
			if($user_type == 'homeowner'){
				$onboardingtasks = $this->onboardingtasks->getbyattribute('user_type','homeowner');
				
				$referral_program = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',1));
				$upgrade_plan = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',5));
				$has_projects = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',3));
				
				if(!$referral_program){// check referral_program db if exist
					$sql = "SELECT * FROM `campaign_participants` WHERE `email` = '$email' AND `campaign_id` = '".$this->config->item('referral_widget_id')."'";
					$query_referral = $this->db_referrals->query($sql);
					if ($query_referral->num_rows() > 0){
						$referral_row = $query_referral->row();
						$participant_id = $referral_row->id;
						
						$sql_invite = "SELECT participant_id FROM `participants_invited_emails`  WHERE participant_id = '$participant_id'
							UNION SELECT participant_id FROM `participants_share`  WHERE participant_id = '$participant_id'";
							
						$query_invite = $this->db_referrals->query($sql_invite);
						if ($query_invite->num_rows() > 0){
							//send SCESH for referral
							if(empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',1))){
								if($this->memberwalletdata->checkexist('member_id',$userid) == TRUE) {
									$memberWalletData = $this->memberwalletdata->getbyattribute('member_id',$userid);
									
									$network = $this->config->item('network');
									$onboarding_token =$this->config->item('onboarding_token');
									
									$referral_esh = $this->cryptoapi->sendservicechainesh($memberWalletData->row()->member_id,
										$memberWalletData->row()->wallet_address,$onboarding_token,$network,
										'Onboarding task Join Referral Program. Has received '.$onboarding_token.' SCESH Tokens',1);
										
									$referral_program = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',1));
								}
							}
						}
					}
				}
				
				$onboarding_array = [];
				$done = 0;
				if ($onboardingtasks->num_rows() > 0){
					foreach ($onboardingtasks->result_array() as $row){
						$row['done'] = false;
						if($row['id'] == 1){
							$row['done'] = !empty($referral_program);
						}else if($row['id'] == 5){
							$row['done'] = !empty($upgrade_plan);
						}else if($row['id'] == 3){
							$row['done'] = !empty($has_projects);
						}
						if($row['done']) $done++;
						$onboarding_array[] = $row;
					}
				}
				$data['onboardingtasks'] = $onboarding_array;
				if($onboardingtasks->num_rows()==$done){
					redirect("/project-owner/dashboard");
				}else{
					$this->load->view('onboarding/index',$data);
				}
			}else{
				$onboardingtasks = $this->onboardingtasks->getbyattribute('user_type','contractor');
				$referral_program = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',2));
				$join_projects = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',4));
				$upgrade_plan = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',6));
				
				if(!$referral_program){// check referral_program db if exist
					$sql = "SELECT * FROM `campaign_participants` WHERE `email` = '$email' AND `campaign_id` = '".$this->config->item('referral_widget_id')."'";
					$query_referral = $this->db_referrals->query($sql);
					if ($query_referral->num_rows() > 0){
						$referral_row = $query_referral->row();
						$participant_id = $referral_row->id;
						
						$sql_invite = "SELECT participant_id FROM `participants_invited_emails`  WHERE participant_id = '$participant_id'
							UNION SELECT participant_id FROM `participants_share`  WHERE participant_id = '$participant_id'";
							
						$query_invite = $this->db_referrals->query($sql_invite);
						if ($query_invite->num_rows() > 0){
							//send SCESH for referral
							if(empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',2))){
								if($this->memberwalletdata->checkexist('member_id',$userid) == TRUE) {
									$memberWalletData = $this->memberwalletdata->getbyattribute('member_id',$userid);
									
									$network = $this->config->item('network');
									$onboarding_token =$this->config->item('onboarding_token');
									
									$referral_esh = $this->cryptoapi->sendservicechainesh($memberWalletData->row()->member_id,
										$memberWalletData->row()->wallet_address,$onboarding_token,$network,
										'Onboarding task Join Referral Program. Has received '.$onboarding_token.' SCESH Tokens',2);
										
									$referral_program = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',2));
								}
							}
						}
					}
				}
				
				$onboarding_array = [];
				$done = 0;
				if ($onboardingtasks->num_rows() > 0){
					foreach ($onboardingtasks->result_array() as $row){
						$row['done'] = false;
						if($row['id'] == 2){
							$row['done'] = !empty($referral_program);
						}else if($row['id'] == 4){
							$row['done'] = !empty($join_projects);
						}else if($row['id'] == 6){
							$row['done'] = !empty($upgrade_plan);
						}
						if($row['done']) $done++;
						$onboarding_array[] = $row;
					}
				}
				$data['onboardingtasks'] = $onboarding_array;
				if($onboardingtasks->num_rows()==$done){
					redirect("/dashboard");
				}else{
					$this->load->view('onboarding/index',$data);
				}
			}
		}else{
			redirect("/");
			exit;
		}
	}
	
	
}