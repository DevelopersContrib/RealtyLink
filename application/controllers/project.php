<?

class Project extends CI_Controller {
    
    
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
        $this->load->model('countrydata');
        $this->load->database();
    }
    
    
    
    public function details(){
        $project_id = $this->uri->segment(3);
        $title = $this->projectsdata->getinfo('title','id',$project_id);
        $userid = $this->session->userdata('userid');
        $data['project'] = $this->projectsdata->getbyattribute('id',$project_id);
        $data['title'] = "Servicechain.com - Project ".$title;
        
        $data['qprojects'] = $this->db->query('SELECT * FROM `projects` ORDER BY `id` DESC LIMIT 10');
        $data['qpeople'] = $this->db->query("SELECT * FROM `members` WHERE `user_type` = 'contractor' ORDER BY id DESC LIMIT 10");
        $data['address']= urlencode($data['project']->row()->address.','.$data['project']->row()->city.','.$data['project']->row()->state.",".$data['project']->row()->zipcode.",".$this->countrydata->getinfobyid('name',$data['project']->row()->country_id));
        $this->load->view('project/details',$data);
        
    }
    
    public function loadmytasks(){
        $userid = $this->session->userdata('userid');
        $status = $this->db->escape_str($this->input->post('status'));
        $project_id = $this->db->escape_str($this->input->post('project_id'));
        $project = $this->projectsdata->getbyattribute('id',$project_id);
        $sql_status = 'available';
        $sql = "SELECT  `project_tasks`.*, (`project_tasks`.`cash_value`+`project_tasks`.`esh_value`)AS cost, `projects`.`title` AS project_title, `projects`.`slug` AS project_slug, members.`firstname`, members.`lastname`, members.`username`,members.`profile_image`
FROM `project_tasks` INNER JOIN `projects` ON (`projects`.`id` = `project_tasks`.`project_id`)
INNER JOIN members ON (members.id = `project_tasks`.`created_by`)
WHERE `project_tasks`.`project_id` = '$project_id' ";
        
        switch ($status){
            case 'done':
                $sql_status = 'completed';
                $sql .= "and `project_tasks`.`status` = '$sql_status' ";
                break;
            case 'forapproval':
                $sql_status = 'for approval';
                $sql .= "and `project_tasks`.`status` = '$sql_status' ";
                break;
            case 'inprogress':
                $sql_status = 'in progress';
                $sql .= "and `project_tasks`.`status` = '$sql_status' ";
                break;
                
        }
        $sql .=" order by project_tasks.id desc";
        $data['tasks'] = $this->db->query($sql);
        $data['owner'] = $this->membersdata->getbyattribute('id',$project->row()->userid);
        $data['status'] = $sql_status;
        $html = $this->load->view('project/tasks_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['html'=>$html,'status'=>$status]));
    }
    
}