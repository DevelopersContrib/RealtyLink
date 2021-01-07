<?

class Projects extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->model('membersdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
        $this->load->database();
    }
    
    
    
    public function index(){
        $data['title'] = "Servicechain.com - Projects ";
        $this->load->view('projects/index',$data);
    }
    
    public function loadprojects(){
        $userid = $this->session->userdata('userid');
        $limit =  $this->db->escape_str($this->input->post('limit'));
        $start =  $this->db->escape_str($this->input->post('start'));
        $search_key = $this->db->escape_str($this->input->post('search_key'));
        $status = $this->db->escape_str($this->input->post('status'));
        
        $hasand = false;
        
        $sql = "SELECT m.*, DATE_FORMAT(`date_created`, '%m/%d/%Y') AS mydate ,(
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id
) AS `count_tasks`,(
  SELECT COUNT(*) FROM `tasks_applications` INNER JOIN `project_tasks` ON (`project_tasks`.id = `tasks_applications`.`task_id`) WHERE `project_tasks`.`project_id`=m.id AND `project_tasks`.`assigned_to` IS NULL
) AS `count_applications`, (
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id AND `status` = 'for approval'
) AS `count_approval`,(
  SELECT COUNT(*) FROM `project_tasks` WHERE `project_id`=m.id AND `status` = 'completed'
) AS `count_done`,members.`firstname`, members.`lastname`, members.`username`, members.`profile_image`
 FROM `projects` m INNER JOIN members ON (members.`id` = m.`userid`) ";
        
        
        if ($search_key != ""){
          $sql .= " WHERE (m.title like '%$search_key%' OR m.description like '%$search_key%') ";
          $hasand = true;  
        }

        if ($status != ''){
            if ($hasand){
                $sql .= " AND m.status = '$status' ";
            }else {
                $sql .= " WHERE m.status = '$status' ";
            }
        }
        
        $sql .= " order by m.id desc LIMIT $start,$limit";
        $data['query'] = $this->db->query($sql);
        $html = $this->load->view('projects/project_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('html'=>$html)));
    }
    
}