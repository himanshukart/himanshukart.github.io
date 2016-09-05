<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->helper('url');
		$this->load->model('user');
		//check if email is already exists the set flash session
		if (!empty($this->session->flashdata('email_exists'))) {
							$data['email_exists'] = $this->session->flashdata('email_exists');
					}
		//if successfully registered then allow user to choose to his subsribires
		if (!empty($this->session->flashdata('registered'))) {
							$data['registered'] = $this->session->flashdata('registered');
					}
		if (!empty($this->session->flashdata('invalid_login'))) {
							$data['invalid_login'] = $this->session->flashdata('invalid_login');
					}
		if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
			$user_id = $this->session->userdata('user')['user_id'];
			$data['count'] = $this->user->get_notification_count($user_id);
		}
		$data['meta_title'] = 'home page';
		$data['main_content'] = 'home';
		$data['pixel_content'] = 'pixel';
		$this->load->view('includes/templates', $data);
	}
}
