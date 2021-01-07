<?
class Rating extends CI_Controller {

	function __construct()
	{
        parent::__construct();
        $this->load->helper(array('form', 'url','cookie','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('cryptoapi');
        $this->load->library('twilioapi');		
        $this->load->model('countrydata');
        $this->load->model('membersdata');
        $this->load->model('memberlicensedata');
        $this->load->model('memberbonddata');
        $this->load->model('membersocialsdata');
        $this->load->model('memberplandata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
		$this->load->model('memberrating');
		$this->load->model('historydata');
		$this->load->model('notificationsdata');
        $this->load->database();
	}
	
	
	public function index(){
        if ($this->session->userdata('logged_in')){
            $userid = $this->session->userdata('userid');
            $data['title'] = "Servicechain.com - Rating";
            $data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
            $user_type = $this->membersdata->getinfo('user_type','id',$userid);
			$data['user_type'] = $user_type;
			
			$task_id = $this->uri->segment(2);
			
			$task = $this->projecttasksdata->getbyattribute('id',$task_id);
			if ($task->num_rows() > 0){
				$project_id = $task->row()->project_id;
				
				$ownerid = $this->projectsdata->getinfo('userid','id',$project_id);
				$data['task_id'] = $task_id;
				$data['owner_name'] = $this->membersdata->getinfo('firstname','id',$ownerid).' '.$this->membersdata->getinfo('lastname','id',$ownerid);
				$data['owner_username'] = $this->membersdata->getinfo('username','id',$ownerid);
				$data['task_title'] = $task->row()->title;
				$data['slug'] = url_title($task->row()->title, 'dash', true);
				if($user_type == 'contractor'){
					$this->load->view('rating/index',$data);
				}else{
					redirect("/");
				}
			}
        }else{
			$task_id = $this->uri->segment(2);
			$redirectData = [
				'redirect'=>"/rating/$task_id",
			];

			$encodedRedirectData = json_encode($redirectData);

			$cookieRedirect = array(
				'name'=>'servicechain_redirect',
				'value'=>$encodedRedirectData,
				'expire'=>86400,
				'domain'=>'.servicechain.com',
				'path'=>'/'
			);

			$this->input->set_cookie($cookieRedirect);
            redirect("/login?redirect=".rawurlencode("/rating/$task_id"));
            exit;
        }
    }
	
	public function rate(){
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$rating = $this->db->escape_str($this->input->post('rate'));
			$task_id = $this->db->escape_str($this->input->post('task'));
			$comment = $this->db->escape_str($this->input->post('comment'));
			$username = $this->session->userdata('username');
			
			$task = $this->projecttasksdata->getbyattribute('id',$task_id);
			if ($task->num_rows() > 0){
				$assigned_to = $task->row()->assigned_to;
				$created_by = $task->row()->created_by;
				$project_id = $task->row()->project_id;
				$project_title = $this->projectsdata->getinfo('title','id',$project_id);
				if($assigned_to == $userid){
					if($rating>5) $rating = 5;
					$comment = strip_tags($comment);
					
					$rating_id = $this->memberrating->getinfo('id','task_id',$task_id,'rated_by',$userid);
					$rating_id = empty($rating_id)?0:$rating_id;
					
					$rating_id = $this->memberrating->update($rating_id,array('member_id'=>$created_by,'task_id'=>$task_id,
						'rated_by'=>$userid,'rating'=>$rating,'comment'=>$comment));
					
					//send message to owner
					$to_name = $this->membersdata->getinfobyid('firstname',$task->row()->created_by);
					$to_lastname = $this->membersdata->getinfobyid('lastname',$task->row()->created_by);
					$to_email = $this->membersdata->getinfobyid('email',$task->row()->created_by);
					
					$from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
					$from_email = $this->membersdata->getinfobyid('email',$userid);

					$slug = url_title($task->row()->title, 'dash', true);
					$url = $this->config->item('main_url')."/tasks/details/$task_id/$slug";
					$assign_to_url = $this->config->item('main_url')."/project-owner/profile/$username";
					
					$email_message = "<p>You are rated $rating stars by <a href='$assign_to_url'>$from_name</a> for task <a href='$url'>".$task->row()->title."</a></p>".
						"<p>Comment: $comment</p>";

					$data = [
						'message'=>$email_message,
						'name' => $to_name,
						
					];
					
					$email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
					
					$sendgrid_data = [
						'subject' => "Servicechain Rating - ".ucwords($project_title),
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
					$n_array = array('member_id'=>$task->row()->created_by,'subject'=>"Rating $rating stars - ".$task->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
					$this->notificationsdata->update(0,$n_array);
					
					$this->output
						->set_content_type('application/json')
						->set_output(json_encode(['success'=>!empty($rating_id),'success_message'=>'<h3>Thank you! Your rating has been submitted successfully.</h3>']));
				}
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
	
	public function getcookie(){
		$mData = get_cookie('servicechain_redirect');
		$mData = json_decode($mData);
		var_dump($mData->redirect);
	}
	
	public function deletecookie() {
		$cookieDomain = '.servicechain.com';
		$cookiePath = '/';
		$cookieRedirect = 'servicechain_redirect';
		
		delete_cookie($cookieRedirect, $cookieDomain, $cookiePath);
	}
}
?>