<?

class People extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
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
        $this->load->database();
    }
    
    public function index(){
        $data['title'] = "Servicechain.com - People" ;
        $this->load->view('people/index',$data);
    }
    
	public function load()
	{
		$pages = $this->db->escape_str($this->input->post('pages'));
		$search_key = $this->db->escape_str($this->input->post('search_key'));
		
		$order = ' ORDER BY signup_date desc ';
		$where = '';
		
		if (empty($pages)) {
			$pages = 1; 
		}

		$limit = 6;
		$start = ($pages-1) * $limit;
		
		$search = '';
		if ($search_key != ""){
			$search = " AND (firstname like '%$search_key%' OR lastname like '%$search_key%' OR username like '%$search_key%')";
		}
		
		$sql = " SELECT 
			COUNT(distinct t_new.id) total_new,
			COUNT(distinct t_in_progress.id) total_in_progress,
			COUNT(distinct t_for_approval.id) total_for_approval,
			COUNT(distinct t_completed.id) total_completed,
			COUNT(distinct t_task.id) total_task,
			members.username, members.id, members.firstname, 
			members.lastname, members.profile_image, members.signup_date
			FROM members
			LEFT JOIN project_tasks t_new on members.id = t_new.assigned_to AND t_new.status = 'new'
			LEFT JOIN project_tasks t_in_progress on members.id = t_in_progress.assigned_to AND t_in_progress.status = 'in progress'
			LEFT JOIN project_tasks t_for_approval on members.id = t_for_approval.assigned_to AND t_for_approval.status = 'for approval'
			LEFT JOIN project_tasks t_completed on members.id = t_completed.assigned_to AND t_completed.status = 'completed'
			LEFT JOIN project_tasks t_task on members.id = t_task.assigned_to 
			
			WHERE user_type = 'contractor' $search
			GROUP BY members.id $order ";
		
		$query = $this->db->query($sql);
		
		$total_count = $query->num_rows();

		$sql = $sql." LIMIT $start,$limit";			

		$pages_count = ceil($total_count / $limit);
		$query = $this->db->query($sql);

		$data['query'] = $query;
		$data['pages_count'] = $pages_count;
		$data['current_page'] = $pages;

		$html = $this->load->view('people/peoples',$data,true);
		$pagination = $this->load->view('people/peoples-pagination',$data,true);
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status'=>true,'total_count'=>$total_count,'html'=>$html,'pagination'=>$pagination)));

	}
}