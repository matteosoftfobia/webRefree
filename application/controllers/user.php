<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('Utente_model');
		$this->load->helper('url');
    }
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		redirect('/user/profile');
	}
	
	public function list_all(){
		$temp['data']=$this->Utente_model->get_all();
		$this->load->view('user_list',$temp);
	}
	
	public function insert(){
		$dati = $this->input->post('utente');
		$this->Utente_model->insert($dati);
		redirect('/CodeIgniter/index.php/utente/list_all');
	}
	
	public function profile()
	{
		$temp['data']=$this->Utente_model->get_all();
		
		$this->load->view('user_profile',$temp);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */