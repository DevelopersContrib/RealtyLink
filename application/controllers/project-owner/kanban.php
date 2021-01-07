<?

class Kanban extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('cryptoapi');
        $this->load->library('twilioapi');
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
		$this->load->model('memberrating');
        $this->load->database();
    }
    
    public function index(){
        if ($this->session->userdata('logged_in')){
            $userid = $this->session->userdata('userid');
            $data['title'] = "Servicechain.com - Tasks Kanban";
            $data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
            $data['qprojects'] = $this->db->query("SELECT * FROM `projects` where userid='$userid' ORDER BY `title`");
            $this->load->view('project-owner/kanban/index',$data);
        }else{
            redirect("/");
            exit;
        }
    }
    
    public function loadmytasks(){
        $userid = $this->session->userdata('userid');
        $status = $this->db->escape_str($this->input->post('status'));
        $project_id = $this->db->escape_str($this->input->post('project_id'));
        $sql_status = 'pending';
        $and = "";
        
        if ($project_id != ""){
           $and = "  and `project_tasks`.`project_id` = '$project_id' ";    
        }
        
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
LEFT JOIN members ON (members.id = `project_tasks`.`assigned_to`)
WHERE `project_tasks`.`created_by` = '$userid'  and `project_tasks`.`status` = '$sql_status' $and order by project_tasks.id desc";
        $data['tasks'] = $this->db->query($sql);
        
        $html = $this->load->view('project-owner/kanban/my_tasks_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['html'=>$html,'status'=>$status]));
    }
    
    public function loadpayment(){
        $id =  $this->db->escape_str($this->input->post('id'));
        $sql = "SELECT * from  `project_tasks` where id='$id'";
        $data['task'] = $this->db->query($sql);
        $html = $this->load->view('project-owner/kanban/payment_form',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['html'=>$html,'status'=>true]));
    }
    
    public function loadtransactions(){
        $id =  $this->db->escape_str($this->input->post('id'));
        $sql = "SELECT * FROM `task_contributions` WHERE task_id ='$id'";
        $data['trans'] = $this->db->query($sql);
        $html = $this->load->view('project-owner/kanban/transaction_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['html'=>$html,'status'=>true]));
    }
    
    public function savepaymentstatus(){
        $id =  $this->db->escape_str($this->input->post('id'));
        $status =  $this->db->escape_str($this->input->post('status'));
        $success = false;
        $userid = $this->session->userdata('userid');
        $t_array = array('completed_payment_status'=>$status);
        $this->projecttasksdata->update($id,$t_array);
        
        $sql = "SELECT * from  `project_tasks` where id='$id'";
        $task = $this->db->query($sql);
        //create to user history
        $slug = url_title($task->row()->title, 'dash', true);
        $h_message = 'has updated payment status of '.$task->row()->title.' to '.$status;
        $h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/project-owner/kanban');
        $this->historydata->update(0,$h_array);
        
        
        //send assigned user notification
        $project = $this->projectsdata->getbyattribute('id',$task->row()->project_id);
        $n_message = 'has updated payment status of '.$task->row()->title.' to '.$status;
       
        $n_link = "/tasks/my";
        $n_array = array('member_id'=>$task->row()->assigned_to,'subject'=>'Task Payment Status Update -'.$project->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
        $this->notificationsdata->update(0,$n_array);
        
        
        //send message to assigned user
        $to_name = $this->membersdata->getinfobyid('firstname',$task->row()->assigned_to);
        $to_email = $this->membersdata->getinfobyid('email',$task->row()->assigned_to);
        
        $from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
        $from_email = $this->membersdata->getinfobyid('email',$userid);
        
        
        $email_message = "<p>$from_name has updated payment status of: ".$task->row()->title." to ".$status."</p>";
        if (($task->row()->payment == 'cash')|| ($task->row()->payment == 'cash/equity')){
            $email_message .= "<p>You have received ".$task->row()->cash_value." USDC</p>"; 
        }
        
        
        $data = [
            'message'=>$email_message,
            'name' => $to_name,
            
        ];
        
        $email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
        
        $sendgrid_data = [
            'subject' => "Servicechain Task Payment Update",
            'message' => $email_message,
            'admin_name' => "Servicechain",
            'admin_email' => "support@servicechain.com",
            'recipient' => $to_name,
            'recipient_email' => $to_email,
        ];
        
        
        $notify_by = $this->membersdata->getinfobyid('notify_by',$task->row()->assigned_to);
        
        if($notify_by == 'sms'){
            $sent ='';
            $country_id = $this->membersdata->getinfobyid('country_id',$task->row()->assigned_to);
            $number_raw = $this->membersdata->getinfobyid('phone_number',$task->row()->assigned_to);
            $phone_code = $this->countrydata->getinfobyid('phone_code',$country_id);
            if($country_id == '147'){
                $number = ltrim($number_raw, '0');
            }else{
                $number = $number_raw;
            }
            
            $phone = '+'.$phone_code.$number;
            
            if (($task->row()->payment == 'cash')|| ($task->row()->payment == 'cash/equity')){
                $sent = "You have received ".$task->row()->cash_value." USDC from ";
            }
            
            $text_message = $sent.' '.$from_name." for task: ".$task->row()->title."  ".$this->config->item('main_url').$n_link;
            $this->awsmailapi->send($phone,$text_message);
            
        }else{
            $response = $this->sendEmail($sendgrid_data);
        }
        
        $success = true;
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status'=>true]));
        
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
	
	public function rate(){
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$rating = $this->db->escape_str($this->input->post('rate'));
			$project_id = $this->db->escape_str($this->input->post('project'));
			$task_id = $this->db->escape_str($this->input->post('task'));
			$comment = $this->db->escape_str($this->input->post('comment'));
			$username = $this->session->userdata('username');
			
			$project = $this->projectsdata->getbyattribute('id',$project_id);
			if ($project->num_rows() > 0){
				if($project->row()->userid == $userid){ //check if user is owner
					$task = $this->projecttasksdata->getbyattribute('id',$task_id);
					if ($task->num_rows() > 0){
						$assigned_to = $task->row()->assigned_to;
						
						if($rating>5) $rating = 5;
						$comment = strip_tags($comment);
						
						$rating_id = $this->memberrating->update(0,array('member_id'=>$assigned_to,'task_id'=>$task_id,
							'rated_by'=>$userid,'rating'=>$rating,'comment'=>$comment));
						
						//send message to assigned user
						$to_name = $this->membersdata->getinfobyid('firstname',$task->row()->assigned_to);
						$to_lastname = $this->membersdata->getinfobyid('lastname',$task->row()->assigned_to);
						$to_email = $this->membersdata->getinfobyid('email',$task->row()->assigned_to);
						
						$from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
						$from_email = $this->membersdata->getinfobyid('email',$userid);
						
						$url = $this->config->item('main_url')."/rating/$task_id";
						
						$slug = url_title($task->row()->title, 'dash', true);
						$task_url = $this->config->item('main_url')."/tasks/details/$task_id/$slug";
						$assign_to_url = $this->config->item('main_url')."/project-owner/profile/$username";
						
						$email_message = "<p>You are rated $rating stars by <a href='$assign_to_url'>$from_name</a> for task <a href='$task_url'>".$task->row()->title."</a>.
							<br><br>Comment: $comment <br><br>	
							<a href='$url'>[Rate Project Owner Now ]</a></p>";

						$data = [
							'message'=>$email_message,
							'name' => $to_name,
							
						];
						
						$email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
						
						$sendgrid_data = [
							'subject' => "Servicechain Rating - ".ucwords($project->row()->title),
							'message' => $email_message,
							'admin_name' => "Servicechain",
							'admin_email' => "support@servicechain.com",
							'recipient' => $to_name,
							'recipient_email' => $to_email,
						];
						
						$response = $this->sendEmail($sendgrid_data);
						//--
						
						//create to user history
						$slug = url_title($task->row()->title, 'dash', true);
						$h_message = "has rated $rating stars for ".$task->row()->title;
						$h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/tasks/details/'.$task_id.'/'.$slug);
						$this->historydata->update(0,$h_array);
						
						//send owner notification
						$n_message = "has rated $rating stars for ".$task->row()->title."<br> Comment: $comment";
						$n_link = '/tasks/details/'.$task_id.'/'.$slug;
						$n_array = array('member_id'=>$assigned_to,'subject'=>"Rating $rating stars - ".$task->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
						$this->notificationsdata->update(0,$n_array);
						
						$this->output
							->set_content_type('application/json')
							->set_output(json_encode(['success'=>!empty($rating_id),'error'=>'']));
					}
				}
			}
		}
	}
    
    public function updatestatus(){
        $status = $this->db->escape_str($this->input->post('status'));
        $task_id = $this->db->escape_str($this->input->post('id'));
        $sql_status = 'pending';
        $task = $this->projecttasksdata->getbyattribute('id',$task_id);
        $success = false;
        $has_error = false;
        $is_member = false;
        $error = "";
        $success_message = "";
        $userid = $this->session->userdata('userid');
        $return_message = '';
     
        
        
        if ( $task->row()->assigned_to != null){
            
            if ( $task->row()->status != 'completed'){
            
            $firstname = $this->membersdata->getinfo('firstname','id',$task->row()->assigned_to);
            $lastname = $this->membersdata->getinfo('lastname','id',$task->row()->assigned_to);
            
            
            $wallet_address = $this->memberwalletdata->getinfo('wallet_address','member_id',$task->row()->assigned_to);
            
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
            
            
            
            if ($sql_status != 'for approval'){
            
            if ($sql_status == 'completed'){
                $project_id = $task->row()->project_id;
                $assigned_to =$task->row()->assigned_to;
                
                if ($this->projectcontractdata->hascontract($task->row()->project_id,'dan')===true){
                    
                    $data_address = $this->projectcontractdata->getinfo('address','project_id',$project_id,'contract_type','data');
                    $dan_address = $this->projectcontractdata->getinfo('address','project_id',$project_id,'contract_type','dan');
                    
                   
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
                            
							$assign_to_name = $firstname.' '.$lastname;
							$task_title = $task->row()->title;
							
							$rating = $this->load->view('project-owner/kanban/rating',
								['assign_to_name'=>$assign_to_name,'task_title'=>$task_title,'project_id'=>$project_id,'task_id'=>$task_id],true);
							
                            $query = $this->db->query("Select * from task_contributions where task_id = '$task_id' and userid='$assigned_to' and trans_id is NULL");
                            if ($query->num_rows() > 0){
                                foreach ($query->result() as $row){
                                    $contri_id = $row->id;
                                    if ($row->token_currency == 'USDC'){
                                        $res = $this->cryptoapi->executedatacontribution($data_address,$owner_address,$passphrase,$row->contribution_key);
                                        if (isset($res['txHash'])) {
                                            $this->taskcontributionsdata->update($contri_id,array('status'=>'approved','trans_id'=>$res['txHash']));
                                            $success_message .= 'You successfully approved '.$row->token_amount.' '.$row->token_currency.' for '.$firstname.' '.$lastname.' account<br>';
											$return_message = 'You successfully approved '.$row->token_amount.' '.$row->token_currency.' for '.$firstname.' '.$lastname.' account<br><br>'.$rating;
                                        }else {
                                            $has_error = true;
                                            $error = "Something went wrong while executing contribution";
                                        }
                                    }else {
                                        
                                        $balance = $this->cryptoapi->geteshbalance($project_id);
                                        if ($balance >0){
                                            $res = $this->cryptoapi->executecontribution($dan_address,$owner_address,$passphrase,$row->contribution_key);
                                            if (isset($res['txHash'])) {
                                                $this->taskcontributionsdata->update($contri_id,array('status'=>'approved','trans_id'=>$res['txHash']));
                                                $success_message .= 'You successfully approved '.$row->token_amount.' '.$row->token_currency.' for '.$firstname.' '.$lastname.' account<br>';
												$return_message = 'You successfully approved '.$row->token_amount.' '.$row->token_currency.' for '.$firstname.' '.$lastname.' account<br><br>'.$rating;
                                            }else {
                                                $has_error = true;
                                                $error = "Something went wrong while executing contribution";
                                            }
                                        }else {
                                            $has_error = true;
                                            $error = "Dan contract has ".$balance.' '.$this->config->item('servicechain_token');
                                        }
                                    }
                                }
                            }else{
								//$return_message = $rating;
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
                $h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/project-owner/kanban');
                $this->historydata->update(0,$h_array);
                
                
                //send assigned user notification
                $project = $this->projectsdata->getbyattribute('id',$task->row()->project_id);
                $n_message = 'has updated '.$task->row()->title.' to '.$sql_status;
                if ($success_message != ""){
                    $n_message .= '<br>'.$success_message;
                }
                $n_link = "/tasks/my";
                $n_array = array('member_id'=>$task->row()->assigned_to,'subject'=>'Task Status Update -'.$project->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
                $this->notificationsdata->update(0,$n_array);
                
                
                //send message to assigned user
                $to_name = $this->membersdata->getinfobyid('firstname',$task->row()->assigned_to);
                $to_email = $this->membersdata->getinfobyid('email',$task->row()->assigned_to);
                
                $from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
                $from_email = $this->membersdata->getinfobyid('email',$userid);
                
                
                $email_message = "<p>$from_name has updated task: ".$task->row()->title." to ".$sql_status."</p>";
                if ($success_message != ""){
                    $email_message .= '<br>'.$success_message;
                }
                
                
                $data = [
                    'message'=>$email_message,
                    'name' => $to_name,
                    
                ];
                
                $email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
                
                $sendgrid_data = [
                    'subject' => "Servicechain Task Completed Notification",
                    'message' => $email_message,
                    'admin_name' => "Servicechain",
                    'admin_email' => "support@servicechain.com",
                    'recipient' => $to_name,
                    'recipient_email' => $to_email,
                ];


                $notify_by = $this->membersdata->getinfobyid('notify_by',$task->row()->assigned_to);

                if($notify_by == 'sms'){

                     $country_id = $this->membersdata->getinfobyid('country_id',$task->row()->assigned_to);
                     $number_raw = $this->membersdata->getinfobyid('phone_number',$task->row()->assigned_to);
                     $phone_code = $this->countrydata->getinfobyid('phone_code',$country_id);
                     if($country_id == '147'){
                        $number = ltrim($number_raw, '0');
                    }else{
                        $number = $number_raw;
                    }

                    $phone = '+'.$phone_code.$number;
                    $text_message =  $from_name." has updated task: ".$task->row()->title." to ".$sql_status." ".$this->config->item('main_url').$n_link;
                    $this->awsmailapi->send($phone,$text_message);

                }else{
                    $response = $this->sendEmail($sendgrid_data);
                }
                
                $success = true;
            }
            
            }else {
                $error = 'Only assigned user can set task to for approval!';
            }
            }else {
                $error = 'You can no longer update a completed task!';
            }
        }else {
            $error = 'You cannot update task which is not yet assiged!';
        }
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['success'=>$success,'error'=>$error,'success_message'=>$return_message]));
    }
}