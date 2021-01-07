<?
class Profile extends CI_Controller {

	function __construct()
	{
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->model('countrydata');
        $this->load->model('membersdata');
        $this->load->model('memberlicensedata');
        $this->load->model('memberbonddata');
        $this->load->model('membersocialsdata');
        $this->load->model('memberplandata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
        $this->load->database();
	}
	
	public function details($username){
		if ($this->session->userdata('logged_in')){
			$data['title'] = "Servicechain.com - Profile";
			$data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
			$data['username'] = $username;
			$userid = $this->session->userdata('userid');
			
			$profile_query = $this->membersdata->getbyattribute('username',$username);
			$profile = $profile_query->row_array();
			$data['profile'] = $profile;
			
			if(empty($profile)){
				$this->load->view('/error/404');
			}else{
				$data['licenseDetails'] = $this->memberlicensedata->getbyattribute('member_id',$profile['id']);
				$data['bondDetails'] = $this->memberbonddata->getbyattribute('member_id',$profile['id']);
				$data['socials'] = $this->membersocialsdata->getbyattribute('member_id',$profile['id']);
				$data['memberDetails'] = $this->membersdata->getbyattribute('id',$profile['id']);
				$data['memberPlan'] = $this->memberplandata->getbyattribute('member_id',$userid);

				$this->load->view('profile/index',$data);
			}
		} else{
			 redirect("/project-owner");
            exit;
		}
	}
	
	
}
?>