<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utente_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get_all()
    {
        $query = $this->db->get('user');
        return $query->result();
    }
	
	function get_logged_user(){
		return $this->session->userdata('username');
	}

    function insert($utente)
    {
        $this->db->insert('user', $utente);
    }

    function update_entry()
    {
        $this->title   = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }
	
	function logged_in ()
	    {
			if($this->session->userdata('ADMIN',false)) 	
				return true;
			else
				return false;
	    }
        
		function get_administrator($username,$password)
	    {
	        $username = mysql_real_escape_string($username);
	        $password = mysql_real_escape_string($password);

	        $this->db->from('user');
	       	$this->db->where('username', $username);
	        $this->db->where('password', $password);
			$this->db->where('is_admin',TRUE);
	        $res = $this->db->get();
	        if ($res->num_rows() > 0) {
	            return $res->row();
	        } else {
	            return FALSE;
	        }
	    }	
	
		function get_normal_user($username,$password)
		    {
		        $username = mysql_real_escape_string($username);
		        $password = mysql_real_escape_string($password);

		        $this->db->from('user');
		       	$this->db->where('username', $username);
		        $this->db->where('password', $password);
		        $res = $this->db->get();
		        if ($res->num_rows() > 0) {
		            return $res->row();
		        } else {
		            return FALSE;
		        }
		    }	
	
		function check_connection($ipaddress)
	    {
	        $ipaddress = mysql_real_escape_string($ipaddress);
	        $this->db->from('login_log');
	        $this->db->where('ipaddress', $ipaddress);
	        $res = $this->db->get();
	        if ($res->num_rows() > 0) {
	            return $res->result();
	        } else {
	            return FALSE;
	        }
	    }

	    function set_connection($ipaddress, $failed)
	    {

	        $insert  = $this->check_connection($ipaddress);
	        if($insert == FALSE){
	            $data = array(
	               'ipaddress'       =>  $ipaddress ,
	               'date'            =>  date( 'Y-m-d H:i:s', time()),
	               'failedAttempts'  =>  0
	            );

	            if($failed == TRUE) 
					$data['failedAttempts'] = 1;
	            $this->db->insert('login_log', $data);
	        }else{
	            $data = array(
	               'ipaddress'       =>  $ipaddress ,
	               'date'            =>  date( 'Y-m-d H:i:s', time()),
	               'failedAttempts'  =>  $insert[0]->failedAttempts
	            );

	            if($failed == TRUE) 
					$data['failedAttempts'] += 1;
	            else                
					$data['failedAttempts']  = 0;

	            $this->db->where('ipaddress', $ipaddress);
	            $this->db->update('login_log', $data);
	        }
	        return $data['failedAttempts'];
	    }
}