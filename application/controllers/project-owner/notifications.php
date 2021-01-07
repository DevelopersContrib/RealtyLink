<?

class Notifications extends CI_Controller {
    
    
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
        $this->load->model('notificationsdata');
        $this->load->database();
    }
    
    public function loadnotifications(){
        $userid = $this->session->userdata('userid');
        $sql = "SELECT COUNT(*) AS total FROM member_notifications WHERE `member_id` = '$userid' AND `is_clicked` = '0'";
        $query = $this->db->query("SELECT COUNT(*) AS total FROM member_notifications WHERE `member_id` = '$userid' AND `is_clicked` = '0'");
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $count =  $row->total;
            }
        }
        $data['query'] = $this->notificationsdata->getbyattribute('member_id',$userid,'is_read',0);
        $html =  $this->load->view('project-owner/notification/notif_list',$data,true);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('count'=>$count,'html'=>$html,'sql'=>$sql)));
    }
    
    
    public function updateclick(){
        $userid = $this->session->userdata('userid');
        $this->db->query("Update member_notifications set is_clicked=1 where member_id='$userid'");
    }
   
   
}