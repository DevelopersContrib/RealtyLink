<?

class Referral extends CI_Controller {
    
    
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
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
			$data['userid'] = $userid;
			$data['title'] = "Servicechain.com - Referral" ;
			$data['username'] = $this->session->userdata('username');
			$data['firstname'] = $this->membersdata->getinfo('firstname','id',$userid);
			$data['lastname'] = $this->membersdata->getinfo('lastname','id',$userid);
			$data['email'] = $this->membersdata->getinfo('email','id',$userid);
			$this->load->view('referral/index',$data);
		}else{
			redirect("/");
			exit;
		}
    }
    
}