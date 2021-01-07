<?php 
require 'twilio/vendor/autoload.php';
use Twilio\Rest\Client;

class Twilioapi
{
	protected $sid; 
	protected $token;
    protected $from;
    
    public function __construct() {
        $this->CI = get_instance();
		$this->CI->config->load("twilio",TRUE);
		$config = $this->CI->config->item('twilio');
		$this->sid = $config['sid'];
		$this->token = $config['token']; 
        $this->from = $config['from']; 
    }
    
    public function sendsms($number,$text_message){
		$twilio = new Client($this->sid, $this->token);
		$message = $twilio->messages
                  ->create($number, // to
                           array("from" =>$this->from, "body" => $text_message)
                  );

          return $message->sid;
		

		
	}
}