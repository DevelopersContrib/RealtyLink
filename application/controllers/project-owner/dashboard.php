<?

class Dashboard extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('cryptoapi');
        $this->load->model('membersdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
        $this->load->model('projectcontractdata');
        $this->load->model('mintransdata');
        $this->load->database();
    }
    
    public function index(){
        if ($this->session->userdata('logged_in')){
            $data['title'] = "Servicechain.com - Dashboard";
            $userid = $this->session->userdata('userid');
			$data['userid'] = $userid;
            $data['username'] = $this->session->userdata('username');
            $data['count_projects'] =  $this->projectsdata->getcountbyattribute('userid',$data['userid']);
			$data['new_projects'] =  $this->projectsdata->getcountbyattribute('userid',$data['userid'],'status','new');
			$data['inprogress_projects'] =  $this->projectsdata->getcountbyattribute('userid',$data['userid'],'status','in progress');
			$data['completed_projects'] =  $this->projectsdata->getcountbyattribute('userid',$data['userid'],'status','completed');
			
			$data['count_tasks'] =  $this->projecttasksdata->getcountbyattribute('created_by',$data['userid']);
			$data['forapproval_tasks'] =  $this->projecttasksdata->getcountbyattribute('created_by',$data['userid'],'status','for approval');
			$data['inprogress_tasks'] =  $this->projecttasksdata->getcountbyattribute('created_by',$data['userid'],'status','in progress');
			$data['completed_tasks'] =  $this->projecttasksdata->getcountbyattribute('created_by',$data['userid'],'status','completed');
			
			$latest_sql = "select profile_image, members.id member_id, members.firstname,members.lastname,members.username, project_tasks.* from project_tasks left join members on project_tasks.assigned_to = members.id where created_by = '$userid' order by project_tasks.id desc limit 10";
			$data['latest_query'] = $this->db->query($latest_sql);
		
			$latest_sql = "select profile_image, members.id member_id, members.firstname,members.lastname,members.username, project_tasks.* from project_tasks left join members on project_tasks.assigned_to = members.id where created_by = '$userid' and status='for approval' order by project_tasks.id desc ";
			$data['for_approval_query'] = $this->db->query($latest_sql);

			$appplication_sql = "select profile_image, members.id member_id, members.firstname, members.lastname, members.username, project_tasks.* from tasks_applications 
				inner join project_tasks on tasks_applications.task_id = project_tasks.id 
				inner join members on tasks_applications.userid = members.id
				where project_tasks.assigned_to is null and project_tasks.created_by = '$userid'";
				
			$data['appplication_query'] = $this->db->query($appplication_sql);
			
			
            if ($this->session->userdata('page')=='homeowner'){
                $this->load->view('project-owner/dashboard/index',$data);
            }else {
                redirect("/");
            }
        }else{
            redirect("/project-owner");
            exit;
        }
    }
    
    public function loadprojects(){
        $userid = $this->session->userdata('userid');
        $limit =  $this->db->escape_str($this->input->post('limit'));
        $start =  $this->db->escape_str($this->input->post('start'));
        $data['query'] = $this->db->query("SELECT *, DATE_FORMAT(`date_created`, '%m/%d/%Y') AS mydate ,(
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id 
) AS `count_tasks`,(
  SELECT COUNT(*) FROM `tasks_applications` INNER JOIN `project_tasks` ON (`project_tasks`.id = `tasks_applications`.`task_id`) WHERE `project_tasks`.`project_id`=m.id AND `project_tasks`.`assigned_to` IS NULL
) AS `count_applications`, (
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id AND `status` = 'for approval'
) AS `count_approval`,(
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id AND `status` = 'completed'
) AS `count_done`
 FROM `projects` m WHERE `userid` = '$userid' order by id desc LIMIT $start,$limit");
        $html = $this->load->view('project-owner/dashboard/project_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('html'=>$html)));
    }
    
    
    public function loadprojectdiv(){
        $id =  $this->db->escape_str($this->input->post('id'));
        $data['query'] = $this->db->query("SELECT *, DATE_FORMAT(`date_created`, '%m/%d/%Y') AS mydate ,(
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id
) AS `count_tasks`,(
  SELECT COUNT(*) FROM `tasks_applications` INNER JOIN `project_tasks` ON (`project_tasks`.id = `tasks_applications`.`task_id`) WHERE `project_tasks`.`project_id`=m.id AND `project_tasks`.`assigned_to` IS NULL
) AS `count_applications`, (
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id AND `status` = 'for approval'
) AS `count_approval`,(
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id AND `status` = 'completed'
) AS `count_done`
 FROM `projects` m WHERE `id` = '$id'");
        $html = $this->load->view('project-owner/dashboard/project_list_div',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('html'=>$html)));
    }
    
    public function loadform(){
        $userid = $this->session->userdata('userid');
        $id =  $this->db->escape_str($this->input->post('id'));
        $data= array();
        if ($id > 0){
            $proj =  $this->projectsdata->getbyattribute('id',$id);
            $data['title'] = $proj->row()->title;
            $data['description'] = $proj->row()->description;
            $data['icon_image'] = $proj->row()->icon_image;
            $data['network'] = $this->projectcontractdata->getinfo('network','project_id',$id);
        }else {
            $data['title'] = "";
            $data['description'] = "";
            $data['icon_image'] = "";
            $data['network'] = "test";
        }
        
        $data['project_id'] = $id;
       
        $this->load->view('project-owner/dashboard/project_form',$data);
    }
    
    public function loadchangestatus(){
        $userid = $this->session->userdata('userid');
        $id =  $this->db->escape_str($this->input->post('id'));
        $proj =  $this->projectsdata->getbyattribute('id',$id);
        $data['status'] = $proj->row()->status;
        $data['project_id'] = $proj->row()->id;
        $this->load->view('project-owner/dashboard/project_form_status',$data);
    }
    
    
    public function ajaxUploadUserPhoto(){
        header('Access-Control-Allow-Origin: *');
        $options = array( 'upload_dir' => './uploads/projects/','upload_url' =>'/uploads/projects/','accept_file_types'=>'/\.(gif|jpeg|jpg|png)$/i');
        $this->load->library('uploadhandler', $options);
    }
    
    public function saveproject(){
        $userid = $this->session->userdata('userid');
        $title =  $this->db->escape_str($this->input->post('title'));
        $description =  $this->db->escape_str($this->input->post('description'));
        $network =  $this->db->escape_str($this->input->post('network'));
        $image =  $this->db->escape_str($this->input->post('image'));
        $slug = url_title($title, 'dash', true);
        $project_id =  $this->db->escape_str($this->input->post('project_id'));
        $update = false;
        $p_array = array('userid'=>$userid,'title'=>$title,'description'=>$description,'slug'=>$slug,'icon_image'=>$image);
        if (($project_id=="")||($project_id==0)){
            $project_id = $this->projectsdata->update(0,$p_array);
        }else {
            $project_id = $this->projectsdata->update($project_id,$p_array);
            $update = true;
        }
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('project_id'=>$project_id,'update'=>$update)));
    }
    
    public function savestatus(){
        $project_id =  $this->db->escape_str($this->input->post('project_id'));
        $status =  $this->db->escape_str($this->input->post('status'));
        $project_id = $this->projectsdata->update($project_id,array('status'=>$status));
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('project_id'=>$project_id)));
    }
    
    public function deleteproject(){
        $project_id =  $this->db->escape_str($this->input->post('project_id'));
        $this->db->delete('project_contracts', array('project_id' => $project_id));
        $this->db->delete('project_gallery', array('project_id' => $project_id));
        $this->db->delete('project_tasks', array('project_id' => $project_id));
        $this->db->delete('projects', array('id' => $project_id));
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('project_id'=>$project_id)));
    }
    
    public function loadloader(){
        $id =  $this->db->escape_str($this->input->post('id'));
        $message =  $this->db->escape_str($this->input->post('message'));
        $proj =  $this->projectsdata->getbyattribute('id',$id);
        $data['title'] = $proj->row()->title;
        $data['project_id'] = $proj->row()->id;
        $data['message'] = $message;
        $this->load->view('project-owner/dashboard/project_form_loader',$data);
    }
}