<?

class Social extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('ContribClient');
        $this->load->library('VnocClient');
        $this->load->library('HandymanClient');
        $this->load->library('cryptoapi');
        $this->load->model('membersdata');
        $this->load->model('memberwalletdata');
        $this->load->model('cryptocontribdata');
        $this->load->database();
    }
    
    public function contrib(){
        $api_key = '364b4d6516bf4907';
        $client = new ContribClient($api_key);
        $client_info = $client->getUser();
        $email = $client_info['data']['info']['email'];
        $username =  $client_info['data']['info']['username'];
        $first_name =  $client_info['data']['info']['first_name'];
        $last_name =  $client_info['data']['info']['last_name'];
        $password = $client_info['data']['info']['userid'];
        $image = $client_info['data']['info']['image'];
        $wallet_address = "";
        $from_crypto = 0;
        $exists_email = $this->membersdata->checkexist('email',$email);
        
        if($exists_email){
            if($this->login($email)){
                //redirect("/project-owner/dashboard");
				redirect("/onboarding");
                exit;
            }else{
                echo "login_error";
            }
        }else{
            $exists_username = $this->membersdata->checkexist('username',$username);
            
            if($exists_username){
                $username = $username.$this->membersdata->generatecode(3);
            }
            
          
            $member_data = [
                'firstname'=>$first_name,
                'lastname'=>$last_name,
                'username' => $username,
                'email' => $email,
                'password' => md5($password),
                'country_id'=>1,
                'phone_number'=>'',
                'user_type'=>'homeowner',
                'code'=>'xxx',
                'is_admin' => 0,
                'is_activated'=>1,
                'signup_from'=>'contrib',
                'profile_image'=>$image
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
                'name' => $first_name,
                'link' => $this->config->item('main_url')."/project-owner/signup/confirm?code=".$code
            ];
            
            $message = $this->load->view('project-owner/email_templates/signup_email_template',$data,TRUE);
            
            $sendgrid_data = [
                'subject' => "Welcome to Servicechain - Confirm your email",
                'message' => $message,
                'admin_name' => "Servicechain",
                'admin_email' => "support@servicechain.com",
                'recipient' => $first_name.' '.$last_name,
                'recipient_email' => $email,
            ];
            
            $response = $this->sendEmail($sendgrid_data);
            
            if($this->login($email)){
                //redirect("/project-owner/dashboard");
				redirect("/onboarding");
                exit;
            }else{
                echo "login_error";
            }
        }
        
    }
    
    public function vnoc(){
        $api_key = 'a088239f8263dc8f';
        $client = new VnocClient($api_key);
        $client_info = $client->getUser();
        
        $email = $client_info['data']['info']['email'];
        $username =  $client_info['data']['info']['username'];
        $first_name =  $client_info['data']['info']['first_name'];
        $last_name =  $client_info['data']['info']['last_name'];
        $password = $client_info['data']['info']['userid'];
        $image = $client_info['data']['info']['image'];
        
        $wallet_address = "";
        $from_crypto = 0;
        
        
        $exists_email = $this->membersdata->checkexist('email',$email);
        
        if($exists_email){
            if($this->login($email)){
                //redirect("/project-owner/dashboard");
				redirect("/onboarding");
                exit;
            }else{
                echo "login_error";
            }
        }else{
            $exists_username = $this->membersdata->checkexist('username',$username);
            
            if($exists_username){
                $username = $username.generatecode(3);
            }
            
            $member_data = [
                'firstname'=>$first_name,
                'lastname'=>$last_name,
                'username' => $username,
                'email' => $email,
                'password' => md5($password),
                'country_id'=>1,
                'phone_number'=>'',
                'user_type'=>'homeowner',
                'code'=>'xxx',
                'is_admin' => 0,
                'is_activated'=>1,
                'signup_from'=>'vnoc',
                'profile_image'=>$image
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
                'name' => $first_name,
                'link' => $this->config->item('main_url')."/project-owner/signup/confirm?code=".$code
            ];
            
            $message = $this->load->view('project-owner/email_templates/signup_email_template',$data,TRUE);
            
            $sendgrid_data = [
                'subject' => "Welcome to Servicechain - Confirm your email",
                'message' => $message,
                'admin_name' => "Servicechain",
                'admin_email' => "support@servicechain.com",
                'recipient' => $first_name.' '.$last_name,
                'recipient_email' => $email,
            ];
            
            $response = $this->sendEmail($sendgrid_data);
            
            if($this->login($email)){
                //redirect("/project-owner/dashboard");
				redirect("/onboarding");
                exit;
            }else{
                echo "login_error";
            }
        }
        
        
    }
    
    public function handyman(){
        $api_key = 'a088239f8263dc8f';
        $client = new HandymanClient($api_key);
        $client_info = $client->getUser();
        
       
        $email = $client_info['data']['info']['email'];
        $username =  $client_info['data']['info']['username'];
        $first_name =  $client_info['data']['info']['first_name'];
        $last_name =  $client_info['data']['info']['last_name'];
        $password = $client_info['data']['info']['userid'];
        $image = $client_info['data']['info']['image'];
        $type = $client_info['data']['info']['type'];
        
        $wallet_address = "";
        $from_crypto = 0;
        
        
        $exists_email = $this->membersdata->checkexist('email',$email);
        
        if($exists_email){
            if($this->login($email)){
                //redirect("/project-owner/dashboard");
				redirect("/onboarding");
                exit;
            }else{
                echo "login_error";
            }
        }else{
            $exists_username = $this->membersdata->checkexist('username',$username);
            
            if($exists_username){
                $username = $username.generatecode(3);
            }
            
            $member_data = [
                'firstname'=>$first_name,
                'lastname'=>$last_name,
                'username' => $username,
                'email' => $email,
                'password' => md5($password),
                'country_id'=>1,
                'phone_number'=>'',
                'user_type'=>'homeowner',
                'code'=>'xxx',
                'is_admin' => 0,
                'is_activated'=>1,
                'signup_from'=>'handyman',
                'profile_image'=>$image
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
                'name' => $first_name,
                'link' => $this->config->item('main_url')."/project-owner/signup/confirm?code=".$code
            ];
            
            $message = $this->load->view('project-owner/email_templates/signup_email_template',$data,TRUE);
            
            $sendgrid_data = [
                'subject' => "Welcome to Servicechain - Confirm your email",
                'message' => $message,
                'admin_name' => "Servicechain",
                'admin_email' => "support@servicechain.com",
                'recipient' => $first_name.' '.$last_name,
                'recipient_email' => $email,
            ];
            
            $response = $this->sendEmail($sendgrid_data);
            
            if($this->login($email)){
                //redirect("/project-owner/dashboard");
				redirect("/onboarding");
                exit;
            }else{
                echo "login_error";
            }
        }
        
        
    }
    
    public function login($email){
        if ($this->membersdata->checkexist('email',$email)===true){
            $is_admin_check = $this->membersdata->getinfo('is_admin','email',$email);
            $is_admin = FALSE;
            if($is_admin_check == 1){
                $is_admin = TRUE;
            }
            
            $newdata = array(
                'username'  => $this->membersdata->getinfo('username','email',$email),
                'userid'     =>  $this->membersdata->getinfo('id','email',$email),
                'logged_in' => TRUE,
                'is_admin' => $is_admin,
                 'page' => $this->membersdata->getinfo('user_type','email',$email)
            );
            
            $this->session->set_userdata($newdata);
            $status = true;
            return $status;
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
    
    
}