<?php 
require 'awsmail/vendor/autoload.php';
use Aws\Sns\SnsClient; 
use Aws\Exception\AwsException;

class Awsmailapi
{
	protected $aws_key; // Github Client Id
	protected $aws_secret; // Github Secret key
    
    public function __construct() {
        $this->CI = get_instance();
		$this->CI->config->load("aws",TRUE);
		$config = $this->CI->config->item('aws');
		$this->aws_key = $config['aws_key'];
		$this->aws_secret = $config['aws_secret']; 
    }
    
    public function send($number,$text_message){
    

    $SnSclient = new SnsClient([
            'profile' => 'default',
            'region' => 'us-east-1',
            'version' => '2010-03-31',
            'credentials' => [
                'key' => $this->aws_key,
                'secret' => $this->aws_secret ,
            ]
        ]);

           $message = $text_message;
           $phone = $number;

           
           try {
            $result = $SnSclient->publish([
                'Message' => $message,
                'PhoneNumber' => $phone,
            ]);
            return $result;
        } catch (AwsException $e) {
    // output error message if fails
            return $e->getMessage();
        } 
    }
}