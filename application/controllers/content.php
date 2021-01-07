<?php
class Content extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'codegenerator_helper'));
	    $this->load->library('session');
	}
	
	public function tasks()
	{
		$segment = $this->uri->segment(3);
		$file = rawurldecode($segment);
		download_file("uploads/tasks/$file", "$file");
	}
}
?>