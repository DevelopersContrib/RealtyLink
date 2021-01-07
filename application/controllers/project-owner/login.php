<?

class Login extends CI_Controller {
    
    
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
            
            $data['login_contrib'] = $contribclient->LoginUrl($this->config->item('main_url').'/project-owner/social/contrib',$this->config->item('main_url'));
            $data['login_vnoc'] = $vnocclient->LoginUrl($this->config->item('main_url').'/project-owner/social/vnoc',$this->config->item('main_url'));
            $data['login_handyman'] = $handymanclient->LoginUrl($this->config->item('main_url').'/project-owner/social/handyman',$this->config->item('main_url'),'homeowner');
            $data['title'] = "Servicechain.com - Login";
            $data['countries'] = $this->countrydata->getall();
            $this->load->view('project-owner/home/signin',$data);
        }
        
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
        
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('status'=>$status,'message'=>$message)));
    }
    
   
}

