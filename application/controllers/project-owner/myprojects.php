<?

class Myprojects extends CI_Controller {
    
    
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
        $this->load->model('countrydata');
		$this->load->model('tokentransactions');
		$this->load->model('historydata');
        $this->load->database();
    }
    
    public function index(){
        if ($this->session->userdata('logged_in')){
            $data['title'] = "Servicechain.com - My Projects";
            $data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
            $data['count_projects'] =  $this->projectsdata->getcountbyattribute('userid',$data['userid']);
            if ($this->session->userdata('page')=='homeowner'){
                $this->load->view('project-owner/myprojects/index',$data);
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
        $html = $this->load->view('project-owner/myprojects/project_list',$data,true);
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
        $html = $this->load->view('project-owner/myprojects/project_list_div',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('html'=>$html)));
    }
    
    public function loadform(){
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$id =  $this->db->escape_str($this->input->post('id'));
			$upgrade_plan = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',5));
			$data= array();
			$data['upgrade_plan'] = $upgrade_plan;
			
			if ($id > 0){
				$proj =  $this->projectsdata->getbyattribute('id',$id);
				$data['title'] = $proj->row()->title;
				$data['description'] = $proj->row()->description;
				$data['icon_image'] = $proj->row()->icon_image;
				$data['cover_image'] = $proj->row()->cover_image;
				$data['network'] = $this->projectcontractdata->getinfo('network','project_id',$id);
				$data['state'] = $proj->row()->state;
				$data['city'] = $proj->row()->city;
				$data['address'] = $proj->row()->address;
				$data['zipcode'] = $proj->row()->zipcode;
				$data['country_id'] = $proj->row()->country_id;
				$data['phone_number'] = $proj->row()->phone_number;
			}else {
				$data['title'] = "";
				$data['description'] = "";
				$data['icon_image'] = "";
				$data['cover_image'] = "";
				if($upgrade_plan){
					$data['network'] = "main";
				}else{
					$data['network'] = "test";
				}
				$data['state'] = "";
				$data['city'] = "";
				$data['address'] = "";
				$data['zipcode'] = "";
				$data['country_id'] = 1;
				$data['phone_number'] = "";
			}
			
			$data['project_id'] = $id;
			$data['countries'] = $this->countrydata->getall('order by name asc');
		   
			$this->load->view('project-owner/myprojects/project_form',$data);
		}
    }
    
    public function loadchangestatus(){
        $userid = $this->session->userdata('userid');
        $id =  $this->db->escape_str($this->input->post('id'));
        $proj =  $this->projectsdata->getbyattribute('id',$id);
        $data['status'] = $proj->row()->status;
        $data['project_id'] = $proj->row()->id;
        $this->load->view('project-owner/myprojects/project_form_status',$data);
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
        $image_cover =  $this->db->escape_str($this->input->post('image_cover'));
        $slug = url_title($title, 'dash', true);
        $project_id =  $this->db->escape_str($this->input->post('project_id'));
        
        $country_id =  $this->db->escape_str($this->input->post('country_id'));
        $phone_number =  $this->db->escape_str($this->input->post('phone_number'));
        $state =  $this->db->escape_str($this->input->post('state'));
        $city =  $this->db->escape_str($this->input->post('city'));
        $address =  $this->db->escape_str($this->input->post('address'));
        $zipcode =  $this->db->escape_str($this->input->post('zipcode'));
        
        $update = false;
        $p_array = array('userid'=>$userid,'title'=>$title,'description'=>$description,'slug'=>$slug,'icon_image'=>$image,
            'cover_image'=>$image_cover,'city'=>$city,'address'=>$address,'zipcode'=>$zipcode,'country_id'=>$country_id,'phone_number'=>$phone_number,'state'=>$state);
        if (($project_id=="")||($project_id==0)){
            $project_id = $this->projectsdata->update(0,$p_array);
			
			//createuser history
			$url = "/project-owner/project/$project_id/$slug";
			$h_message = 'has created project '.$title;
			$h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>$url);
			$this->historydata->update(0,$h_array);
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
        $this->load->view('project-owner/myprojects/project_form_loader',$data);
    }
    
    public function autosearchstate(){
        $row_set = array();
        $term =  $this->db->escape_str($this->input->get('term'));
        $query= $this->db->query("SELECT c.`Name` AS value, c.`StateId` AS id FROM  `states` c  WHERE c.`Name` LIKE '%$term%'  ");
        if ($query->num_rows() > 0){
            foreach ($query->result() as $rowk)
            {
                $row['value']=$rowk->value;
                $row['id']=(int)$rowk->id;
                $row_set[] = $row;//build an array
            }
        }
        echo json_encode($row_set);//format the array into json data
    }
    
    public function autosearchcity(){
        $term =  $this->db->escape_str($this->input->post('term'));
        $country =  $this->db->escape_str($this->input->post('country'));
        $row_set = array();
        $query= $this->db->query("SELECT c.`city` AS value, c.`id` AS id FROM  `country_cities` c  WHERE c.`city` LIKE '%$term%'  AND `country` = '$country'");
        if ($query->num_rows() > 0){
            foreach ($query->result() as $rowk)
            {
                $row['value']=$rowk->value;
                $row['id']=(int)$rowk->id;
                $row_set[] = $row;//build an array
            }
        }
        echo json_encode($row_set);//format the array into json data
    }
}