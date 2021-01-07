<?php
class VnocClient{
    
	private $api_key = null;
	private $code = null;
	private $cookie = null;
	private $access_token = null;
	private $api_url = "http://api1.contrib.co/vaccount/";
	private $state = false;
    private $user = null;
    private $headers = array('Accept: application/json');
	
	
    public function __construct($api_key=null)
	  {
	    if($api_key !== null)
	    {
	      $this->api_key = $api_key;
	    }
	    else 
	    {
	      $this->api_key = null;
	    }
	  }
	
     protected function getCode() {
	     if (isset($_REQUEST['code'])) {
	     	$code = $_REQUEST['code'];
	     	$code = base64_decode($code);
	     	$parts = json_decode($code,true);
	     	setcookie("vnoc_access_token", $parts['access_token'], time()+3600);  
	     	setcookie("vnoc_user", $parts['email'], time()+3600);  
	     	$this->access_token = $parts['access_token'];
	     	$this->user = $parts['email'];
	     	return true;
	     }else {
	     	return false;
	     }
    }
    
    public function getToken(){
    	return $_COOKIE["vnoc_access_token"];
    }
    
    
    public function getCurrentUser(){
    	return $this->user;
    }
    
    
    public function isLoggedIn(){
    	
    	$loggedin = false;
    	if ($this->getCode()===true){
    			$loggedin = true;
    	}else {
    		if ((isset($_COOKIE["vnoc_access_token"])) && (isset($_COOKIE["vnoc_user"]))){
    				if ($_COOKIE["vnoc_access_token"] !="" && ($_COOKIE["vnoc_user"]!="")){
	    			    $this->access_token = $_COOKIE["vnoc_access_token"];
		     	        $this->user = $_COOKIE["vnoc_user"];
		     	        $loggedin = true;
    				}
    		}
        	
        }
    	
    	return $loggedin;
    }
    
    public function resetToken(){
    	unset($_COOKIE['vnoc_access_token']);
     }
    
    
    public function logout($redirect_url){
	     $data = array();
	     $url = $this->api_url.'logoutuser';
	     $this->access_token = $_COOKIE["vnoc_access_token"];
	     $result =  $this->createApiCall($url, 'POST', $this->headers, array('token'=>$this->access_token,'redirect_url'=>$redirect_url));
		 $data = json_decode($result,true);
	     setcookie("vnoc_access_token","", time()+3600);  
		 setcookie("vnoc_user","", time()+3600);  
		 header('Location: '.$redirect_url) ;	
    }
    
    public function LoginUrl($redirect_url,$cancel_url){
    	return "https://manage.vnoc.com/accounts/signin?client=".$this->api_key."&redirect_url=".$redirect_url."&cancel_url=".$cancel_url;
    }
    
    public function getUser(){
    	$code = $_REQUEST['code'];
	    $code = base64_decode($code);
	    $parts = json_decode($code,true);
      $this->access_token = $parts['access_token'];
	    $this->user = $parts['email'];
    	$data = array();
        $url = $this->api_url.'getuserinfo';
        $result =  $this->createApiCall($url, 'POST', $this->headers, array('access_key'=>$this->access_token,'email'=>$this->getCurrentUser()));
		$data = json_decode($result,true);
    	return $data;	
    }
    
	public static function createApiCall($url, $method, $headers, $data = array(),$user=null,$pass=null)
    {
        if ($method == 'PUT')
        {
            $headers[] = 'X-HTTP-Method-Override: PUT';
        }
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        if ($user){
         curl_setopt($handle, CURLOPT_USERPWD, $user.':'.$pass);
        } 
        switch($method)
        {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'PUT':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'DELETE':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        $response = curl_exec($handle);
        return $response;
    }
  
}
?>