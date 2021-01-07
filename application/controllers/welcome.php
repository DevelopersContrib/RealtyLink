<?

class Welcome extends CI_Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','codegenerator_helper'));
	    $this->load->library('session');
	    $this->load->library('email');
	    $this->load->library('ContribClient');
	    $this->load->library('VnocClient');
	    $this->load->library('libphonenumber');
	    $this->load->model('membersdata');
		$this->load->model('tokentransactions');
	    $this->load->database();
	}

	public function index(){
		if ($this->session->userdata('logged_in')){
			$data['title'] = "Cowork.com - Welcome to Cowork.com";
			$data['userid'] = $this->session->userdata('userid');
			$data['username'] = $this->session->userdata('username');
			$this->load->view('welcome/index',$data);
		}else{
			redirect("/home");
			exit;
		}
	}
	
	public function test()
	{
		if ($this->session->userdata('logged_in')){
			$data['title'] = "Servicechain.com - Upgrade";
			$userid = $this->session->userdata('userid');
			$data['userid'] = $userid;
			$user_type = $this->membersdata->getinfo('user_type','id',$userid);
			$data['user_type'] = $user_type;
			$data['username'] = $this->session->userdata('username');
			$data['firstname'] = $this->membersdata->getinfo('firstname','id',$userid);
			$data['lastname'] = $this->membersdata->getinfo('lastname','id',$userid);
			$data['upgrade_plan'] = !empty($this->tokentransactions->getcountbyattribute('member_id',$userid,'onboarding_task_id',5));
			$data['assign_to_name'] = '';
			$data['task_title'] = '';
			$this->load->view('welcome/test');
		}else{
			redirect("/");
			exit;
		}
		
	}
	
	public function testupload()
	{
		$this->load->view('welcome/testupload');
	}

	public function validatesms(){
		$status = TRUE;
		$supportedRegions = $this->libphonenumber->getSupportedRegions();
		//var_dump($supportedRegions);
		$countryCode = 'PH';
		$phoneNumber = '09500906466';
		$phoneNumberObj = $this->libphonenumber->parse($phoneNumber,$countryCode);

		if(is_array($phoneNumberObj)) {
			$status = $phoneNumberObj['status'];
			$msg = $phoneNumberObj['message'];

			$this->output
				->set_content_type('application/json')
					->set_output(json_encode(array('status'=>$status,'message'=>$msg)));
		} elseif(is_object($phoneNumberObj)) {
			$isValidNumber = $this->libphonenumber->isValidNumber($phoneNumberObj);
			$isPossibleNumber = $this->libphonenumber->isPossibleNumber($phoneNumberObj);
			$isValidNumberForRegion = $this->libphonenumber->isValidNumberForRegion($phoneNumberObj,$countryCode);

			if($isValidNumber == FALSE) {
				$status = FALSE;
				$msg = 'Invalid phone number.';
				
			} elseif($isPossibleNumber == FALSE) {
				$status = FALSE;
				$msg = 'Invalid phone number.';
			} elseif($isValidNumberForRegion == FALSE) {
				$status = FALSE;
				$msg = 'Invalid phone number in your region.';
			}
	
			if($status == TRUE) {
				$formattedNumber = $this->libphonenumber->format($phoneNumberObj);
				$formattedNumber = str_replace(' ','',$formattedNumber);
			}
		}
		$phone_code =  '+63';
			var_dump(ltrim($formattedNumber, $phone_code));
		
	}
    
    /*public function testaws(){
       $this->load->library('awsmailapi');
       $text_message = 'This message is sent from a <br>Amazon SNS code sample.';
       $phone = '+639500906466';
       $this->awsmailapi->send($phone,$text_message);
    }
    
    public function testtwilio(){
       $this->load->library('twilioapi');
       $text_message = 'This message is sent from a <br> Twilio sample.';
       $phone = '+639500906466';
       var_dump($this->twilioapi->sendsms($phone,$text_message));
    }*/

}