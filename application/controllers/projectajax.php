<?
class Projectajax extends CI_Controller {
    
    
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
    
    public function loadprojects(){
        $limit = 30;
        
        $userid = $this->session->userdata('userid');
        
        $sql = "SELECT  * from projects order by id desc limit $limit ";
        
        $all_results = $this->db->query($sql);
        
        $data['projects'] = $this->db->query($sql);
        
        $this->load->view('projectajax/project-list',$data);
        
    }
    
}