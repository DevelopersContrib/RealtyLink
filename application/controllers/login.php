<?

class Login extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'codegenerator_helper','cookie'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('ContribClient');
        $this->load->library('VnocClient');
        $this->load->library('HandymanClient');
        $this->load->library('cryptoapi');
        $this->load->model('membersdata');
        $this->load->model('memberwalletdata');
        $this->load->model('statesdata');
        $this->load->model('countrydata');
        $this->load->model('cryptocontribdata');
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
            
            $data['login_contrib'] = $contribclient->LoginUrl($this->config->item('main_url').'/social/contrib',$this->config->item('main_url'));
            $data['login_vnoc'] = $vnocclient->LoginUrl($this->config->item('main_url').'/social/vnoc',$this->config->item('main_url'));
            $data['login_handyman'] = $handymanclient->LoginUrl($this->config->item('main_url').'/social/handyman',$this->config->item('main_url'),'contractor');
            $data['title'] = "ServiceChain - Login";
            $data['countries'] = $this->countrydata->getall();
            $this->load->view('home/signin',$data);
        }
        
    }

    public function forgotpassword() {
        $data['title'] = "Servicechain.com - Forgot Password";
        $this->load->view('home/forgot_password',$data);
    }

    public function changepassword() {
        $data['title'] = "Servicechain.com - Change Password";
        $this->load->view('home/change_password',$data);
    }

    public function sendchangepasswordnotification() {
        $email = $this->input->post('email');
        $status = FALSE;
        if($this->membersdata->checkexist('email',$email) === TRUE) {
            $name = ucwords($this->membersdata->getinfo('firstname','email',$email).' '.$this->membersdata->getinfo('lastname','email',$email));

            $data = [
                'link'=>$this->config->item('main_url').'change-password?em='.base64_encode($email),
                'name'=>$name,
            ];

            $message = $this->load->view('home/change_password_template',$data,TRUE);
            
            $sendgrid_data = [
                'subject' => "Welcome to Servicechain - Change Password",
                'message' => $message,
                'admin_name' => "Servicechain",
                'admin_email' => "support@servicechain.com",
                'recipient' => $name,
                'recipient_email' => $email,
            ];
            
            $response = $this->sendEmail($sendgrid_data);
            $status = $response;
        }

        $this->output
            ->set_content_type('application/json')
                ->set_output(json_encode(array('status'=>$status)));
    }
    
    public function signinprocess(){
        $email = $this->db->escape_str($this->input->post('email'));
        $password_raw = $this->db->escape_str($this->input->post('password'));
        $status = false;
        $password = md5($password_raw);
        $message = '';
        
        if($this->membersdata->getinfo('is_activated','email',$email) == 0){
            
            $message = 'Account is not verified';
            
            
        } else if ($this->membersdata->checkexist('email',$email,'password',$password)===true){
            
            $user_type = $this->membersdata->getinfo('user_type','email',$email);
            
               
                
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
                $ip = $this->input->ip_address();
                $id = $this->membersdata->getinfo('id','email',$email);
                $this->db->query("Update members set login_address='$ip',last_login=NOW() where id='$id' ");
                $status = true;
                
            
            
        } else{
            $user_password = $this->membersdata->getinfo('password','email',$email);
            $message = md5($password) != $user_password ? 'Incorrect password.':'Account does not exist. ';
        }
        
		$redirect = '';
        $mData = get_cookie('servicechain_redirect');
		if(!empty($mData)){
			$mData = json_decode($mData);
			$redirect = $mData->redirect;
			$cookieDomain = '.servicechain.com';
			$cookiePath = '/';
			$cookieRedirect = 'servicechain_redirect';
			
			delete_cookie($cookieRedirect, $cookieDomain, $cookiePath);
		}
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('status'=>$status,'message'=>$message,'redirect'=>$redirect)));
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

    public function test() {
        echo base64_encode('stephen.catacte@gmail.com');
    }
}

