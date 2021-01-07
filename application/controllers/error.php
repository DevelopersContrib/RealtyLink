<?php
class error extends CI_Controller
{
	public function __construct()
	{
	        parent::__construct();
	}
 
	public function index()
	{
		$this->output->set_status_header('404');
		$data['content'] = '404';  // View name
		$this->load->view('/error/404', $data);
	}
}
?>