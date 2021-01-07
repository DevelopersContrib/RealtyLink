<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends CI_Controller {
	private $db_vnoc = null;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	    $this->load->helper('xml');  
        $this->load->helper('text');  
	    $this->load->library('email');
	    $this->load->library('ContribClient');
	    $this->load->library('VnocClient');
	    $this->load->model('membersdata');
        $this->load->model('projectsdata');
        $this->load->model('projecttasksdata');
        $this->load->model('taskapplicationsdata');
	    $this->load->database();
	}
	
	
    public function updates()  
    {  
    	
    	$domain  = $this->db->escape_str($this->input->get('domain'));
        $data['feed_name'] = 'Servicechain.com Updates';  
        $data['encoding'] = 'utf-8';  
        $data['feed_url'] = 'https://www.servicechain.com/feed/updates';  
        $data['page_description'] = 'Latest updates on servicechain.com';  
        $data['page_language'] = 'en-en';  
        $data['creator_email'] = 'admin@domaindirectory.com';  
        
        $sql = "SELECT `member_history`.*, members.username,members.lastname,members.firstname FROM `member_history` INNER JOIN members ON members.id = member_history.member_id ";
        $sql .= " ORDER BY member_history.id DESC ";
        
        
        $data['updates'] = $this->db->query($sql);
        header("Content-Type: application/rss+xml");  
        $this->load->view('rss/updates', $data);  
    }  
    
}	