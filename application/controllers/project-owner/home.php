<?

class Home extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('ContribClient');
        $this->load->library('VnocClient');
        $this->load->database();
    }
    
    public function index()
    {
        if ($this->session->userdata('logged_in')){
            redirect("/project-owner/dashboard");
        }else{
            $data['title'] = "Servicechain.com - Home";
            $this->load->view('project-owner/home/index',$data);
        }
        
    }
    
    
}

