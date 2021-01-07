<?

class Tasks extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('cryptoapi');
        $this->load->library('awsmailapi');
        $this->load->model('membersdata');
        $this->load->model('memberwalletdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('projectcontractdata');
        $this->load->model('taskapplicationsdata');
        $this->load->model('countrydata');
        $this->load->model('historydata');
        $this->load->model('notificationsdata');
        $this->load->model('taskcontributionsdata');
        $this->load->model('tokentransactions');
        $this->load->model('cryptocontribdata');
		$this->load->model('vnocdata');
		$this->load->model('onboardingtasks');
        $this->load->model('memberrating');
        $this->load->database();
    }
    
    public function index(){
        $task_id = $this->uri->segment(3);
        $data['title'] = "Servicechain.com - Tasks" ;
        $this->load->view('tasks/index',$data);
        
    }
    
    public function details(){
            $task_id = $this->uri->segment(3);
            $title = $this->projecttasksdata->getinfo('title','id',$task_id);
            $project_id = $this->projecttasksdata->getinfo('project_id','id',$task_id);
            $created_by = $this->projecttasksdata->getinfo('created_by','id',$task_id);
            $userid = $this->session->userdata('userid');
            $data['project'] = $this->projectsdata->getbyattribute('id',$project_id);
            $data['task'] = $this->projecttasksdata->getbyattribute('id',$task_id);
            $data['user'] = $this->membersdata->getbyattribute('id',$created_by);
            $data['title'] = "Servicechain.com - Task ".$title;
            $this->load->view('tasks/details',$data);
        
    }
    
    public function saveapplication(){
        $userid = $this->session->userdata('userid');
        $task_id = $this->db->escape_str($this->input->post('task_id'));
        $apply_message = $this->db->escape_str($this->input->post('message'));
        $status = false;
        $message = "";
        $task = $this->projecttasksdata->getbyattribute('id',$task_id);
        $contractor_esh ='';
        if ($this->taskapplicationsdata->checkexist('task_id',$task_id,'userid',$userid) ===false){
            $ap_array = array('userid'=>$userid,'task_id'=>$task_id,'message'=>$apply_message);
            $this->taskapplicationsdata->update(0,$ap_array);
            
			//send SCESH to home owner for first project
			if(empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',4))){
				if($this->memberwalletdata->checkexist('member_id',$userid) == TRUE) {
					$memberWalletData = $this->memberwalletdata->getbyattribute('member_id',$userid);
					
					if($memberWalletData->num_rows() > 0) {
						$project_id = $task->row()->project_id;
						$sql = "SELECT * FROM `project_contracts` WHERE project_id='$project_id' AND contract_type='dan'";
						$query = $this->db->query($sql);
						if ($query->num_rows() > 0){
							foreach ($query->result() as $row){
								$network = $row->network;
							}
							
							$contractor_esh = $this->cryptoapi->sendservicechainesh($memberWalletData->row()->member_id,
								$memberWalletData->row()->wallet_address,$this->config->item('onboarding_token'),$network,
								'Onboarding task Join your first project for '.$this->config->item('onboarding_token').' SCESH Tokens',4);
						}
					}
				}
			}
			
            //create to user history
            $slug = url_title($task->row()->title, 'dash', true);
            $h_message = 'has applied for '.$task->row()->title;
            $h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/tasks/details/'.$task_id.'/'.$slug);
            $this->historydata->update(0,$h_array);
            
            
            //send owner notification
            $project = $this->projectsdata->getbyattribute('id',$task->row()->project_id);
            $n_message = "has applied for ".$task->row()->title;
            $n_link = "/project-owner/project/".$task->row()->project_id."/".$project->row()->slug;
            $n_array = array('member_id'=>$task->row()->created_by,'subject'=>'Task application - '.$project->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
            $this->notificationsdata->update(0,$n_array);
            
            
            //send message to owner
            $to_name = $this->membersdata->getinfobyid('firstname',$task->row()->created_by);
            $to_email = $this->membersdata->getinfobyid('email',$task->row()->created_by);
            
            $from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
            $from_email = $this->membersdata->getinfobyid('email',$userid);
            
            
            $email_message = "<p>$from_name has applied for task: ".$task->row()->title."</p>";
            $email_message .= "<p>Approve this application now: ".$this->config->item('main_url')."$n_link</p>";
            
            
            $data = [
                'message'=>$email_message,
                'name' => $to_name,
                
            ];
            
            $email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
            
            $sendgrid_data = [
                'subject' => "Servicechain Task Application - ".$project->row()->title." Project",
                'message' => $email_message,
                'admin_name' => "Servicechain",
                'admin_email' => "support@servicechain.com",
                'recipient' => $to_name,
                'recipient_email' => $to_email,
            ];

            $notify_by = $this->membersdata->getinfobyid('notify_by',$task->row()->created_by);
            if($notify_by == 'sms'){

                $country_id = $this->membersdata->getinfobyid('country_id',$task->row()->created_by);
                $number_raw = $this->membersdata->getinfobyid('phone_number',$task->row()->created_by);
                $phone_code = $this->countrydata->getinfobyid('phone_code',$country_id);
                if($country_id == '147'){
                    $number = ltrim($number_raw, '0');
                }else{
                    $number = $number_raw;
                }

                $phone = '+'.$phone_code.$number;
                $text_message = $from_name." has applied for task: ".$task->row()->title.". View here ".$this->config->item('main_url').$n_link;
                $this->awsmailapi->send($phone,$text_message);
            }else{
                $response = $this->sendEmail($sendgrid_data);
            }
            $status = true;
            
            
        }else {
            $message = "You successfully applied for this service task.";
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['message'=>$message,'status'=>$status,'task_id'=>$task_id,'slug'=>$slug]));
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
   
    
    public function my(){
        if ($this->session->userdata('logged_in')){
            $data['title'] = "Servicechain.com - My Tasks";
            $data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
            $this->load->view('tasks/my_tasks',$data);
        }else{
            redirect("/");
            exit;
        }
    }
    
    public function loadapplications(){
        $page = $this->db->escape_str($this->input->post('page'));
        $search_key = $this->db->escape_str($this->input->post('search_key'));
        $sort_by = $this->db->escape_str($this->input->post('sort_by'));
        $from = $this->db->escape_str($this->input->post('from'));
        $html = "";
        $sql = '';
        $and = '';
        $inner_join = '';
        $limit = 8;
        $condition = "";
        $userid = $this->session->userdata('userid');
        switch ($sort_by){
            case 'latest':
                $sort_query = "project_tasks.id DESC";
                break;
            default:
                $sort_query = "project_tasks.id DESC";
                break;
        }
        
        
        
        $start = ($page-1) * $limit;
        $sql = "SELECT  `project_tasks`.*, (`project_tasks`.`cash_value`+`project_tasks`.`esh_value`)AS cost, `projects`.`title` AS project_title, `projects`.`slug` AS project_slug, members.`firstname`, members.`lastname`, members.`username`,members.`profile_image`, `tasks_applications`.`status` AS task_status
FROM `project_tasks` INNER JOIN `projects` ON (`projects`.`id` = `project_tasks`.`project_id`)
INNER JOIN members ON (members.id = `project_tasks`.`created_by`)
INNER JOIN `tasks_applications` ON (`tasks_applications`.`task_id` = `project_tasks`.id)
WHERE `project_tasks`.`assigned_to` IS NULL AND `tasks_applications`.`userid` = '$userid' ";
        
        if ($search_key != ""){
            $sql .= " AND (project_tasks.title like '%$search_key%' OR project_tasks.description like '%$search_key%' ) ";
        }
        $sql .= "Order by ".$sort_query;
        
        $all_results = $this->db->query($sql);
        
        $sql = $sql." LIMIT $start,$limit";
        $data['tasks'] = $this->db->query($sql);
        
        $total_tasks = $all_results->num_rows();
        $pages_count = ceil($total_tasks / $limit);
        $data['limit'] = $limit;
        $data['search_key'] = $search_key;
        
        $data['limit'] = $limit;
        $data['current_page'] = $page;
        $data['pages_count'] = $pages_count;
        $data['sql'] = $sql;
        $data['from'] = $from;
        $data['sql'] = $sql;
        
        $html = $this->load->view('tasks/applications_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['html'=>$html,'count'=>$total_tasks]));
    }
    
    private function adddaocontributions($project_id,$task_id,$member_id) {
        $userid = $this->session->userdata('userid');
        $is_member = false;
        $status = false;
        $error = "";
        
        if ($this->projectcontractdata->hascontract($project_id,'dan')===true){
            
        }else {
            $error = "project has no dan contract yet";
        }
    }
    
    public function loadmytasks(){
        $userid = $this->session->userdata('userid');
        $status = $this->db->escape_str($this->input->post('status'));
        $sql_status = 'pending';
        
        switch ($status){
            case 'new':
                $sql_status = 'new';
            break;
            case 'onprocess':
                $sql_status = 'in progress';
            break;
            case 'forapproval':
                $sql_status = 'for approval';
            break;
            case 'completed':
                $sql_status = 'completed';
            break;
                
        }
        $sql = "SELECT  `project_tasks`.*, (`project_tasks`.`cash_value`+`project_tasks`.`esh_value`)AS cost, `projects`.`title` AS project_title, `projects`.`slug` AS project_slug, members.`firstname`, members.`lastname`, members.`username`,members.`profile_image`
FROM `project_tasks` INNER JOIN `projects` ON (`projects`.`id` = `project_tasks`.`project_id`)
INNER JOIN members ON (members.id = `project_tasks`.`created_by`)
WHERE `project_tasks`.`assigned_to` = '$userid' and `project_tasks`.`status` = '$sql_status'  order by project_tasks.id desc";
        $data['tasks'] = $this->db->query($sql);
        $html = $this->load->view('tasks/my_tasks_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['html'=>$html,'status'=>$status]));
    }
    
    private function savecontribution($task_id,$token_amount,$token_currency,$notes,$key,$trans=null,$wallet_address=null,$network='test') {
        $member_id = $this->session->userdata('userid');
        $data = array(
            'userid' => $member_id,
            'trans_id' => NULL,
            'task_id' => $task_id,
            'token_amount' => $token_amount,
            'token_currency' => $token_currency,
            'notes' => $notes,
            'contribution_key' => $key,
            'contribution_trans' => $trans,
            'contribution_from' =>1,
            'wallet_address'=>$wallet_address,
            'network'=>$network
            );
        
        $insert = $this->taskcontributionsdata->update(0,$data);
        
        return $insert;
    }
    
    public function updatestatus(){
           $status = $this->db->escape_str($this->input->post('status'));
           $task_id = $this->db->escape_str($this->input->post('id'));
           $payment_details = $this->input->post('payment_details');
           $sql_status = 'pending';
           $task = $this->projecttasksdata->getbyattribute('id',$task_id);
           $success = false;
           $has_error = false;
           $is_member = false;
           $has_payment = false;
           $error = "";
           $userid = $this->session->userdata('userid');
           
           $wallet_address = $this->memberwalletdata->getinfo('wallet_address','member_id',$userid);
           $domain_id = 11188;
           
           $firstname = $this->membersdata->getinfo('firstname','id',$userid);
           $lastname = $this->membersdata->getinfo('lastname','id',$userid);
           $email =  $this->membersdata->getinfo('email','id',$userid);
           $password =  $this->membersdata->getinfo('password','id',$userid);
           $user_name = $this->membersdata->getinfo('username','id',$userid);
           $user_name = $user_name.$userid;
           $exist_crypto = false;
           
           
           
           if ($status != 'completed'){
                  switch ($status){
                    case 'new':
                        $sql_status = 'new';
                    break;
                    case 'onprocess':
                        $sql_status = 'in progress';
                    break;
                    case 'forapproval':
                        $sql_status = 'for approval';

                    break;
                    case 'completed':
                        $sql_status = 'completed';
                    break;
                        
                }
                
                
                if ($sql_status == 'for approval'){
                    
                    $project_id = $task->row()->project_id;

                    if(!empty($payment_details)) {
                        $this->projecttasksdata->update($task_id,['completed_payment_details'=>$payment_details]);
                    }
                    
                    if ($this->projectcontractdata->hascontract($task->row()->project_id,'dan')===true){
                        
                        $data_address = $this->projectcontractdata->getinfo('address','project_id',$project_id,'contract_type','data');
                        $contract_address = $this->projectcontractdata->getinfo('address','project_id',$project_id,'contract_type','dan');
                        
                        if ($data_address != ""){
                            $network = $this->projectcontractdata->getinfo('network','address',$data_address);
                            if ($wallet_address != ""){
                                
                                    if ($network == 'test'){
                                        $owner_address = $this->config->item('c_account_owner_test');
                                        $passphrase = $this->config->item('c_account_password_test');
                                        $tokenreward = $this->config->item('c_account_esh_test');
                                    }else {
                                        $owner_address = $this->config->item('c_account_owner');
                                        $passphrase = $this->config->item('c_account_password');
                                        $tokenreward = $this->config->item('c_account_esh_main');
                                    }
                                    
                                    $is_member = $this->cryptoapi->ismember($project_id,$wallet_address,$data_address);
                                  
                                    if ($is_member) {
                                       
                                        if ($this->taskcontributionsdata->checkexist('task_id',$task_id)===false){
                                        
                                            switch($task->row()->payment){
                                                case 'cash';
                                                    $token_amount= $task->row()->cash_value;
                                                    $key = 'taskc'.$task_id;
                                                    $res = $this->cryptoapi->addcashcontribution($owner_address,$data_address,$passphrase,number_format($token_amount, 18, '', ''),$wallet_address,'task',0,$key);
                                                    if (isset($res['txHash'])) {
                                                        $this->savecontribution($task_id,$token_amount,'USDC','Completed task '.$task->row()->title,$key,$res['txHash'],$wallet_address,$network);
                                                    }else {
                                                        $has_error = true;
                                                        $error = "Something went wrong while adding to contribution";
                                                    }
                                                break;
                                                case 'equity';
                                                $token_amount = $task->row()->esh_value;
                                                    $key = 'taske'.$task_id;
                                                    $res = $this->cryptoapi->addcontribution($owner_address,$contract_address,$passphrase,number_format($token_amount, 18, '', ''),$wallet_address,'task',$tokenreward,$key);
                                                    if (isset($res['txHash'])) {
                                                        $this->savecontribution($task_id,$token_amount,$this->config->item('servicechain_token'),'Completed task '.$task->row()->title,$key,$res['txHash'],$wallet_address,$network);
                                                    }else {
                                                        $has_error = true;
                                                        $error = "Something went wrong while adding to contribution";
                                                    }
                                                break;
                                                case 'cash/equity';
                                                    $token_amount= $task->row()->cash_value;
                                                    $key = 'taskc'.$task_id;
                                                    $res = $this->cryptoapi->addcashcontribution($owner_address,$data_address,$passphrase,number_format($token_amount, 18, '', ''),$wallet_address,'task',0,$key);
                                                    
                                                    if (isset($res['txHash'])) {
                                                        $this->savecontribution($task_id,$token_amount,'USDC','Completed task '.$task->row()->title,$key,$res['txHash'],$wallet_address,$network);
                                                        
                                                        $token_amount = $task->row()->esh_value;
                                                        $key = 'taske'.$task_id;
                                                        $res = $this->cryptoapi->addcontribution($owner_address,$contract_address,$passphrase,number_format($token_amount, 18, '', ''),$wallet_address,'task',$tokenreward,$key);
                                                        
                                                        if (isset($res['txHash'])) {
                                                            $this->savecontribution($task_id,$token_amount,$this->config->item('servicechain_token'),'Completed task '.$task->row()->title,$key,$res['txHash'],$wallet_address,$network);
                                                        }else {
                                                            $has_error = true;
                                                            $error = "Something went wrong while adding to contribution";
                                                        }
                                                        
                                                    }else {
                                                        $has_error = true;
                                                        $error = "Something went wrong while adding to contribution";
                                                    }
                                                    
                                                    
                                                    
                                                break;
                                                
                                                
                                            }
                                        
                                        }
                                        
                                    }else {
                                        $member = array();
                                        
                                        if($this->cryptocontribdata->CheckFieldExists('members','email',$email)){
                                            $exist_crypto = true;
                                        }
                                        
                                        if ($exist_crypto===false){
                                            $mem_data =array(
                                                'email'=>$email,
                                                'name'=>ucwords($firstname).' '.ucwords($lastname),
                                                'password'=>$password,
                                                'name_slug'=>strtolower(trim(preg_replace('/[^a-zA-Z0-9\-]/', '-', $user_name),'\-')),
                                                'email_verified'=>1,
                                                'is_verified'=>1
                                            );
                                            $crypto_member_id = $this->cryptocontribdata->update('members','member_id',0,$mem_data);
                                        } else {
                                            $crypto_member_id = $this->cryptocontribdata->GetInfo('member_id','members','email',$email);
                                        }
                                        
                                        if ($this->cryptocontribdata->CheckFieldExists('addmember_transactions','member_id',$crypto_member_id,'domain_id',$domain_id,'status','pending')===false){
                                            
                                            $tx = $this->cryptoapi->addmemberwithtx($owner_address,$data_address,$passphrase,$wallet_address,$domain_id,$user_name);
                                            $error = "You have just been added as member of data contract. Please try again later.";
                                        } else {
                                            $error = "A transaction under this user still pending. Please wait and try again later.";
                                        }
                                        $has_error = true;
                                    }
                                    
                               
                                
                            }else {
                                $error = "Member has no wallet address";
                                $has_error = true;
                            }
                          
                        }else {
                            $error = "Project has no data contract in blockchain";
                            $has_error = true;
                        }
                        
                        
                    }else {
                        $error = "Project has no dan contract in blockchain";
                        $has_error = true;
                    }
                }
                
                
                if ($has_error === false){
                    $t_array = array('status'=>$sql_status);
                    $this->projecttasksdata->update($task_id,$t_array);
                    
                    //create to user history
                    $slug = url_title($task->row()->title, 'dash', true);
                    $h_message = 'has updated '.$task->row()->title.' to '.$sql_status;
                    $h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/task/updates/'.$task_id);
                    $this->historydata->update(0,$h_array);
                        
                        
                        //send owner notification
                     $project = $this->projectsdata->getbyattribute('id',$task->row()->project_id);
                     $n_message = 'has updated '.$task->row()->title.' to '.$sql_status;
                     $n_link = "/task/updates/".$task_id;
                     $n_array = array('member_id'=>$task->row()->created_by,'subject'=>'Task Update - '.$project->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
                     $this->notificationsdata->update(0,$n_array);
                        
                        
                        //send message to owner
                        $to_name = $this->membersdata->getinfobyid('firstname',$task->row()->created_by);
                        $to_email = $this->membersdata->getinfobyid('email',$task->row()->created_by);
                        
                        $from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
                        $from_email = $this->membersdata->getinfobyid('email',$userid);
                        
                        
                        $email_message = "<p>$from_name has updated task: ".$task->row()->title." to ".$sql_status.". ".$task->row()->completed_payment_details."</p>";
                        if ($payment_details != ''){
                            $has_payment = true;
                            $email_message .= '<p>Payment details: '.$task->row()->completed_payment_details.'</p>';
                        }
                        
                        
                        $data = [
                            'message'=>$email_message,
                            'name' => $to_name,
                            
                        ];
                        
                        $email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
                        
                        if ($has_payment === false){
                            $sendgrid_data = [
                                'subject' => "Servicechain Task Update - ".$project->row()->title." Project",
                                'message' => $email_message,
                                'admin_name' => "Servicechain",
                                'admin_email' => "support@servicechain.com",
                                'recipient' => $to_name,
                                'recipient_email' => $to_email,
                            ];
                        }else {
                            $sendgrid_data = [
                                'subject' => "Servicechain Task Update and Payment Details - ".$project->row()->title." Project",
                                'message' => $email_message,
                                'admin_name' => "Servicechain",
                                'admin_email' => "support@servicechain.com",
                                'recipient' => $to_name,
                                'recipient_email' => $to_email,
                            ];
                        }

                        $notify_by = $this->membersdata->getinfobyid('notify_by',$task->row()->created_by);
                        if($notify_by == 'sms'){

                           $country_id = $this->membersdata->getinfobyid('country_id',$task->row()->created_by);
                           $number_raw = $this->membersdata->getinfobyid('phone_number',$task->row()->created_by);
                           $phone_code = $this->countrydata->getinfobyid('phone_code',$country_id);
                           if($country_id == '147'){
                                $number = ltrim($number_raw, '0');
                            }else{
                                $number = $number_raw;
                            }

                            $phone = '+'.$phone_code.$number;
                            if ($has_payment === false){
                                $text_message =  $from_name." has updated task: ".$task->row()->title." to ".$sql_status." ".$this->config->item('main_url').$n_link;
                            }else {
                                $text_message =  $from_name." has updated task: ".$task->row()->title." to ".$sql_status." and added payment details ".$task->row()->completed_payment_details." ".$this->config->item('main_url').$n_link;
                            }
                            $this->awsmailapi->send($phone,$text_message);

                        }else{
                            $response = $this->sendEmail($sendgrid_data);
                        }
                       
                        
                        $success = true;
                }
                   
           }else {
               $error = 'Only owner can update this task to completed';
           }

            if(!empty($project) || $project != NULL) {
                $project_name = $project->row()->title;
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success'=>$success,'error'=>$error,'t_status'=>$task->row()->status,'payment'=>$task->row()->payment,'projectName'=>!empty($project_name) ? $project_name:'','taskId'=>$task_id]));
    }

    /* public function savepaymentdetails() {
        $task_id = $this->input->post('task_id');
        $payment_details = $this->input->post('payment_details');

        $id = $this->projecttasksdata->update($task_id,['completed_payment_details'=>$payment_details]);

        $this->output
            ->set_content_type('application/json')
                ->set_output(json_encode(['status'=>$id != '0' ? TRUE:FALSE]));
    } */

    public function checkprojectaskpayment() {
        $id = $this->input->post('task_id');

        $payment = $this->projecttasksdata->getinfobyid('payment',$id);
        $details = $this->projecttasksdata->getinfobyid('completed_payment_details',$id);
        if ($details==null){
            $details = '';
        }

        $this->output
            ->set_content_type('application/json')
                ->set_output(json_encode(['payment'=>$payment,'details'=>$details]));
    }
    
    public function loadpayment(){
        $id =  $this->db->escape_str($this->input->post('id'));
        $sql = "SELECT * from  `project_tasks` where id='$id'";
        $data['task'] = $this->db->query($sql);
        $html = $this->load->view('tasks/payment_form',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['html'=>$html,'status'=>true]));
    }
    
    public function savepaymentstatus(){
        $id =  $this->db->escape_str($this->input->post('id'));
        $details =  $this->db->escape_str($this->input->post('details'));
        $success = false;
        $userid = $this->session->userdata('userid');
        $t_array = array('completed_payment_details'=>$details);
        $this->projecttasksdata->update($id,$t_array);
        
        $sql = "SELECT * from  `project_tasks` where id='$id'";
        $task = $this->db->query($sql);
        //create to user history
        $slug = url_title($task->row()->title, 'dash', true);
        $h_message = 'has updated payment details of '.$task->row()->title.': '.$details;
        $h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/project-owner/kanban');
        $this->historydata->update(0,$h_array);
        
        
        //send owner notification
        $project = $this->projectsdata->getbyattribute('id',$task->row()->project_id);
        $n_message ='has updated payment details of '.$task->row()->title.': '.$details;
        
        $n_link = "/tasks/my";
        $n_array = array('member_id'=>$task->row()->created_by,'subject'=>'Task Payment Details Update -'.$project->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
        $this->notificationsdata->update(0,$n_array);
        
        
        //send message to owner
        $to_name = $this->membersdata->getinfobyid('firstname',$task->row()->created_by);
        $to_email = $this->membersdata->getinfobyid('email',$task->row()->created_by);
        
        $from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
        $from_email = $this->membersdata->getinfobyid('email',$userid);
        
        
        $email_message = "<p>$from_name has updated payment details of: ".$task->row()->title."</p><p> Details: ".$details."</p>";
        
        
        $data = [
            'message'=>$email_message,
            'name' => $to_name,
            
        ];
        
        $email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
        
        $sendgrid_data = [
            'subject' => "Servicechain Task Payment Details Update - ".$project->row()->title,
            'message' => $email_message,
            'admin_name' => "Servicechain",
            'admin_email' => "support@servicechain.com",
            'recipient' => $to_name,
            'recipient_email' => $to_email,
        ];
        
        
        $notify_by = $this->membersdata->getinfobyid('notify_by',$task->row()->created_by);
        
        if($notify_by == 'sms'){
            $sent ='';
            $country_id = $this->membersdata->getinfobyid('country_id',$task->row()->created_by);
            $number_raw = $this->membersdata->getinfobyid('phone_number',$task->row()->created_by);
            $phone_code = $this->countrydata->getinfobyid('phone_code',$country_id);
            if($country_id == '147'){
                $number = ltrim($number_raw, '0');
            }else{
                $number = $number_raw;
            }
            
            $phone = '+'.$phone_code.$number;
            
            $text_message = $from_name." updated payment details for task: ".$task->row()->title.". Details: ".$details." ".$this->config->item('main_url').$n_link;
            $this->awsmailapi->send($phone,$text_message);
            
        }else{
            $response = $this->sendEmail($sendgrid_data);
        }
        
        $success = true;
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status'=>true]));
        
    }
}