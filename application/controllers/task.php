<?

class Task extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('awsmailapi');
        $this->load->model('membersdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
        $this->load->model('taskupdatesdata');
        $this->load->model('countrydata');
        $this->load->model('historydata');
        $this->load->model('notificationsdata');
        $this->load->model('taskcontributionsdata');
        $this->load->database();
    }
    
    private function replaceimages($html){
  	$html = preg_replace_callback("/src=\"data:([^\"]+)\"/", function ($matches) {
	    list($contentType, $encContent) = explode(';', $matches[1]);
	    if (substr($encContent, 0, 6) != 'base64') {
	        return $matches[0]; // Don't understand, return as is
	    }
	    $imgBase64 = substr($encContent, 6);
	    $imgFilename = md5($imgBase64); // Get unique filename
	    $imgExt = '';
	    switch($contentType) {
	        case 'image/jpeg':  $imgExt = 'jpg'; break;
	        case 'image/gif':   $imgExt = 'gif'; break;
	        case 'image/png':   $imgExt = 'png'; break;
	        default:            return $matches[0]; // Don't understand, return as is
	    }
	    $imgPath = 'uploads/updates/'.$imgFilename.'.'.$imgExt;
	    // Save the file to disk if it doesn't exist
	    if (!file_exists($imgPath)) {
	        $imgDecoded = base64_decode($imgBase64);
	        $fp = fopen($imgPath, 'w');
	        if (!$fp) {
	            return $matches[0];
	        }
	        fwrite($fp, $imgDecoded);
	        fclose($fp);
	    }
	    return 'src="'.$this->config->item('main_url').'/'.$imgPath.'"';
	}, $html);
	return $html;
   }
    
    
    public function uploadimageupdate(){
     $return_value = "";

    if ($_FILES['image']['name']) {
      if (!$_FILES['image']['error']) {
      $name = md5(rand(100, 200));
      $ext = explode('.', $_FILES['image']['name']);
      $filename = $name . '.' . $ext[1];
      $destination = './uploads/updates/' . $filename;
      $location = $_FILES["image"]["tmp_name"];
      move_uploaded_file($location, $destination);
      $return_value = $this->config->item('main_url').'/uploads/updates/' . $filename;
      }else{
      $return_value = 'Ooops! Your upload triggered the following error: '.$_FILES['image']['error'];
      }
    }

echo $return_value;
    }
    
    public function updates(){
        if ($this->session->userdata('logged_in')){
            $data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
            $task_id = $this->uri->segment(3);
            $title = $this->projecttasksdata->getinfo('title','id',$task_id);
            $project_id = $this->projecttasksdata->getinfo('project_id','id',$task_id);
            $created_by = $this->projecttasksdata->getinfo('created_by','id',$task_id);
            $userid = $this->session->userdata('userid');
            $data['project'] = $this->projectsdata->getbyattribute('id',$project_id);
            $data['task'] = $this->projecttasksdata->getbyattribute('id',$task_id);
            $data['user'] = $this->membersdata->getbyattribute('id',$created_by);
            $data['title'] = "Servicechain.com - Updates on ".$title;
            $this->load->view('task/updates',$data);
            
        }else{
            redirect("/");
            exit;
        }
    }
    
    public function loadupdates(){
          $task_id = $this->db->escape_str($this->input->post('task_id'));
          $sql = "SELECT `task_updates`.*, members.`username`, members.`firstname`,members.`lastname`, members.`profile_image`
FROM `task_updates` INNER JOIN members ON (members.`id` = `task_updates`.`userid`) WHERE `task_updates`.`task_id` = '$task_id'
ORDER BY `task_updates`.date_updated DESC";
          $data['updates'] = $this->db->query($sql);
          $html = $this->load->view('task/update_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['html'=>$html]));
    }
    
    public function saveupdate(){
         $task_id = $this->db->escape_str($this->input->post('task_id'));
         $update_id = $this->db->escape_str($this->input->post('update_id'));
         $message = urldecode($this->input->post('message'));
         $message = $this->replaceimages($message);
         $userid = $this->session->userdata('userid');
         if ($update_id == ""){
           $update_id = 0;
         }
         
         $status = false;
         
        $task = $this->projecttasksdata->getbyattribute('id',$task_id);
          
         $u_array = array('task_id'=>$task_id,'userid'=>$userid,'message'=>$message);
         
         $this->taskupdatesdata->update($update_id,$u_array);
         
         
         //create to user history
         $slug = url_title($task->row()->title, 'dash', true);
         $h_message = 'has created an update on '.$task->row()->title;
         $h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/task/updates/'.$task_id);
         $this->historydata->update(0,$h_array);
         
         
         if ($this->session->userdata('page')=='contractor'){
           
            //send owner notification
            $project = $this->projectsdata->getbyattribute('id',$task->row()->project_id);
            $n_message = 'has created an update on '.$task->row()->title;
            $n_link = '/task/updates/'.$task_id;
            $n_array = array('member_id'=>$task->row()->created_by,'subject'=>'Task update - '.$project->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
            $this->notificationsdata->update(0,$n_array);
            
            
            //send message to owner
            $to_name = $this->membersdata->getinfobyid('firstname',$task->row()->created_by);
            $to_email = $this->membersdata->getinfobyid('email',$task->row()->created_by);
            
            
            $from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
            $from_email = $this->membersdata->getinfobyid('email',$userid);
            
            
            $email_message = "<p>$from_name has created an update for task: ".$task->row()->title."</p>";
            $email_message = "$message";
            $email_message .= "<p>View updates: ".$this->config->item('main_url')."$n_link</p>";
            
            
            $data = [
                'message'=>$email_message,
                'name' => $to_name,
                
            ];
            
            $email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
            
            $sendgrid_data = [
                'subject' => "Servicechain Task Update - ".$project->row()->title." Project",
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
                $text_message = $from_name." has created an update for task: ".$task->row()->title." ".$this->config->item('main_url').$n_link;
                $this->awsmailapi->send($phone,$text_message);

            }else{
                $response = $this->sendEmail($sendgrid_data);
            }
            
            
            $status = true;
         
         }else {
             
             //send assigned notification
             if ($task->row()->assigned_to != null){
                 $n_message = 'has created an update on '.$task->row()->title;
                 $n_link = '/task/updates/'.$task_id;
                 $project = $this->projectsdata->getbyattribute('id',$task->row()->project_id);
                 $n_array = array('member_id'=>$task->row()->assigned_to,'subject'=>'Task update - '.$project->row()->title,'message'=>$n_message,'link'=>$n_link,'from_id'=>$userid,'is_clicked'=>0);
                 $this->notificationsdata->update(0,$n_array);
                 
                 
                 //send message to assigned
                 $to_name = $this->membersdata->getinfobyid('firstname',$task->row()->assigned_to);
                 $to_email = $this->membersdata->getinfobyid('email',$task->row()->assigned_to);
                 
                 $from_name = $this->membersdata->getinfobyid('firstname',$userid).' '.$this->membersdata->getinfobyid('lastname',$userid);
                 $from_email = $this->membersdata->getinfobyid('email',$userid);
                 
                 
                 $email_message = "<p>$from_name has created an update for task: ".$task->row()->title."</p>";
                 $email_message = "$message";
                 $email_message .= "<p>View updates: ".$this->config->item('main_url')."$n_link</p>";
                 
                 
                 $data = [
                     'message'=>$email_message,
                     'name' => $to_name,
                     
                 ];
                 
                 $email_message = $this->load->view('project-owner/email_templates/generic_template',$data,TRUE);
                 
                 $sendgrid_data = [
                     'subject' => "Servicechain Task Update",
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
                    $text_message = $from_name." has created an update for task: ".$task->row()->title." ".$this->config->item('main_url').$n_link;
                    $this->awsmailapi->send($phone,$text_message);
    
                }else{
                    $response = $this->sendEmail($sendgrid_data);
                }

             }
             //$response = $this->sendEmail($sendgrid_data);
             $status = true;
             
         }
         
            $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status'=>$status]));
         
    }
    
    
    public function deleteupdate(){
           $id = $this->db->escape_str($this->input->post('id'));
           $this->taskupdatesdata->delete('id',$id);
             $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['id'=>$id]));
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