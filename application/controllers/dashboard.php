<?

class Dashboard extends CI_Controller {

	
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

	public function index(){
		if ($this->session->userdata('logged_in')){
			$data['title'] = "Servicechain.com - Dashboard";
			$data['userid'] = $this->session->userdata('userid');
			$data['username'] = $this->session->userdata('username');
			if ($this->session->userdata('page')=='contractor'){
			 $this->load->view('dashboard/index',$data);
			}else {
			    redirect("/project-owner");
			    exit;
			}
		}else{
			redirect("/");
			exit;
		}
	}
	
	
	
	
	public function details(){
		//if ($this->session->userdata('logged_in')){
			$data['title'] = "Servicechain.com - Dashboard";
			$data['userid'] = $this->session->userdata('userid');
			$data['username'] = $this->session->userdata('username');
			$this->load->view('dashboard/details',$data);
		//}else{
		//	redirect("/home");
		//	exit;
		//}
	}
	
}