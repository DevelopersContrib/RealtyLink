<?

class Projectowners extends CI_Controller {
    
    
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

        $data['title'] = "Servicechain.com - Project Owners";
		$this->load->view('projectowners/index',$data);
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

		$limit = 10;
		$start = ($pages-1) * $limit;
		
		$search = '';
		if ($search_key != ""){
			$search = " AND (firstname like '%$search_key%' OR lastname like '%$search_key%' OR username like '%$search_key%')";
		}
		
		$sql = " SELECT members.username, members.id, members.firstname, members.lastname, members.profile_image, members.signup_date,
				COUNT(distinct projects.id) total_projects, 
				COUNT(distinct project_tasks.id) total_tasks
				FROM members
				LEFT JOIN projects on members.id = projects.userid
				LEFT JOIN project_tasks on members.id = project_tasks.created_by

				WHERE user_type = 'homeowner' $search
				GROUP BY members.id $order ";
		
		$query = $this->db->query($sql);
		
		$total_count = $query->num_rows();

		$sql = $sql." LIMIT $start,$limit";			

		$pages_count = ceil($total_count / $limit);
		$query = $this->db->query($sql);

		$data['query'] = $query;
		$data['pages_count'] = $pages_count;
		$data['current_page'] = $pages;

		$html = $this->load->view('projectowners/owners',$data,true);
		$pagination = $this->load->view('projectowners/owners-pagination',$data,true);
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('current_page'=>$pages,'pages_count'=>$pages_count,'status'=>true,'total_count'=>$total_count,'html'=>$html,'pagination'=>$pagination)));

	}
}