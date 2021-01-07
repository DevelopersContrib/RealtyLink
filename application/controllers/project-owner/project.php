<?

class Project extends CI_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('datatables');
        $this->load->model('membersdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
        $this->load->database();
    }
	    
    public function index(){
        if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
            $data['title'] = "Servicechain.com - Project";
            $data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
			
			$project_id = $this->uri->segment(3);
			$project_slug = $this->uri->segment(4);
			$data['tab'] = $this->uri->segment(5);
			
			$data['project_id'] = $project_id;
			$data['project_slug'] = $project_slug;
			
			$project_query = $this->projectsdata->getbyattribute('id',$project_id,'userid',$userid);
			$project = $project_query->row_array();
			if(!empty($project)){
				$data['project'] = $project;
			
				$this->load->view('project-owner/project/index',$data);
			}else{
				$this->load->view('/error/404');
				//redirect('404');
				//throw new Exception();
			}
        }else{
            redirect("/project-owner");
            exit;
        }
    }
    
	
	private function sendNotification($data) {        
		$html_content = wordwrap($data['message']);
        
		require $this->config->item('sendgrid_path');
		$from = new SendGrid\Email($data['admin_name'], $data['admin_email']);
		$to = new SendGrid\Email($data['recipient'], $data['recipient_email']);
		$reply_to = new SendGrid\Email($data['admin_name'], $data['admin_email']);
		$content = new SendGrid\Content("text/html", $html_content);
		$mail = new SendGrid\Mail($from, $data['subject'], $to, $content);
		$mail->setReplyTo($reply_to);
		$sg = new \SendGrid($this->config->item('sendgrid_key'));
        $response = $sg->client->mail()->send()->post($mail);
        
        return $response;
	}

	public function transactions() {
		if ($this->session->userdata('logged_in')){
			$userid = $this->session->userdata('userid');
            $data['title'] = "Servicechain.com - Project";
            $data['userid'] = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
			
			$project_id = $this->uri->segment(4);
			
			$data['project_id'] = $project_id;
			
			$project_query = $this->projectsdata->getbyattribute('id',$project_id,'userid',$userid);
			
            $project = $project_query->row_array();
            
            $data['title'] = "Servicechain.com - ".ucwords($project['title'])." Blockchain Transactions";

			if(!empty($project)){
				$data['project'] = $project;
			
				$this->load->view('project-owner/project/transactions',$data);
			}else{
				$this->load->view('/error/404');
				//redirect('404');
				//throw new Exception();
			}
        }else{
            redirect("/project-owner");
            exit;
        }
    }
    
    public function contractslist() {
        $project_id = $this->input->get('project_id');

        $select = "contract_type, address, trans_id, date_created, network";
        $sWhere = '';
        $aColumns = explode(',' , $select);
        if (isset($_GET['search'])){
            
            $search = $_GET['search'];
            $sSearch = $this->db->escape_like_str(trim($search['value']));
            
            $sWhere = "(";
            
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                if (!empty($sSearch)){
                    if(strpos($aColumns[$i],'.')){
                        $sWhere .= "".$aColumns[$i]." LIKE '%".$sSearch."%' OR ";
                    }else{
                        $sWhere .= "`".$aColumns[$i]."` LIKE '%".$sSearch."%' OR ";
                    }
                }
            }
            $sWhere .= ')';
            
        }
        
        if($sWhere!="()"){
            $sWhere = str_replace("OR )",")",$sWhere);
            $this->datatables->where($sWhere);
        }
        
        if (isset($_GET['order'])){
            $order = $_GET['order'];
            $index = $order[0]['column'];
            $this->datatables->order_by($aColumns[$index],$order[0]['dir']);
        }
        
        
		$this->datatables
		->select($select)
		->from('project_contracts')
		->where('project_id',$project_id);

            
        
        echo $this->datatables->generate(); 
    }

	public function translist(){
		$project_id = $this->input->get('project_id');

        $select = "members.firstname, members.lastname, task_contributions.trans_id, task_contributions.token_amount, task_contributions.token_currency,
				   task_contributions.notes, task_contributions.date_of_transaction,task_contributions.status,task_contributions.network,task_contributions.task_id ";
        $sWhere = '';
        $aColumns = explode(',' , $select);
        if (isset($_GET['search'])){
            
            $search = $_GET['search'];
            $sSearch = $this->db->escape_like_str(trim($search['value']));
            
            $sWhere = "(";
            
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                if (!empty($sSearch)){
                    if(strpos($aColumns[$i],'.')){
                        $sWhere .= "".$aColumns[$i]." LIKE '%".$sSearch."%' OR ";
                    }else{
                        $sWhere .= "`".$aColumns[$i]."` LIKE '%".$sSearch."%' OR ";
                    }
                }
            }
            $sWhere .= ')';
            
        }
        
        if($sWhere!="()"){
            $sWhere = str_replace("OR )",")",$sWhere);
            $this->datatables->where($sWhere);
        }
        
        if (isset($_GET['order'])){
            $order = $_GET['order'];
            $index = $order[0]['column'];
            $this->datatables->order_by($aColumns[$index],$order[0]['dir']);
        }
        
        
		$this->datatables
		->select($select)
		->from('task_contributions')
		->join('members','members.id = task_contributions.userid')
		->join('project_tasks','project_tasks.id = task_contributions.task_id')
		->where('project_tasks.project_id',$project_id);

            
        
        echo $this->datatables->generate(); 
    }
	
}