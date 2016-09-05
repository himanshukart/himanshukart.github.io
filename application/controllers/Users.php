 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
  * @desc this class will hold functions for user interaction
  * examples include register(), validate_user(), logout()
  * @author Himanshu Gupta hayhimanshu009@gmail.com
  * @extends codeigniter controller
*/
class Users extends CI_Controller {

	/**
	 * Users Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/Users
	 *	- or -
	 * 		http://example.com/index.php/Users/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
   /**
      * Responsable for auto load the model
      * @return void
      */

  /*
    @Author - Himanshu
    @param - form data
    @return - void
    Purpose - function to register new user
   */
  public function register(){
		$this->load->helper('url');

    //load model through load->model
    $this->load->model('user');
    //get the user data post method values
    $username = $this->input->post('name');
    $email = $this->input->post('email');
    $password = $this->input->post('password');
		if(empty($username) || empty($email) ||  empty($password)){
		    //set flash messge to remind all field validation
		    echo 'Please fill all fields';
				die();
    }else{
			//call user model function adduser to add user
			$user_id = $this->user->addUser($username,$email, $password);
			redirect(base_url(), 'refresh');
		}


  }
/*
  @Author - Himanshu
  @param - (string) user DETAIL
  @return - boolean
  Purpose - function to validate user's credenatial for login
 */
  public function validate_user(){
		$this->load->helper('url');
		$this->load->model('user');
    $email = $this->input->post('email');
		$password = $this->input->post('password');
		if(empty($password) || empty($email)){
		    //set flash messge to remind all field validation
		    echo 'Please fill all fields';
				die();
    }else{
			//call user model function validate_user to add validate user
			$user_id = $this->user->validate_user($email, $password);
			redirect(base_url(), 'refresh');
		}
  }
/**
 * logout function and unset session
 * @return [void] []
 */
public function logout(){
	$this->load->helper('url');
	$this->session->unset_userdata('user');
	redirect(base_url(), 'refresh');
}

/**add publisher function to get all the user id  from whom user wants to recieve notification
 * load notify page
 */
public function add_publisher(){
	$this->load->helper('url');
	$this->load->model('user');
  //check from session whether user is logged in else redirect to login page
	if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
		$user_id = $this->session->userdata('user')['user_id'];
    //get all publisher's id
		$data['addpublisher'] =  $this->user->get_publisher($user_id);
    //get user's unread notification count
    $data['count'] = $this->user->get_notification_count($user_id);
		$data['meta_title'] = 'Add publisher';
		$data['main_content'] = 'notify';
		$this->load->view('includes/templates', $data);
	}else{
		redirect(base_url(), 'refresh');
	}
}
/**
 * [remove_publisher used to get all publisher's id user has added them as publisher]
 * @return [void] []
 * load notify page with remove publisher functionlity
 */
public function remove_publisher(){
	$this->load->helper('url');
	$this->load->model('user');
	if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
		$user_id = $this->session->userdata('user')['user_id'];
    //get user's unread notification count
    $data['count'] = $this->user->get_notification_count($user_id);
    //get publishers id user has added them for same
		$data['removepublisher'] =  $this->user->get_publisher($user_id);
		$data['meta_title'] = 'Add publisher';
		$data['main_content'] = 'notify';
		$this->load->view('includes/templates', $data);
	}else{
		redirect(base_url(), 'refresh');
	}
}
/**
 * [remove_subscriber used to get all subscribes's id user has added them as subscribes]
 * @return [void] []
 * load notify page with remove subscribes functionlity
 */
public function remove_subscriber(){
	$this->load->helper('url');
	$this->load->model('user');
	if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
		$user_id = $this->session->userdata('user')['user_id'];
    //get user's unread notification count
    $data['count'] = $this->user->get_notification_count($user_id);
    //get subscribes id user has added them for same
		$data['removesubscriber'] =  $this->user->get_subscriber($user_id);
		$data['meta_title'] = 'Add publisher';
		$data['main_content'] = 'notify';
		$this->load->view('includes/templates', $data);
	}else{
		redirect(base_url(), 'refresh');
	}
}
/**
 * [add_subscriber used to get all users's id user wants to add them as subscribers]
 * @return [void] []
 * load notify page with add subscribes functionlity
 */
public function add_subscriber(){
	$this->load->helper('url');
	$this->load->model('user');
	if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
		$user_id = $this->session->userdata('user')['user_id'];
    $data['count'] = $this->user->get_notification_count($user_id);
    //get users id user added them for same
		$data['addsubscriber'] =  $this->user->get_subscriber($user_id);
		$data['meta_title'] = 'Add publisher';
		$data['main_content'] = 'notify';
		$this->load->view('includes/templates', $data);
	}else{
		redirect(base_url(), 'refresh');
	}
}
/**
 * [add_notify function to add users as his/her publisher or subscribers based on type received from ajax input]
 * input - [array] user's id
 * input - [string] type (subscriber or publisher)
 */
public function add_notify(){
  $this->load->model('user');
  //get users' id
	$data = $_POST['array'];
  //get type of users want to add them as
  $notify_type = $_POST['type'];
  if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
		 $user_id = $this->session->userdata('user')['user_id'];
     // add user as their subscriber or publisher based upon type
     $result = $this->user->addnotifyer($data,$user_id,$notify_type);
}
}
/**
 * [remove_notify function to remove users as his/her publisher or subscribers based on type received from ajax input]
 * input - [array] user's id
 * input - [string] type (subscriber or publisher)
 */
public function remove_notify(){
  $this->load->model('user');
  $data = $_POST['array'];
  $notify_type = $_POST['type'];
  if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
     $user_id = $this->session->userdata('user')['user_id'];
     // remove user as their subscriber or publisher based upon type
     $result = $this->user->removenotifyer($data,$user_id,$notify_type);
}
}
/**
 * [get_notification_count gets the unread count of user notification]
 * @return [json] [response with notification count and success]
 */
public function get_notification_count(){
  $this->load->helper('url');
  $this->load->model('user');
  if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
    $user_id = $this->session->userdata('user')['user_id'];
    $result = $this->user->get_notification_count($user_id);

    $response = array(
                       'success' => true,
                       'data' => $result,
                       'code' => 200
                   );
    echo json_encode($response);
  }else{
    redirect(base_url(), 'refresh');
  }
}

/**
 * [notification gets the latest read unread notification of users]
 * @return [void]
 * load notification page with notification data
 */
public function notification(){
  $this->load->helper('url');
  $this->load->model('user');
  if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
    $user_id = $this->session->userdata('user')['user_id'];
    //set notification as read when user load this page
    $this->user->remove_notification_count($user_id);
    //get latest notification of user call model function get_all_notification
    $data['result'] = $this->user->get_all_notification($user_id);
    $data['meta_title'] = 'Add publisher';
		$data['main_content'] = 'notification';
    //load view with notification page
		$this->load->view('includes/templates', $data);
  }else{
    redirect(base_url(), 'refresh');
  }
}
/**
 * [update function loads the update page where user can update his/her info
 * @return [void]
 * load update page
 */
public function update(){
  $this->load->helper('url');
  $this->load->model('user');
	if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0){
    $user_id = $this->session->userdata('user')['user_id'];
    $data['count'] = $this->user->get_notification_count($user_id);
    $data['meta_title'] = 'Add publisher';
		$data['main_content'] = 'update';
		$this->load->view('includes/templates', $data);
	}else{
		redirect(base_url(), 'refresh');
	}
}
/**
 * [change_user_info responsible for changing user info like user name and password depeneds upon what pg_parameter_status
 * has been passed to it
 * @return [void]
 * input - [string] username or password
 */
public function change_user_info(){
  $this->load->helper('url');
	$this->load->model('user');
  $user_id = $this->session->userdata('user')['user_id'];
  if(isset($_POST['username']) && !empty($_POST['username'])){
    //get user name from ajax
    $username = $_POST['username'];
    //upadte user name call change_user_name function
    $result = $this->user->change_user_name($user_id,$username);
  }
  else if(isset($_POST['password']) && !empty($_POST['password'])){
      //get user passeord from ajax
    $password = $_POST['password'];
    //upadte user password call change_user_password function
    $result = $this->user->change_user_password($user_id,$password);
  }
}
/**
 * [delete_account function deletes the account of user]
 * @return [void]
 */
public function delete_account(){
  $this->load->helper('url');
	$this->load->model('user');
  $user_id = $this->session->userdata('user')['user_id'];
  //delete user's account call user model's delete_account 
  $result = $this->user->delete_account($user_id);
  $this->session->unset_userdata('user');
  redirect(base_url(), 'refresh');
}
}
