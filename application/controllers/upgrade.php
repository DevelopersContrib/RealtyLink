<?php

class Upgrade extends CI_Controller {
	//sandbox
	private $apiUsername = 'kjcast_1334370520_biz_api1.gmail.com';
	private $apiPassword = '1334370545';
	private $apiSignature = 'AiPC9BjkCyDFQXbSkoZcgqH3hpacA-zy0RMkCrgmgi2.pBo86NbXQIPz';
	private $live = true;
	
	//live
	// private $apiUsername = 'chad_api1.ecorp.com';
	// private $apiPassword = 'MG567ZV3LDH7KBE8';
	// private $apiSignature = 'Ap01ymKisjPO3XYMjrU7IqDSHv5xAEdyX4QlGZsGXS6r.7JAl4kh1bXQ';
	// private $live = true;
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','codegenerator_helper', 'curl_helper', 'cookie','notification_helper'));
	    $this->load->library('session');
	    $this->load->library('email');
	    $this->load->library('ContribClient');
	    $this->load->library('VnocClient');
		$this->load->library('Paypal');
	    $this->load->library('cryptoapi');
	    $this->load->model('membersdata');
		$this->load->model('memberpaymentsdata');
		$this->load->model('projectsdata');
		$this->load->model('projecttasksdata');
		$this->load->model('cryptocontribdata');
		$this->load->model('memberwalletdata');
		$this->load->model('tokentransactions');
		$this->load->model('onboardingtasks');
		$this->load->model('memberplandata');
		$this->load->model('historydata');
		$this->load->model('vnocdata');
		
	    $this->load->database();
	}
	
	public function test()
	{
		$theo = $this->vnocdata->GetInfo('total','domain_theoretical_value','domain_id',11188);
		$esh_val = round($theo/$this->config->item('ESH_VALUE'),2);
		$plan_amount = $this->config->item('plan_amount');
		$onboarding_token = $plan_amount / $esh_val;
		var_dump($onboarding_token);
		var_dump((float)number_format($onboarding_token, 2, '.', ''));
	}
	

	public function index(){
		if ($this->session->userdata('logged_in')){
			$data['title'] = "Servicechain.com - Upgrade";
			$userid = $this->session->userdata('userid');
			$data['userid'] = $userid;
			$user_type = $this->membersdata->getinfo('user_type','id',$userid);
			$data['user_type'] = $user_type;
			$data['username'] = $this->session->userdata('username');
			$data['firstname'] = $this->membersdata->getinfo('firstname','id',$userid);
			$data['lastname'] = $this->membersdata->getinfo('lastname','id',$userid);
			$data['upgrade_plan'] = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',5));
			$data['upgrade_plan_service_provider'] = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',6));
			//$data['upgrade_plan'] = false;
			//if($user_type == 'homeowner'){
				$this->load->view('upgrade/index',$data);
			//}else{
				//redirect("/");
			//}
		}else{
			redirect("/");
			exit;
		}
	}
		
	public function purchase() {
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$data['userid'] = $userid;
			$user_type = $this->membersdata->getinfo('user_type','id',$userid);
			$data['user_type'] = $user_type;
			//$data = $this->input->post('data');
			//$newTokenAmount = $this->input->post('newTokenAmount');
			//$newPlanAmount = $this->input->post('newPlanAmount');
			//$coupon = $this->input->post('coupon');
			
			$cookieData = [
				'userid'=>$userid
			];

			$encodedCookieData = json_encode($cookieData);
			
			
			$coupon = '';
			$newTokenAmount = $this->config->item('onboarding_token');
			if($user_type=='homeowner'){
				$newPlanAmount = $this->config->item('plan_amount');
			}else{
				$newPlanAmount = $this->config->item('plan_amount_service_provider');
			}
			
			$cookieData = array(
				'name'=>'servicechain_mdetails',
				'value'=>$encodedCookieData,
				'expire'=>86400,
				'domain'=>'.servicechain.com',
				'path'=>'/'
			);

			if(!empty($coupon) && !empty($newTokenAmount)) {
				$couponData = [
					'coupon'=>$coupon,
					'newTokenAmount'=>$newTokenAmount,
				];

				$encodedCouponData = json_encode($couponData);

				$cookieCouponData = array(
					'name'=>'coupon_discount_details',
					'value'=>$encodedCouponData,
					'expire'=>86400,
					'domain'=>'.servicechain.com',
					'path'=>'/'
				);

				$this->input->set_cookie($cookieCouponData);
			}
			

			$this->input->set_cookie($cookieData);
			
			if(!empty($newPlanAmount)) {
				$totalAmount = $newPlanAmount;
			} else {
				$totalAmount = $this->config->item('plan_amount');
			}

			$paymentInfo['Order']['theTotal'] = $totalAmount;
			$paymentInfo['Order']['description'] = 'Purchase Servicechain PREMIUM Plan for $'.$totalAmount;
			$paymentInfo['Order']['quantity'] = '1';

			$Paypal = new Paypal($this->apiUsername,$this->apiPassword,$this->apiSignature,$this->live);
			
			$result = $Paypal->SetExpressCheckout($paymentInfo);
			
			if(!$Paypal->isCallSucceeded($result)){ 
				if($Paypal->apiLive === true){
					//Live mode basic error message
					$returnData['message'] = 'We were unable to process your request. Please try again later';
					$returnData['status'] = FALSE;
				}else{
					//Sandbox output the actual error message to dive in.
					$returnData['message'] = $result['L_LONGMESSAGE0'];
					$returnData['status'] = FALSE;
				}
			} else { 
				// send user to paypal 
				$token = urldecode($result["TOKEN"]); 
				
				$returnData['payPalURL'] = $Paypal->paypalUrl.$token; 
				$returnData['status'] = TRUE;
			}

			// $this->output
				// ->set_content_type('application/json')
					// ->set_output(json_encode($returnData));
					
			header('Location: '.$returnData['payPalURL']);
		}
	}
	
	public function payment() {
		//if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$data['userid'] = $userid;
			$memberId = $userid;
			$token = $this->input->get('token');
			$payerId = $this->input->get('PayerID');

			$Paypal = new Paypal($this->apiUsername,$this->apiPassword,$this->apiSignature,$this->live);
			$result = $Paypal->GetExpressCheckoutDetails($token);	
			
			$mData = get_cookie('servicechain_mdetails');
			if(!empty($mData) || $mData !== FALSE) {
				$cdata = json_decode($mData);
				$userid = $cdata->userid;
				$memberId = $userid;
			}
			$user_type = $this->membersdata->getinfo('user_type','id',$userid);
			if($result['ACK'] === "Success") {
				if($user_type=='homeowner'){
					$amnt = $this->config->item('plan_amount');
				}else{
					$amnt = $this->config->item('plan_amount_service_provider');
				}
				
				$paymentDetails = [
					'TOKEN'=> $token,
					'PAYERID' => $payerId, 
					'ORDERTOTAL' => $amnt
				];
				
				$paymentResult = $Paypal->DoExpressCheckoutPayment($paymentDetails);

				if($paymentResult['ACK'] === "Success") {
					
					$newTokenAmount = NULL;

					if(is_null(get_cookie('coupon_discount_details')) === FALSE) {
						$couponData = json_decode(get_cookie('coupon_discount_details'));

						// $couponId = $this->couponsdata->getinfo('id','code',$couponData->coupon);

						// if($couponId !== '0' || $couponId !== 0 || !empty($couponId)) {
							// $this->couponsdata->update($couponId,['is_used'=>1]);
						// }

						// $newTokenAmount = $couponData->newTokenAmount;
					}

					if(!empty($mData) || $mData !== FALSE) {
						//$memberId = $this->savemember($mData);

						if($memberId != 0) {
							$this->deletecookiedata();

							$memberPaymentData = [
								'member_id'=>$memberId,
								'transaction_id'=>$paymentResult['TRANSACTIONID'],
								'token'=>$paymentResult['TOKEN'],
								'amount'=>$paymentResult['AMT'],
								'currency'=>$paymentResult['CURRENCYCODE'],
								'status'=>$paymentResult['PAYMENTSTATUS'],
								'firstname'=>$this->membersdata->getinfo('username','id',$memberId),
								'email'=>$result['EMAIL']
							];

							$paymentId = $this->memberpaymentsdata->update(0,$memberPaymentData);

							$memberPlanData = [
								'member_id'=>$memberId,
								'amount'=>$amnt,
								'expiry_date'=>date('Y-m-d H:i:s', strtotime("+1 YEAR", strtotime('now')))
							];

							$memberPlanId = $this->memberplandata->update(0,$memberPlanData);

							
							
							$memberEmail = $this->membersdata->getinfo('email','id',$userid);
							$memberUsername = $this->membersdata->getinfo('username','id',$userid);
							
							if($user_type=='homeowner'){
								$onboarding_task_id = 5;
							}else{
								$onboarding_task_id = 6;
							}
							
							//send SCESH to home owner for first project
							if(empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',$onboarding_task_id))){
								if($this->memberwalletdata->checkexist('member_id',$userid) == TRUE) {
									$memberWalletData = $this->memberwalletdata->getbyattribute('member_id',$userid);
									
									//$network = 'main';
									$network = $this->config->item('network');
									
									$theo = $this->vnocdata->GetInfo('total','domain_theoretical_value','domain_id',
										$this->config->item('servicechain_domainid'));
									$esh_val = round($theo/$this->config->item('ESH_VALUE'),2);
									
									$plan_amount = $amnt;
									
									$onboarding_token = $plan_amount / $esh_val;
									$onboarding_token =(float)number_format($onboarding_token, 2, '.', '');
									
									$contractor_esh = $this->cryptoapi->sendservicechainesh($memberWalletData->row()->member_id,
										$memberWalletData->row()->wallet_address,$onboarding_token,$network,
										'Onboarding task upgrade to PREMIUM Account. Has received '.$onboarding_token.' SCESH Tokens',$onboarding_task_id);
										
									// if($contractor_esh['transaction_id']) {
										// $tokenAmountSent = $newTokenAmount !== NULL ? $newTokenAmount:$this->config->item('token_amount');
										// $transData = [
											// 'member_id'=>$memberId,
											// 'trans_id'=>$contractor_esh['transaction_id'],
											// 'token_amount'=>$tokenAmountSent,
											// 'notes'=>'Free '.$tokenAmountSent.' tokens for PREMIUM Account',
											// 'contribution_key'=>'obt'.time(),
											// 'domain_id'=>$this->config->item('servicechain_domainid')
										// ];
										// $this->tokentransactions->update(0, $transData);
									// }

									if(!empty($cdata->referrer_id) || isset($cdata->referrer_id)) {
										$referrerWalletInfo = $this->memberwalletdata->getbyattribute('member_id',$cdata->referrer_id);
										
										$onboardingTask = $this->onboardingtasks->getbyattribute('id',$onboarding_task_id);

										$this->cryptoapi->adddaocontributions($cdata->referrer_id,$onboardingTask->row()->id,
											$onboardingTask->row()->token_amount,$onboardingTask->row()->title);
									}
									
									//createuser history
									$h_message = 'has upgraded to PREMIUM Account';
									$h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/update/');
									$this->historydata->update(0,$h_array);
								}
							}
							
							header('Location: '.base_url().'upgrade?m=success&email='.$memberEmail.'&username='.$memberUsername);
						}
					} else {
						$this->output
							->set_content_type('application/json')
								->set_output(json_encode(['status'=>FALSE,'message'=>'Upgrade failed.']));
						header('Location: '.base_url().'upgrade?m=failed');
					}
				} else {
					header('Location: '.base_url().'upgrade?m='.$paymentResult['L_SHORTMESSAGE0']);
				}
			}
		//}
	}
	
	public function deletecookiedata() {
		$cookieSignDetails = 'cowork_mdetails';
		$cookieDomain = '.cowork.com';
		$cookiePath = '/';

		$cookieCoupon = 'coupon_discount_details';
		
		delete_cookie($cookieSignDetails, $cookieDomain, $cookiePath);
		delete_cookie($cookieCoupon, $cookieDomain, $cookiePath);
	}
	
}