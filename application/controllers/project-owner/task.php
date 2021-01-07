<?php
class Task extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('awsmailapi');
        $this->load->library('cryptoapi');
        $this->load->model('membersdata');
        $this->load->model('projectsdata');
		$this->load->model('projectcontractdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
		$this->load->model('notificationsdata');
		$this->load->model('memberwalletdata');
		$this->load->model('taskupdatesdata');
		$this->load->model('taskcontributionsdata');
		$this->load->model('countrydata');
        $this->load->database();
		
    }
	    
    public function index(){
        if ($this->session->userdata('logged_in')){
			
        }
    }
	
	public function uploadfile(){
		header('Access-Control-Allow-Origin: *');
	    $options = array( 'upload_dir' => './uploads/tasks/','upload_url' =>'/uploads/tasks/','accept_file_types'=>'/\.(gif|jpeg|jpg|png|txt|doc|docx|pdf|xlsx|xls|csv)$/i');
	    $this->load->library('uploadhandler', $options);
	}

	public function uploadphoto(){
		header('Access-Control-Allow-Origin: *');
	    $options = array( 'upload_dir' => './uploads/tasks/','upload_url' =>'/uploads/tasks/','accept_file_types'=>'/\.(gif|jpeg|jpg|png)$/i');
	    $this->load->library('uploadhandler', $options);
	}
    
    public function load()
	{
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$project_id = $this->db->escape_str($this->input->post('project'));
			$pages = $this->db->escape_str($this->input->post('pages'));
			$search_key = $this->db->escape_str($this->input->post('search_key'));
			$container = $this->db->escape_str($this->input->post('container'));
			
			$order = '';
			$where = '';
			if(!empty($container)){
				if($container == '#latest'){
					$order = ' ORDER BY project_tasks.id desc ';
				}else if($container == '#withapp'){
					$where = ' AND (SELECT COUNT(id) FROM tasks_applications WHERE task_id = `project_tasks`.id) > 0 AND assigned_to is null ';
				}else if($container == '#forapp'){
					$where = " AND status = 'for approval' ";
				}else if($container == '#completed'){
					$where = " AND status = 'completed' ";
				}
			}
			
			if (empty($pages)) {
				$pages = 1; 
			}

			$limit = 10;
			$start = ($pages-1) * $limit;
			
			$search = '';
			if ($search_key != ""){
				$search = " AND title like '%$search_key%'";
			}
			
			$sql = " SELECT `project_tasks`.*, members.firstname, members.lastname, members.profile_image, members.username FROM `project_tasks` 
				LEFT JOIN members on project_tasks.assigned_to = members.id
				WHERE project_id = '$project_id' $where $search $order ";

			
			
			$query = $this->db->query($sql);
			
			$total_count = $query->num_rows();

			$sql = $sql." LIMIT $start,$limit";			

			$pages_count = ceil($total_count / $limit);
			$query = $this->db->query($sql);

			$data['query'] = $query;
			$data['pages_count'] = $pages_count;
			$data['current_page'] = $pages;
			$data['userid'] = $userid;
			$data['container'] = $container;

			$html = $this->load->view('project-owner/task/tasks',$data,true);
			$pagination = $this->load->view('project-owner/task/task-pagination',$data,true);
			
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('status'=>true,'total_count'=>$total_count,'html'=>$html,'pagination'=>$pagination,'container'=>$container)));
		}
	}
	
	public function get(){
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$task_id = $this->db->escape_str($this->input->post('task'));
			$container = $this->db->escape_str($this->input->post('container'));
			
			$task_query = $this->projecttasksdata->getbyattribute('id',$task_id);
			$task = $task_query->row_array();

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('status'=>true,'task'=>$task,'container'=>$container)));
		}
	}
	
	public function delete(){
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$task_id =  $this->db->escape_str($this->input->post('task'));
			$container =  $this->db->escape_str($this->input->post('container'));
			
			$project_id = $this->projecttasksdata->getinfo('project_id','id',$task_id);
			
			$project_userid = $this->projectsdata->getinfo('userid','id',$project_id);
			if($project_userid!=$userid){
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array('status'=>false,'msg'=>"Invalid project")));die();
			}
			$status = $this->projecttasksdata->delete('id',$task_id);
			$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array('status'=>!empty($status),'msg'=>"Project deleted",'container'=>$container,'task'=>$task_id)));
			
		}else{
			header("HTTP/1.1 404 Not Found");
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
	
	public function approve()
	{
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$application =  $this->db->escape_str($this->input->post('application'));
			$container = $this->db->escape_str($this->input->post('container'));
			$process = '';
			if ($this->taskapplicationsdata->checkexist('id',$application)){
				$task_id = $this->taskapplicationsdata->getinfo('task_id','id',$application);
				$ownerid = $this->projecttasksdata->getinfo('created_by','id',$task_id);

				if($ownerid==$userid){ //check if owner 
					$query = $this->taskapplicationsdata->getbyattribute('task_id',$task_id);
					$status = false;
					if ($query->num_rows() > 0){
						foreach ($query->result() as $row){
							$applicant_id = $this->taskapplicationsdata->getinfo('userid','id',$row->id);
							$title = $this->projecttasksdata->getinfo('title','id',$task_id);
							$slug = url_title($title, 'dash', true);
							$url = $this->config->item('main_url')."/tasks/details/$task_id/$slug";
							
							$result = '';
							if($row->id==$application){ //approve
								$status = $this->taskapplicationsdata->update($row->id,array('status'=>'approved'));
								$result = 'approved';
								$this->projecttasksdata->update($task_id,array('assigned_to'=>$applicant_id));
								
								$applicant_name = $this->membersdata->getinfobyid('firstname',$applicant_id);
								$applicant_lastname = $this->membersdata->getinfobyid('lastname',$applicant_id);
								
								/*
								$to_name = $this->membersdata->getinfobyid('firstname',$ownerid);
								$to_email = $this->membersdata->getinfobyid('email',$ownerid);
								
								$from_name = 'Servicechain';
								$from_email = 'support@servicechain.com';
								
								$email_message = "<p>The application of $applicant_name to task $title has been approved</p>";
								
								$data = [
									'message'=>$email_message,
									'name' => $to_name,
									
								];
								
								$email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
								
								$sendgrid_data = [
									'subject' => "Servicechain Task Application ",
									'message' => $email_message,
									'admin_name' => "Servicechain",
									'admin_email' => "support@servicechain.com",
									'recipient' => $to_name,
									'recipient_email' => $to_email,
								];
								
								$response = $this->sendEmail($sendgrid_data);
								*/
								
								$n_message = 'has approved your application for '.$title;
								$n_link = "/task/updates/".$task_id;
								$n_array = array('member_id'=>$applicant_id,'subject'=>'Task Application Approval','message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
								$this->notificationsdata->update(0,$n_array);
								
								
								
								$project_id = $this->projecttasksdata->getinfo('project_id','id',$task_id);
								if ($this->projectcontractdata->hascontract($project_id,'dan')===true){
									
									$wallet_address = $this->memberwalletdata->getinfo('wallet_address','member_id',$applicant_id);
									
									$data_address = $this->projectcontractdata->getinfo('address','project_id',$project_id,'contract_type','data');
									$contract_address = $this->projectcontractdata->getinfo('address','project_id',$project_id,'contract_type','dan');
									
									if ($data_address != ""){
										$network = $this->projectcontractdata->getinfo('network','address',$data_address);
										if ($wallet_address != ""){
											$is_dao_member = $this->cryptoapi->ismember($project_id,$wallet_address,$data_address,$project_id,$applicant_id);
											
											if ($is_dao_member===false) {
                                    
												$member= array();
												$member['address'] = $wallet_address;
												$member['name'] = $applicant_name .' '. $applicant_lastname;
												
												if ($network == 'test'){
													$owner_address = $this->config->item('c_account_owner_test');
													$passphrase = $this->config->item('c_account_password_test');
													$tokenreward = $this->config->item('c_account_esh_test');
												}else {
													$owner_address = $this->config->item('c_account_owner');
													$passphrase = $this->config->item('c_account_password');
													$tokenreward = $this->config->item('c_account_esh_main');
												}
												
												$addmmeber = $this->cryptoapi->addmember($owner_address,$data_address,$passphrase,$wallet_address,$project_id,$applicant_id);
												
												if ($addmmeber===true) {
													$is_member = true;
													sleep(3);
												}
												
												if ($is_member) {
													$process = 'Applicant successfully added as member';
												}else {
													$process = "Applicant is not member of data contract";
													$has_error = true;
												}
											}else{
												$process = 'Applicant is already a member';
											}
										}else{
											$process = 'Applicant has no wallet address';
										}
									}else{
										$process = 'Project has no data contract';
									}
								}else{
									$process = 'Project has no dan contract';
								}
							}else{ //decline all
								$status =$this->taskapplicationsdata->update($row->id,array('status'=>'declined'));
								$result = 'declined';
							}
							
							if($status){
								//send message to owner
								$to_name = $this->membersdata->getinfobyid('firstname',$applicant_id);
								$to_email = $this->membersdata->getinfobyid('email',$applicant_id);
								
								$from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
								$from_email = $this->membersdata->getinfobyid('email',$userid);
								
								$email_message = "<p>Your application to task <a href='$url'>$title</a> has been $result</p>";
								
								$data = [
									'message'=>$email_message,
									'name' => $to_name,
									
								];
								
								$email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
								
								$sendgrid_data = [
									'subject' => "Servicechain Task Application Approval ".ucwords($result),
									'message' => $email_message,
									'admin_name' => "Servicechain",
									'admin_email' => "support@servicechain.com",
									'recipient' => $to_name,
									'recipient_email' => $to_email,
								];
								
								
								$notify_by = $this->membersdata->getinfobyid('notify_by',$applicant_id);
								if($notify_by == 'sms'){
								    
								    $country_id = $this->membersdata->getinfobyid('country_id',$applicant_id);
								    $number_raw = $this->membersdata->getinfobyid('phone_number',$applicant_id);
								    $phone_code = $this->countrydata->getinfobyid('phone_code',$country_id);
								    if($country_id == '147'){
								        $number = ltrim($number_raw, '0');
								    }else{
								        $number = $number_raw;
								    }
								    
								    $phone = '+'.$phone_code.$number;
								    $text_message = "Your application for task $title has been $result by $from_name. View here https://www.servicechain.com/task/updates/".$task_id;
								    $this->awsmailapi->send($phone,$text_message);
								}else{
								    $response = $this->sendEmail($sendgrid_data);
								}
								
								
								
							}
						}
					}
					
					$this->output
						->set_content_type('application/json')
						->set_output(json_encode(array('status'=>$status,'container'=>$container,'process'=>$process)));
				}else{
					$this->output
						->set_content_type('application/json')
						->set_output(json_encode(array('status'=>false,'msg'=>'Invalid application')));
				}
			}else{
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array('status'=>false,'msg'=>'Invalid application')));
			}
		}
	}
	
	public function save()
	{
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$title =  $this->db->escape_str($this->input->post('title'));
			$legal_file =  $this->db->escape_str($this->input->post('legalfile'));
			$description =  $this->input->post('description');
			$verification =  $this->db->escape_str($this->input->post('verification'));
			$project_id =  $this->db->escape_str($this->input->post('project'));
			$project_tasks_id =  $this->db->escape_str($this->input->post('task'));
			$goal_date = $this->db->escape_str($this->input->post('goal_date'));
			$payment = $this->db->escape_str($this->input->post('payment'));
			$cash_value = $this->db->escape_str($this->input->post('cash_value'));
			$cash_value = empty($cash_value)?0:$cash_value;
			$esh_value = $this->db->escape_str($this->input->post('esh_value'));
			$esh_value = empty($esh_value)?0:$esh_value;
			$type_id = $this->db->escape_str($this->input->post('type'));
			$task_img = $this->db->escape_str($this->input->post('task_img'));
			
			$container = $this->db->escape_str($this->input->post('container'));
			
			$type_id = 1;
			
			$status = '';
			
			$owner_info = $this->membersdata->getbyattribute('id',$userid);
	
			if(empty($project_tasks_id)){
				$project_tasks_id = 0;
				$status = 'new';
			}
			
			if(!empty($legal_file)){
				$legal_file = "$legal_file";
			}
			
			$error = false;
			$error_msg = '';
			
			if(!empty($project_tasks_id)){
				$created_by = $this->projecttasksdata->getinfo('created_by','id',$project_tasks_id);
				$status = $this->projecttasksdata->getinfo('status','id',$project_tasks_id);
				if($created_by!=$userid){
					$error = true;
					$error_msg = "Invalid record";
				}
			}
			
			if(empty($title)){
				$error = true;
				$error_msg = "Title is required";
			}else if (empty($goal_date)){
				$error = true;
				$error_msg = "Goal Date is required";
			}else if (empty($type_id)){
				$error = true;
				$error_msg = "Type is required";
			}else if (empty($payment)){
				$error = true;
				$error_msg = "Payment is required";
			}else if ($payment == 'cash' && empty($cash_value)){
				$error = true;
				$error_msg = "Cash value is required";
			}else if ($payment == 'equity' && empty($esh_value)){
				$error = true;
				$error_msg = "ESH value is required";
			}else if ($payment == 'cash/equity'){
				if (empty($esh_value)){
					$error = true;
					$error_msg = "ESH value is required";
				}else if(empty($cash_value)){
					$error = true;
					$error_msg = "Cash value is required";
				}
			}
			
			if($error){
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array('status'=>false,'msg'=>$error_msg)));
			}
			
			$formdata = array(
				'project_id'=>$project_id,
				'title'=>$title,
				'description'=>$description,
				'status'=>$status,
				'legal_file'=>$legal_file,
				'verification'=>$verification,
				'goal_date'=>$goal_date,
				'payment'=>$payment,
				'cash_value'=>$cash_value,
				'esh_value'=>$esh_value,
				'type_id'=>$type_id,
				'created_by'=>$userid,
				'image'=>$task_img
			);
			
			$task_id = $this->projecttasksdata->update($project_tasks_id,$formdata);
			
			$sql = " SELECT `project_tasks`.*, members.firstname, members.lastname FROM `project_tasks` 
				LEFT JOIN members on project_tasks.assigned_to = members.id
				WHERE project_tasks.id = '$task_id' ";
			$query = $this->db->query($sql);
			$data['query'] = $query;
			$data['container'] = $container;

			$record = $this->load->view('project-owner/task/tasks',$data,true);
			
			$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array('status'=>!empty($task_id),'task'=>$task_id,'record'=>$record,'msg'=>"<strong>Success!</strong> Task $title successfully save.",'container'=>$container)));
		}else{
			header("HTTP/1.1 404 Not Found");
		}
	}
   
}
?>