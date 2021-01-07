<?

class CryptoAccount extends CI_Controller {
    
    
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
            $data['title'] = "Servicechain.com - Crypto Account";
            $userid = $this->session->userdata('userid');
            $data['userid'] = $userid;
            $data['username'] = $this->session->userdata('username');
            $data['wallet_address'] = $this->memberwalletdata->getinfo('wallet_address','member_id',$userid);
            $this->load->view('project-owner/cryptoaccount/index',$data);
        }else{
            redirect("/project-owner");
            exit;
        }
    }
    
    public function savewallet(){
        $address =  $this->db->escape_str($this->input->post('address'));
        $userid = $this->session->userdata('userid');
        $w_array = array('wallet_address'=>$address);
        $id = $this->memberwalletdata->getinfo('id','member_id',$userid);
        $old_adress = $this->memberwalletdata->getinfo('wallet_address','member_id',$userid);
        if ($id != ""){
            $this->memberwalletdata->update($id,$w_array);
        }else {
            $this->memberwalletdata->update(0,$w_array);
        }
        
        $h_message = "has updated wallet address from $old_adress to $address";
        $h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/project-owner/cryptoaccount');
        $this->historydata->update(0,$h_array);
    }
    
    public function generatewallet(){
        $userid = $this->session->userdata('userid');
        $code = $this->membersdata->generatecode(8,$userid);
        $address = $this->cryptoapi->createwallet($code);
        $w_array = array('wallet_address'=>$address);
        $id = $this->memberwalletdata->getinfo('id','member_id',$userid);
        $old_adress = $this->memberwalletdata->getinfo('wallet_address','member_id',$userid);
        if ($id != ""){
            $this->memberwalletdata->update($id,$w_array);
        }else {
            $this->memberwalletdata->update(0,$w_array);
        }
        
        $h_message = "has generated new wallet address from $old_adress to $address";
        $h_array = array('member_id'=>$userid,'message'=>$h_message,'link'=>'/project-owner/cryptoaccount');
        $this->historydata->update(0,$h_array);
        
        $email_message = "<p>You have generated new wallet adress $address</p>";
        $email_message .= "<p>Wallet adress password: $code</p>";
        
        $firstname = $this->membersdata->getinfo('firstname','id',$userid);
        $lastname = $this->membersdata->getinfo('lastname','id',$userid);
        $email = $this->membersdata->getinfo('email','id',$userid);
        
        $data = [
            'message'=>$email_message,
            'name' => $this->membersdata->getinfo('firstname','id',$userid),
            
        ];
        
        $email_message = $this->load->view('project-owner/email_templates/wallet_template_new',$data,TRUE);
        
        $sendgrid_data = [
            'subject' => "Servicechain Wallet Details",
            'message' => $email_message,
            'admin_name' => "Servicechain",
            'admin_email' => "support@servicechain.com",
            'recipient' => $firstname.' '.$lastname,
            'recipient_email' => $email,
        ];
        
        $response = $this->sendEmail($sendgrid_data);
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('address'=>$address)));
    }
   
    public function translist(){
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
            ->where('project_tasks.created_by',$this->session->userdata('userid'));
            ;
            
        
        echo $this->datatables->generate(); 
    }
    
    private function sendEmail($data) {
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
   
}