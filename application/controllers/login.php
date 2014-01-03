<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	 
	function __construct() {
        parent::__construct();
        $this->load->model('utente_model');
		$this->load->helper('string');
		$this->load->helper('date');
		$this->load->helper('html');
		$this->load->library('pagination');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');
    }
	 
	public function index()
	{
        $ipaddress = $this->input->ip_address();
        if($this->checkAccess($ipaddress) == FALSE){
	        show_error('Error login',500);
        }
        
        $fields = array(
        	'username'	=>	'username',
			'password'	=>	'password'
        );


		$temp['logged'] = $this->utente_model->logged_in();

      
	        
            $username		=	$this->input->post('username',TRUE);
            $password		=	$this->input->post('password',TRUE);

            // Check credentials
            if($this->login($username,$password)){
                
                $this->utente_model->set_connection($ipaddress,FALSE);
				redirect('/admin/scomparsi');
	        
	        }else{
                $msgIp = $this->utente_model->set_connection($ipaddress, TRUE);
				$this->load->view('admin/login',$temp);
	        }    
        		
	}	
	 
    function login($username=false,$password=false){
    
    	if(!$username) return false;
     	if(!$password) return false;
   		
        $result = $this->utente_model->get_administrator($username,$password);
    	
        if ($result){
               $this->session->set_userdata('ADMIN',TRUE);
			   $this->session->set_userdata('username',$username);
			   redirect('/admin/backoffice');
	        return true;
        }else{
			$result = $this->utente_model->get_normal_user($username,$password);
	        if ($result){
	               $this->session->set_userdata('USER',TRUE);
				   $this->session->set_userdata('username',$username);
				   redirect('/user/profile');
		        return true;
			}
			redirect('/');
	        return false;
        }
    
    }
    
    function logout()
    {
        $this->session->unset_userdata('ADMIN');
        $this->session->sess_destroy();
		redirect('/');
    }
    
    private function checkAccess($ipaddress){
        $banned    = $this->utente_model->check_connection($ipaddress);

        // New IP
        if($banned == FALSE) return TRUE;
        // OLD IP with less than 3 failed attempts
        if($banned[0]->failedAttempts <= 3) return TRUE;

        // OLD IP with more than 3 failed attempts
        $time        = date_create();
        $lastAttempt = date_create($banned[0]->date);

        $dateDiff    = $time->format('U') - $lastAttempt->format('U');
        $hours       = $dateDiff/(60*60);
        if($hours > 1.0) return TRUE;

        // BANNED for 1 hour
        return FALSE;
    }	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */