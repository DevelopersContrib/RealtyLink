<?

class Signup extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('ContribClient');
        $this->load->library('VnocClient');
        $this->load->library('HandymanClient');
        $this->load->library('cryptoapi');
        $this->load->library('libphonenumber');
        $this->load->model('membersdata');
        $this->load->model('memberwalletdata');
        $this->load->model('statesdata');
        $this->load->model('countrydata');
        $this->load->model('cryptocontribdata');
        $this->load->model('memberplandata');
        $this->load->database();
    }
    
    public function index()
    {
        if ($this->session->userdata('logged_in')){
            //redirect("/dashboard");
			redirect("/onboarding");
        }else{
            
            $contrib_api_key = '364b4d6516bf4907';
            $vnoc_api_key = 'a088239f8263dc8f';
            $handyman_api_key = 'a088239f8263dc8f';
            
            
            $contribclient = new ContribClient($contrib_api_key);
            $vnocclient = new VnocClient($vnoc_api_key);
            $handymanclient = new HandymanClient($handyman_api_key);
            
            $data['login_contrib'] = $contribclient->LoginUrl($this->config->item('main_url').'/project-owner/social/contrib',$this->config->item('main_url'));
            $data['login_vnoc'] = $vnocclient->LoginUrl($this->config->item('main_url').'/project-owner/social/vnoc',$this->config->item('main_url'));
            $data['login_handyman'] = $handymanclient->LoginUrl($this->config->item('main_url').'/project-owner/social/handyman',$this->config->item('main_url'),'homeowner');
            $data['title'] = "Servicechain.com - Signup";
            $data['countries'] = $this->countrydata->getall();
            $this->load->view('project-owner/home/signup',$data);
        }
        
    }
    
    public function savemember(){
        $firstname = $this->db->escape_str($this->input->post('firstname'));
        $lastname = $this->db->escape_str($this->input->post('lastname'));
        $username = $this->db->escape_str($this->input->post('username'));
        $email = $this->db->escape_str($this->input->post('email'));
        $password = $this->db->escape_str($this->input->post('password'));
        $country = $this->db->escape_str($this->input->post('country'));
        $phone = $this->db->escape_str($this->input->post('phone'));
        $field = '';

        $countryCode = $this->countrydata->getinfobyid('code',$country);
        $phone_code = $this->countrydata->getinfobyid('phone_code',$country);


        $is_number_valid = $this->validatesms($countryCode,$phone,$phone_code);

        $status = FALSE;
        $msg = "";
        //$url = "/project-owner/dashboard";
		$url = "/onboarding";
        $from_crypto = 0;
        $wallet_address = "";
        
        if($this->membersdata->checkexist('username',$username)) {
            $msg = "The username you entered is already taken.";
            $field = 'username';
        } elseif($this->membersdata->checkexist('email',$email)) {
            $msg = "The email address you entered is already taken.";
            $field = 'email';
        }else if($is_number_valid == 'Invalid Number'){
            $msg = "The phone number you entered is invalid";
            $field = 'number';
        }else {
            
            $member_data = [
                'firstname'=>$firstname,
                'lastname'=>$lastname,
                'username' => $username,
                'email' => $email,
                'password' => md5($password),
                'country_id'=>$country,
                'phone_number'=>$is_number_valid,
                'user_type'=>'homeowner',
                'code'=>'xxx',
                'is_admin' => 0,
                'signup_from'=>'servicechain'
            ];
            
            $member_id = $this->membersdata->update(0,$member_data);
            $code = $this->membersdata->generatecode(8,$member_id);
            
            $member_data = array('code'=>$code);
            $this->membersdata->update($member_id,$member_data);
            
            
            
            if ($this->cryptocontribdata->CheckFieldExists('members','email',$email)===true){
                $crypto_member_id = $this->cryptocontribdata->GetInfo('member_id','members','email',$email);
                $wallet_address = $this->cryptocontribdata->GetInfo('account_address','members_ether','member_id',$crypto_member_id);
                if ($wallet_address != ""){
                    $from_crypto = 1;
                }
            }
            
            if ($wallet_address == ""){
                $wallet_address = $this->cryptoapi->createwallet($code);
            }
            
            $wallet_data = array('member_id'=>$member_id,'wallet_address'=>$wallet_address,'from_crypto'=>$from_crypto);
            
            $this->memberwalletdata->update(0,$wallet_data);
            
            $message = "<p>We are so happy to have you as part of Servicechain.com. </p>";
            if ($from_crypto==1){
                $message .= "<p>";
                $message .= 'You are using your crypto.contrib.com wallet address:  '.$wallet_address.' for servicechain transactions.';
                $message .= "</p>";
            }else {
                $message .= "<p>";
                $message .= 'Your wallet address is:  '.$wallet_address;
                $message .= "</p>";
                $message .= "<p>";
                $message .= 'Using password:  '.$code;
                $message .= "</p>";
            }
            
            $message .= "<p>Please confirm your email address to prevent potential misuse by third parties.</p>";
            
            $data = [
                'message'=>$message,
                'name' => $firstname,
                'link' => $this->config->item('main_url')."/project-owner/signup/confirm?code=".$code
            ];
            
            $message = $this->load->view('project-owner/email_templates/signup_email_template_new',$data,TRUE);
            
            $sendgrid_data = [
                'subject' => "Welcome to Servicechain - Confirm your email",
                'message' => $message,
                'admin_name' => "Servicechain",
                'admin_email' => "support@servicechain.com",
                'recipient' => $firstname.' '.$lastname,
                'recipient_email' => $email,
            ];
            
            $response = $this->sendEmail($sendgrid_data);
            //var_dump($response);
            $status = true;
            
        }
     
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status'=>$status,'message'=>$msg,'url'=>$url,'email'=>$email,'field'=>$field]));
    }
    
    
    public function resendcode(){
        $email = $this->db->escape_str($this->input->post('email'));
        $message = "";
        $email_message = "";
        
        if($this->membersdata->checkexist('email',$email)) {
            $activated = $this->membersdata->getinfo('is_activated','email',$email);
            if ($activated == 0){
                $query = $this->membersdata->getbyattribute('email',$email);
                $member_id = $query->row()->id;
                $firstname = $query->row()->firstname;
                $lastname = $query->row()->lastname;
                $code = $this->membersdata->generatecode(8,$member_id);
                $member_data = array('code'=>$code);
                $this->membersdata->update($member_id,$member_data);
                
               $email_message .= "<p>Please confirm your email address to prevent potential misuse by third parties.</p>";
                
                $data = [
                    'message'=>$email_message,
                    'name' => $firstname,
                    'link' => $this->config->item('main_url')."/project-owner/signup/confirm?code=".$code
                ];
                
                $email_message = $this->load->view('project-owner/email_templates/signup_email_template_new',$data,TRUE);
                
                $sendgrid_data = [
                    'subject' => "Welcome to Servicechain - Confirm your email",
                    'message' => $email_message,
                    'admin_name' => "Servicechain",
                    'admin_email' => "support@servicechain.com",
                    'recipient' => $firstname.' '.$lastname,
                    'recipient_email' => $email,
                ];
                
                $response = $this->sendEmail($sendgrid_data);
                $status = true;
                $message = "Thank you for signing up!<br> The activation email is on the way, <br>it was sent to<br> $email <br>";
                
                
                
            }else {
                $message = "You already activated your account.";
            }
        }else {
            $message = "Email address does not exist.";
        }
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['message'=>$message]));
    }
    
    public function confirm(){
        $code = $this->input->get('code');
        
        $member_info = $this->membersdata->getbyattribute('code',$code);
        
        if($member_info->num_rows() > 0) {
            $member_id = $this->membersdata->update($member_info->row()->id,['is_activated' => 1]);
            
            if($member_id != 0) {
                $is_admin_check = $this->membersdata->getinfo('is_admin','username',$member_info->row()->username);
                
                
                $newdata = array(
                    'username' => $member_info->row()->username,
                    'userid' => $member_info->row()->id,
                    'logged_in' => TRUE,
                    'is_admin' => $is_admin_check == 1 ? TRUE:FALSE,
                    'page' => 'homeowner'
                );

                if($is_admin_check != 1){
                    $expiry_date = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($member_info->row()->signup_date)));
                    $member_plan = array(
                            'member_id' => $member_info->row()->id,
                            'amount' => 0,
                            'expiry_date' => $expiry_date
                            );
                    $this->memberplandata->update(0,$member_plan);
                }


                
                $this->session->set_userdata($newdata);
                
                //header("Location: /project-owner/dashboard");
				header("Location: /onboarding");
            }
        }
    }
    
    private function sendEmail($data) {
        $html_content = wordwrap($data['message']);
        
        require $this->config->item('sendgrid_path');
        $from = new SendGrid\Email($data['admin_name'], $data['admin_email']);
        $to = new SendGrid\Email($data['recipient'], $data['recipient_email']);
        $reply_to = new SendGrid\Email($data['admin_name'], $data['admin_email']);
        $content = new SendGrid\Content("text/html", $html_content);
        $mail = new SendGrid\Mail($from, $data['subject'], $to, $content);
        $mail->setReplyTo($reply_to);
        $sg = new \SendGrid($this->config->item('sendgrid_key'));
        $response = $sg->client->mail()->send()->post($mail);
        
        return $response;
    }


    private function validatesms($countryCode,$phoneNumber,$phone_code){
        $status = TRUE;

        $phoneNumberObj = $this->libphonenumber->parse($phoneNumber,$countryCode);
        $phone_number = 'Invalid Number';

        if(is_array($phoneNumberObj)) {
            $status = $phoneNumberObj['status'];
            $msg = $phoneNumberObj['message'];

            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('status'=>$status,'message'=>$msg)));
        } elseif(is_object($phoneNumberObj)) {
            $isValidNumber = $this->libphonenumber->isValidNumber($phoneNumberObj);
            $isPossibleNumber = $this->libphonenumber->isPossibleNumber($phoneNumberObj);
            $isValidNumberForRegion = $this->libphonenumber->isValidNumberForRegion($phoneNumberObj,$countryCode);

            if($isValidNumber == FALSE) {
                $status = FALSE;
                $msg = 'Invalid phone number.';
                
            } elseif($isPossibleNumber == FALSE) {
                $status = FALSE;
                $msg = 'Invalid phone number.';
            } elseif($isValidNumberForRegion == FALSE) {
                $status = FALSE;
                $msg = 'Invalid phone number in your region.';
            }

            if($status == TRUE) {
                $formattedNumber = $this->libphonenumber->format($phoneNumberObj);
                $formattedNumber = str_replace(' ','',$formattedNumber);
                $phone_number = ltrim($formattedNumber, '+'.$phone_code);
            }
        }

        return $phone_number;
        
    }
}

