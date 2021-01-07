<?

class Signout extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('ContribClient');
        $this->load->library('VnocClient');
        $this->load->library('cryptoapi');
        $this->load->model('membersdata');
        $this->load->model('memberwalletdata');
        $this->load->model('statesdata');
        $this->load->model('countrydata');
        $this->load->model('cryptocontribdata');
        $this->load->database();
    }
    
    public function index()
    {
        $array_items = array('username' => '', 'email' => '', 'userid' => '', 'logged_in' => FALSE,'is_admin'=>'','page'=>'');
        $this->session->unset_userdata($array_items);
        $this->session->sess_destroy();
        
        header ("Location: /");
        exit; 	
        
    }
    
   
}

