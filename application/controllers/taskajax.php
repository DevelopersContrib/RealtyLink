<?
class Taskajax extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('ContribClient');
        $this->load->library('VnocClient');
        $this->load->model('membersdata');
        $this->load->database();
    }
    
    public function loadtasks(){
        $page = $this->db->escape_str($this->input->post('page'));
        $search_key = $this->db->escape_str($this->input->post('search_key'));
        $sort_by = $this->db->escape_str($this->input->post('sort_by'));
        $from = $this->db->escape_str($this->input->post('from'));
        $payment = $this->db->escape_str($this->input->post('payment'));
		
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
            case 'oldest':
                $sort_query = "project_tasks.id ASC";
            break;
            case 'amount-highest':
                $sort_query = "cost DESC";
            break;
            case 'amount-lowest':
                $sort_query = "cost ASC";
            break;
            case 'alphabetical-az':
                $sort_query = "project_tasks.title ASC";
            break;
            case 'alphabetical-za':
                $sort_query = "project_tasks.title DESC";
             break;
            default:
            $sort_query = "project_tasks.id DESC";
            break;
        }
        
       
        
        $start = ($page-1) * $limit;
        $sql = "SELECT  `project_tasks`.*, (`project_tasks`.`cash_value`+`project_tasks`.`esh_value`)AS cost, `projects`.`title` AS project_title, `projects`.`slug` AS project_slug, members.`firstname`, members.`lastname`, members.`username`,members.`profile_image`  
FROM `project_tasks` INNER JOIN `projects` ON (`projects`.`id` = `project_tasks`.`project_id`)
INNER JOIN members ON (members.id = `project_tasks`.`created_by`)
WHERE `project_tasks`.`assigned_to` IS NULL ";
        
        if ($search_key != ""){
            $sql .= " AND (project_tasks.title like '%$search_key%' OR project_tasks.description like '%$search_key%' ) ";
        }
		
		
        if(!empty($payment)){
			$sql .= " AND (payment = '$payment' ) ";
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
			$data['qprojects'] = $this->db->query('SELECT * FROM `projects` ORDER BY `id` DESC LIMIT 10');
			$data['qpeople'] = $this->db->query("SELECT * FROM `members` WHERE `user_type` = 'contractor' ORDER BY id DESC LIMIT 10");
			
			if ($from == 'home'){
			    $this->load->view('taskajax/ajax-task-list-home',$data);
			}else{
			     $this->load->view('taskajax/ajax-task-list',$data);
			}
    }
    
    public function loadmytasks(){
        $page = $this->db->escape_str($this->input->post('page'));
        $search_key = $this->db->escape_str($this->input->post('search_key'));
        $sort_by = $this->db->escape_str($this->input->post('sort_by'));
        $from = $this->db->escape_str($this->input->post('from'));
        $sql = '';
        $and = '';
        $inner_join = '';
        $limit = 8;
        
        $condition = "";
        
       
        
        $profile_id = $this->uri->segment(3);
        if ($profile_id != ''){
            $userid = $profile_id;
        }else {
            $userid = $this->session->userdata('userid');
        }
        
        
        
        $sort_query = "project_tasks.id DESC";
        
        
        
        
        
        
        $start = ($page-1) * $limit;
        $sql = "SELECT  `project_tasks`.*, (`project_tasks`.`cash_value`+`project_tasks`.`esh_value`)AS cost, `projects`.`title` AS project_title, `projects`.`slug` AS project_slug, members.`firstname`, members.`lastname`, members.`username`,members.`profile_image`
FROM `project_tasks` INNER JOIN `projects` ON (`projects`.`id` = `project_tasks`.`project_id`)
INNER JOIN members ON (members.id = `project_tasks`.`created_by`)
WHERE `project_tasks`.`assigned_to` = '$userid'";
        
        
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
        
        $this->load->view('profile/task-list',$data);
        
    }
}