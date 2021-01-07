<?

class Settings extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('libphonenumber');
        $this->load->model('membersdata');
        $this->load->model('memberlicensedata');
        $this->load->model('memberbonddata');
        $this->load->model('membersocialsdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
        $this->load->model('countrydata');
        $this->load->database();
    }
    
    public function index(){
        if ($this->session->userdata('logged_in')){
            $userid = $this->session->userdata('userid');
            $data['title'] = "Servicechain.com - Settings";
            $data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
            
            $data['security'] = $this->db->escape_str($this->input->get('security'));
            
            $member_query = $this->membersdata->getbyattribute('id',$userid);
            $data['member'] = $member_query->row_array();
            $data['countries'] = $this->countrydata->getall();
            $data['licenseDetails'] = $this->memberlicensedata->getbyattribute('member_id',$userid);
            $data['bondDetails'] = $this->memberbonddata->getbyattribute('member_id',$userid);
            $data['memberSocials'] = $this->membersocialsdata->getbyattribute('member_id',$userid);
            
            $this->load->view('dashboard/settings',$data);
        }else{
            redirect("/");
            exit;
        }
    }
    
    public function requestpassword(){
        if ($this->session->userdata('logged_in')){
            $userid = $this->session->userdata('userid');
            $member_query = $this->membersdata->getbyattribute('id',$userid);
            $member = $member_query->row_array();
            
            $temppass = $this->membersdata->generatecode(8,$userid);
            
            $password = md5($temppass);
            $member_data = [
                'password' => $password
            ];
            
            $member_id = $this->membersdata->update($userid,$member_data);
            
            $message = "<p>Your Temporary Password: $temppass</p>";
            
            $data = [
                'message'=>$message,
                'name' => $member['firstname'],
                'link' => $this->config->item('main_url')."/project-owner/settings?security=1"
            ];
            
            $message = $this->load->view('project-owner/email_templates/request_temppass_template_new',$data,TRUE);
            
            $sendgrid_data = [
                'subject' => "Servicechain - Request temporary password",
                'message' => $message,
                'admin_name' => "Servicechain",
                'admin_email' => "support@servicechain.com",
                'recipient' => $member['firstname'],
                'recipient_email' => $member['email'],
            ];
            
            $response = $this->sendEmail($sendgrid_data);
            
            if($response->_status_code==202){
                $member_data = [
                    'has_request' => 1
                ];
                
                $member_id = $this->membersdata->update($userid,$member_data);
            }
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('status'=>$response->_status_code==202)));
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
    
    public function uploadphoto(){
        header('Access-Control-Allow-Origin: *');
        $options = array( 'upload_dir' => './uploads/settings/','upload_url' =>'/uploads/settings/','accept_file_types'=>'/\.(gif|jpeg|jpg|png)$/i');
        $this->load->library('uploadhandler', $options);
    }
    
    public function ajaxSaveSettings(){
        if ($this->session->userdata('logged_in')){
            $profile_image = $this->db->escape_str($this->input->post('profile_image'));
            $first_name = $this->db->escape_str($this->input->post('first_name'));
            $last_name = $this->db->escape_str($this->input->post('last_name'));
            $phone_number = $this->db->escape_str($this->input->post('phone_number'));
            $country_id = $this->db->escape_str($this->input->post('country_id'));
            $country_id = empty($country_id)?0:$country_id;
            $userid = $this->session->userdata('userid');
            $status = TRUE;
            $is_number_valid = '';
            $message = '';

           
            $countryCode = $this->countrydata->getinfobyid('code',$country_id);
            $phone_code = $this->countrydata->getinfobyid('phone_code',$country_id);


            $is_number_valid = $this->validatesms($countryCode,$phone_number,$phone_code);
            

            if($is_number_valid == 'Invalid Number'){
                $status = FALSE;
                $message = 'You have Enterd an Invalid Phone Number';
            }else{
               $member_data = [
                    'firstname' => $first_name,
                    'lastname' => $last_name,
                    'phone_number' => $is_number_valid,
                    'country_id'=>$country_id,
                    'profile_image' => $profile_image,
                    
                ];
                $member_id = $this->membersdata->update($userid,$member_data);
            }
          
            
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status'=>$status,'profile_image'=>$profile_image,'message'=>$message]));
            
        }else{
            throw new CHttpException(404,'The specified post cannot be found.');
            exit;
        }
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
    
    public function ajaxSaveNotify(){
        if ($this->session->userdata('logged_in')){
            $notifiy = $this->db->escape_str($this->input->post('notifiy'));
            $userid = $this->session->userdata('userid');
            $message = '';
            $status = TRUE;

            if($notifiy === 'sms') {
                $member_phone_num = $this->membersdata->getinfobyid('phone_number',$userid);

                if(empty($member_phone_num) || $member_phone_num == '') {
                    $status = FALSE;
                    $message = 'Please setup your phone in your profile.';
                }
            }

            if($status === TRUE) {
                $member_data = [
                    'notify_by' => $notifiy
                ];
                $member_id = $this->membersdata->update($userid,$member_data);
            }
            
            $this->output
                ->set_content_type('application/json')
                    ->set_output(json_encode(['status'=>$status,'message'=>$message]));
            
        }else{
            throw new CHttpException(404,'The specified post cannot be found.');
            exit;
        }
        
    }
    
    public function delete(){
        
        if ($this->session->userdata('logged_in')){
            $status = FALSE;
            $password = $this->db->escape_str($this->input->post('password'));
            $userid = $this->session->userdata('userid');
            if ($this->membersdata->checkexist('id',$userid,'password',md5($password))===true){
                
                $this->membersdata->delete('id',$userid);
                $status = TRUE;
            }
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status'=>$status]));
        }
    }
    
    public function checkoldpass(){
        if ($this->session->userdata('logged_in')){
            $checkoldpass = $this->db->escape_str($this->input->post('oldpass'));
            $password = md5($checkoldpass);
            $userid = $this->session->userdata('userid');
            $status = FALSE;
            
            //var_dump($password);
            if ($this->membersdata->checkexist('id',$userid,'password',$password)===true){
                
                $status = TRUE;
            }
            
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('status'=>$status)));
        }
        
    }
    
    public function savepassword(){
        if ($this->session->userdata('logged_in')){
            $userid = $this->session->userdata('userid');
            $checkoldpass = $this->db->escape_str($this->input->post('oldpass'));
            $password = md5($checkoldpass);
            $member_id = false;
            if ($this->membersdata->checkexist('id',$userid,'password',$password)===true){
                $newpass = $this->db->escape_str($this->input->post('newpass'));
                $password = md5($newpass);
                
                $member_data = [
                    'password' => $password
                    
                ];
                $member_id = $this->membersdata->update($userid,$member_data);
            }
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('status'=>$member_id)));
        }
        
    }
    
    public function changepassword() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $status = FALSE;
        
        $memberId = $this->membersdata->getinfo('id','email',$email);

        if(!empty($memberId)) {
            $updateData = [
                'password'=>md5($password)
            ];

            $isSaved = $this->membersdata->update($memberId,$updateData);

            if(!empty($isSaved)) {
                $status = TRUE;
            }
        }

        $this->output
            ->set_content_type('application/json')
                ->set_output(json_encode(['status'=>$status]));
    }

    public function updatelicensedetails() {
        $licenseDetails['member_id'] = $this->session->userdata('userid');
        $licenseDetails['license_no'] = strtoupper($this->input->post('num'));
        $licenseDetails['status'] = ucwords($this->input->post('status'));
        $licenseDetails['type'] = ucwords($this->input->post('type'));
        $licenseDetails['date_issued'] = $this->input->post('date');
        $licenseDetails['info'] = $this->input->post('info');

        $licenseId = $this->memberlicensedata->getinfo('license_id','member_id',$licenseDetails['member_id']);

        if(!empty($licenseId)) {
            $id = $this->memberlicensedata->update($licenseId,$licenseDetails);
        } else {
            $id = $this->memberlicensedata->update(0,$licenseDetails);
        }

        $this->output
            ->set_content_type('application/json')
                ->set_output(json_encode(['status'=>$id]));
    }

    public function updatebonddetails() {
        $bondDetails['member_id'] = $this->session->userdata('userid');
        $bondDetails['bond_agent'] = $this->input->post('agent');
        $bondDetails['bond_value'] = $this->input->post('value');
        $bondDetails['info'] = $this->input->post('info');

        $bondId = $this->memberbonddata->getinfo('bond_id','member_id',$bondDetails['member_id']);

        if(!empty($licenseId)) {
            $id = $this->memberbonddata->update($bondId,$bondDetails);
        } else {
            $id = $this->memberbonddata->update(0,$bondDetails);
        }

        $this->output
            ->set_content_type('application/json')
                ->set_output(json_encode(['status'=>$id]));
    }

    public function savesocials() {
        $fb = $this->input->post('facebook_url');
        $twitter = $this->input->post('twitter_url');
        $ig = $this->input->post('instagram_url');
        $github = $this->input->post('github_url');
        $skype = $this->input->post('skype');
        $telegram = $this->input->post('telegram');
        $userid = $this->session->userdata('userid');

        $socialsData = [
            'facebook_url'=>!empty($fb) ? $fb:NULL,
            'twitter_url'=>!empty($twitter) ? $twitter:NULL,
            'instagram_url'=>!empty($ig) ? $ig:NULL,
            'github_url'=>!empty($github) ? $github:NULL,
            'skype_username'=>!empty($skype) ? $skype:NULL,
            'telegram'=>!empty($telegram) ? $telegram:NULL,
            'member_id'=>$userid
        ];

        $memberSocialId = $this->membersocialsdata->getinfo('id','member_id',$userid);

        if(!empty($memberSocialId)) {
            $id = $this->membersocialsdata->update($memberSocialId,$socialsData);
        } else {
            $id = $this->membersocialsdata->update(0,$socialsData);
        }

        $this->output
            ->set_content_type('application/json')
                ->set_output(json_encode(['status'=>$id]));
    }
}