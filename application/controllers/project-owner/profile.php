<?
class Profile extends CI_Controller {

	function __construct()
	{
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->model('membersdata');
        $this->load->model('membersocialsdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
        $this->load->database();
	}
	
	public function index(){
		if ($this->session->userdata('logged_in')){
			$data['title'] = "Servicechain.com - Profile";
			$data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
			$username = $this->uri->segment(3);
			$data['username'] = $username;
			
			$profile_query = $this->membersdata->getbyattribute('username',$username);
			$profile = $profile_query->row_array();
			$data['profile'] = $profile;
			$data['user_socials'] = $this->membersocialsdata->getbyattribute('member_id',$profile['id']);

			if(empty($profile)){
				$this->load->view('/error/404');
			}else{
				$this->load->view('project-owner/profile/index',$data);
			}
		} else{
			 redirect("/project-owner");
            exit;
		}
	}
	
	public function loadprojects()
	{
		$username = $this->db->escape_str($this->input->post('user'));
		$profile_query = $this->membersdata->getbyattribute('username',$username);
		$profile = $profile_query->row_array();
		$userid = $profile['id'];
		$pages = $this->db->escape_str($this->input->post('pages'));
		$search_key = $this->db->escape_str($this->input->post('search_key'));
		
		$order = '';
		$where = '';
		
		if (empty($pages)) {
			$pages = 1; 
		}

		$limit = 10;
		$start = ($pages-1) * $limit;
		
		$search = '';
		if ($search_key != ""){
			$search = " AND title like '%$search_key%'";
		}
		
		$sql = " SELECT `projects`.* FROM `projects` 
			WHERE userid = '$userid' $where $search $order ";

		
		$query = $this->db->query($sql);
		
		$total_count = $query->num_rows();

		$sql = $sql." LIMIT $start,$limit";			

		$pages_count = ceil($total_count / $limit);
		$query = $this->db->query($sql);

		$data['query'] = $query;
		$data['pages_count'] = $pages_count;
		$data['current_page'] = $pages;

		$html = $this->load->view('project-owner/profile/project-list',$data,true);
		$pagination = $this->load->view('project-owner/profile/project-pagination',$data,true);
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status'=>true,'total_count'=>$total_count,'html'=>$html,'pagination'=>$pagination)));
		
	}
	
}
?>