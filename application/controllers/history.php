<?php

class History extends CI_Controller {
    
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','codegenerator_helper'));
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('cryptoapi');
        $this->load->library('datatables');
        $this->load->model('membersdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
        $this->load->model('memberwalletdata');
        $this->load->model('historydata');
        $this->load->database();
    }
    
    public function index(){
        if ($this->session->userdata('logged_in')){
            $data['title'] = "Servicechain.com - Your History";
            $userid = $this->session->userdata('userid');
            $this->load->view('history/index',$data);
        }else{
            redirect("/");
            exit;
        }
    }
	public function table() {
		if ($this->session->userdata('logged_in')){
		$userid = $this->session->userdata('userid');
		$select = 'date_created,message,ip_address';
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
			$cols = explode("as",$aColumns[$index]);

			if(count($cols)>1){
				$this->datatables->order_by("`".trim($cols[1])."`",$order[0]['dir']); 
			}else{
				$this->datatables->order_by($aColumns[$index],$order[0]['dir']); 
			}	    	
	    }
			$this->datatables
				->select($select)
				->from('member_history')
				->where('member_id = "'.$userid.'"');
			echo $this->datatables->generate(); 
		}
	}
	
    /*public function table() {
		$draw = $this->input->get("draw");
 	    $columns = $this->input->get("columns");
 	    $order = $this->input->get("order");
 	    $start = $this->input->get("start");
 	    $length = $this->input->get("length");
 	    $search = $this->input->get("search");
		$userid = $this->session->userdata('userid');
		
		$data = [];
		$records_total = 0;
		$records_filtered = 0;

		$search_keyword = $search["value"];
		$result = $this->tokentransactionsdata->getmembertransactions($userid, $start, $length, $order, $search["value"]);
		
		$columns = ["trans_id","token_amount", "token_currency","notes","status"];
		$condition = "`member_id` = $userid";

		if ($search_keyword != null) {
			$condition .= " AND (message LIKE '%$search_keyword%' )";
		}

		

		$order_column = $columns[$order[0]["column"]];
		$order_dir = $order[0]["dir"];

		$this->db->select("trans_id,token_amount,token_currency,notes,status");
		$this->db->from("token_transactions");
		$this->db->where($condition);
		$this->db->order_by($order_column, $order_dir);
		$this->db->limit($limit, $start);

		$query = $this->db->get();
		
		return $query->result();
		
		if (!empty($result)) {
			for ($i=0; $i < sizeof($result); $i++) { 
				$data[$i] = [
					$result[$i]->trans_id,
					$result[$i]->token_amount,
					$result[$i]->token_currency,
					$result[$i]->notes,
					$result[$i]->status
				];
			}

			$records_total = $this->tokentransactionsdata->gettotaltransactions($userid);
			$records_filtered = $records_total;

		}

		$this->output
			->set_content_type("application/json")
				->set_output(json_encode(["draw" => $draw, "recordsTotal" => $records_total, "recordsFiltered" => $records_filtered, "data" => $data]));
	}*/
    
}