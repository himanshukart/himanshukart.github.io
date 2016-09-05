<?php

/**
 * Class User
 * @property \Users
 * @property \Mysql  db
 */
Class User extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * add User in database
     * @param string $username
     * @param string $email
     * @param string $password
     * @return int(user_id)
     */
    public function addUser($username='',$email='', $password='')
    {
      try{
      //check if email is already registered
       $user_id = $this->check_email_exists($email);
       //check if email does not exists insert into database
       if($user_id){
        $this->session->set_flashdata('email_exists', 'Email address is already exists');
       }else{
         $user = array(
         'user_name' => $username,
         'email' => $email,
         'password' => $password,
         'active' => 1,
         'date_added' => date("Y-m-d H:i:s", time()),
         'date_modified' => date("Y-m-d H:i:s", time())
       );
       $this->db->insert('users', $user);
       //set flash session for successfully registration  message to user
       $this->session->set_flashdata('registered', 'Successfully registered');
       $id = $this->db->insert_id();
       //get all other's user id
       $users_arary = $this->get_all_user($id);
       //set all users as his/her publisher and subscribers  by default
       $this->new_user_notifyer($id,$users_arary);
       //set notification with registration message
       $this->set_notification($id,''.$username. ' has created new account');
       //when successfully login set session data
       $data = array(
                'username' => $username,
                'email' => $email,
                'user_id' => $id,
                'is_logged_in' => true
            );

       $this->session->set_userdata('user',$data);
       return $id;
       }
     }catch (Exception $e) {
				//echo json_encode($e->getMessage());die;
				log_message('error', json_encode($e->getMessage()));
				show_error('Sorry, this is embarrassing. We are on working hard to get it back online.', 500);
			}

    }
  /**
   * [get_all_user get's all active users except the user in session ]
   * @param  [type] $user_id [user id who is getting this info]
   * @return [array] rows         [array of users id]
   */
    private function get_all_user($user_id){
      $query = $this->db->select(array('id'))->from('users')->
      where(array('id !=' => (int) $user_id,'active'=>1))->get();
      $rows = $query->result_array();
      return $rows;
    }
    /*
     * check email already exists in database
     * @param string $email
     * @return int(user_id)
     */
    private function check_email_exists($email){
      $query = $this->db->select('id')->from('users')->where('email', $email)->get();
      $id = $query->row_array()['id'];
      return $id;
    }
    /**
     * [validate_user check the correct user detail provided by user for login]
     * @param  [type] $email    [user email]
     * @param  [type] $password [user password]
     * @return [void] if success else flash message with  'Invalid Login details' message
     */
    public function validate_user($email,$password){
      try{
      //get user info with provided user email and password
      $this->db->select('id, email, user_name');
          $this->db->where('email', $email);
          $this->db->where('password', $password);
          $this->db->where('active', 1);
          $query = $this->db->get('users');
          $data = $query->row_array();
          //if user found set seesion and login else redirect to same message 'Invalid detail' with flash
          if(!empty($data)){
            $data = array(
                     'username' => $data['user_name'],
                     'email' => $data['email'],
                     'user_id' => $data['id'],
                     'is_logged_in' => true
                 );

           $this->session->set_userdata('user', $data);
          }else{
            /*
              Flash is CI function which send data only for single request and for one time
             */
            $this->session->set_flashdata('invalid_login', 'Invalid Login details');
          }
        }catch (Exception $e) {
				//echo json_encode($e->getMessage());die;
				log_message('error', json_encode($e->getMessage()));
				show_error('Sorry, this is embarrassing. We are on working hard to get it back online.', 500);
			}
    }
/**
 * [change_user_name for change the user's own name]
 * @param  [type] $user_id  [user id]
 * @param  [type] $username [user name to be set]
 * @return [void]
 */
public function change_user_name($user_id,$username){
  //set arary with new user name and current time
  $data = array(
                 'user_name' => $username,
                 'date_modified' => date("Y-m-d H:i:s", time())
              );

    $this->db->where('id', $user_id);
    $this->db->update('users', $data);
    //update username and set new name intioo session
    $session_data = $this->session->userdata('user');
    $session_data['username'] = "$username";
    $this->session->set_userdata("user", $session_data);
    $user_email = $this->session->userdata('user')['email'];
    $this->set_notification($user_id,''.$user_email. ' has changed his user name');
}
/**
 * [change_user_password for change the user's own password]
 * @param  [type] $user_id  [user id]
 * @param  [type] $username [user name to be set]
 * @return [void]
 */
public function change_user_password($user_id,$password){
  //set arary with new user password and current time
  $data = array(
                 'password' => $password,
                 'date_modified' => date("Y-m-d H:i:s", time())
              );
    $this->db->where('id', $user_id);
    //update password
    $this->db->update('users', $data);
    $user_email = $this->session->userdata('user')['email'];
    $this->set_notification($user_id,''.$user_email. ' has changed his password');
}
/**
 * check whether other users are publishers or not
 * @param  [int] $user_id [user_id logged in]
 * @return [array] [user id with publishers or not publishers info]
 */
    public function get_publisher($user_id){
      try{
      //get all user's id except this user which are active
      $query = $this->db->select(array('id','user_name'))->from('users')->
      where(array('id !='=> (int) $user_id,'active'=>1))->get();
      $rows = $query->result_array();
      //get the user's already publishers
      $sql  =   $this->db->select('publishers_id')->from('publishers')->where('user_id', $user_id)->get();
      $array = array();
      //get user's publisher list
      foreach($sql->result() as $row)
      {
          $array[] = $row->publishers_id;; // add each user id to the array
      }
      //set that other user's are publisher or not
      foreach($rows as $key => $value){
        if (in_array($value['id'], $array)) {
                $rows[$key]['publisher'] = 1;
        }else{
            $rows[$key]['publisher'] = 0;
        }
      }
      return $rows;
    }catch (Exception $e) {
				//echo json_encode($e->getMessage());die;
				log_message('error', json_encode($e->getMessage()));
				show_error('Sorry, this is embarrassing. We are on working hard to get it back online.', 500);
			}
    }

    /**
     * check whether other users are subscriber or not
     * @param  [int] $user_id [user_id logged in]
     * @return [array] [rows of users with subscriber or not info]
     */
        public function get_subscriber($user_id){
          try{
          //get all user's id except this user
          $query = $this->db->select(array('id','user_name'))->from('users')->
          where(array('id !='=> (int) $user_id,'active'=>1))->get();
          $rows = $query->result_array();
          //get the user's already publishers
          $sql  =   $this->db->select('subscriber_id')->from('subscribers')->where('user_id', $user_id)->get();
          $array = array();
          //get user's publisher list
          foreach($sql->result() as $row)
          {
              $array[] = $row->subscriber_id;; // add each user id to the array
          }
          //set that other user's are publisher or not
          foreach($rows as $key => $value){
            if (in_array($value['id'], $array)) {
                    $rows[$key]['subscriber'] = 1;
            }else{
                $rows[$key]['subscriber'] = 0;
            }
          }
          return $rows;
        }catch (Exception $e) {
				//echo json_encode($e->getMessage());die;
				log_message('error', json_encode($e->getMessage()));
				show_error('Sorry, this is embarrassing. We are on working hard to get it back online.', 500);
			}
        }
    /**
     * [new_user_notifyer function to set other users as publisher or subscriber baseed upon
     * type when user register him/her self]
     * @param  [type] $user_id [user id ]
     * @param  [type] $users   [users array has to be set as publisher or subscriber]
     * @return [void]
     */
    private function new_user_notifyer($user_id,$users){
      try{
      $users_id = array();
      //get users array and implode
      foreach($users as $key => $value){
        $users_id[] = implode('',$users[$key]);
      }
      //insert subscribers
      foreach($users_id as $key => $value){
        $data = array(
        'user_id' => $user_id,
        'subscriber_id'=>$value,
        'date_added' => date("Y-m-d H:i:s", time()),
        'date_modified' => date("Y-m-d H:i:s", time())
      );
        $this->db->insert('subscribers', $data);
      }
      $user_email = $this->session->userdata('user')['email'];
      //set notification with below messgae
      $this->set_notification($user_id,''.$user_email. ' has added his subscribers');
      //insert publishers
      foreach($users_id as $key => $value){
        $data = array(
        'user_id' => $user_id,
        'publishers_id'=>$value,
        'date_added' => date("Y-m-d H:i:s", time()),
        'date_modified' => date("Y-m-d H:i:s", time())
      );
        $this->db->insert('publishers', $data);
      }
      //set notification with below messgae
      $this->set_notification($user_id,''.$user_email. ' has added his publisher');
    }catch (Exception $e) {
				//echo json_encode($e->getMessage());die;
				log_message('error', json_encode($e->getMessage()));
				show_error('Sorry, this is embarrassing. We are on working hard to get it back online.', 500);
			}
    }
    /**
     * [addnotifyer function is to add users as user's publishers or subscribers based upon their $type]
     * @param  [array]  $users [users array to be set as publishers or subscribers]
     * @param [int]   user id
     * @param [string] type (publisher or subscriber)
     * @return [type]        [type subscribers or publishers]
     */
    public function addnotifyer($users = array(),$user_id,$type){
      try{
      //check if type is publisher add user array id as publisher
      if($type === 'publishers' ){
        //get all user id from array and add as publisher
        foreach($users as $value){
          $subscribers = array(
          'user_id' => $user_id,
          'publishers_id'=>$value,
          'date_added' => date("Y-m-d H:i:s", time()),
          'date_modified' => date("Y-m-d H:i:s", time())
        );
          $this->db->insert($type, $subscribers);
        }
        $user_email = $this->session->userdata('user')['email'];
        //set notification that user has added following publishers
        $this->set_notification($user_id,''.$user_email. ' has added his publishers');
      }
      //check if type is subscriber add user array id as subscriber
      else{
        //get all user id from array and add as subscriber
        foreach($users as $value){
          $subscribers = array(
          'user_id' => $user_id,
          'subscriber_id'=>$value,
          'date_added' => date("Y-m-d H:i:s", time()),
          'date_modified' => date("Y-m-d H:i:s", time())
        );
          $this->db->insert($type, $subscribers);
        }
        $user_email = $this->session->userdata('user')['email'];
        //set notification that user has added following subscriber
        $this->set_notification($user_id,''.$user_email. ' has added his subscribers');
      }
        return true;
      }catch (Exception $e) {
				//echo json_encode($e->getMessage());die;
				log_message('error', json_encode($e->getMessage()));
				show_error('Sorry, this is embarrassing. We are on working hard to get it back online.', 500);
			}
    }
/**
 * [removenotifyer function to remove subscribers or publishers from user feed if
 *  user wants the same and based upon type parameter passed ]
 * @param  array  $users [users array to be removed as publishers or subscribers]
 * @param [int]   user id
 * @param [string] type (publisher or subscriber)
 * @return [boolean]        [true or false]
 */
  public function removenotifyer($users = array(),$user_id,$type){
    try{
    //check if type is publishers remove user array id as publishers
    if($type === 'publishers' ){
      //get all user id from array and remove as publishers from user list
      foreach($users as $value){
        $this->db->where('user_id', $user_id);
        $this->db->where('publishers_id', $value);
        $this->db->delete($type);
      }
      $user_email = $this->session->userdata('user')['email'];
      //set notification that user has removed following publishers
      $this->set_notification($user_id,''.$user_email. ' has removed his publishers');
    }
    else{
      //check if type is publishers remove user array id as subscribers
      foreach($users as $value){
        //get all user id from array and remove as subscribers from user list
        $this->db->where('user_id', $user_id);
        $this->db->where('subscriber_id', $value);
        $this->db->delete($type);
      }
      $user_email = $this->session->userdata('user')['email'];
        //set notification that user has removed following subscribers
      $this->set_notification($user_id,''.$user_email. ' has removed his subscribers');
    }
      return true;
    }catch (Exception $e) {
				//echo json_encode($e->getMessage());die;
				log_message('error', json_encode($e->getMessage()));
				show_error('Sorry, this is embarrassing. We are on working hard to get it back online.', 500);
			}
  }

/**
 * [set_notification function is to set notification on base of user interset]
 * @param [int] $user_id [user who wants to receive information]
 * @param [string] $message [message to be set as notification]
 */
  private function set_notification($user_id,$message){
    try{
    //intilizing arrays
    $publishers_array  =  array();
    $subscribers_array =  array();
    //get user's publishers array who wants to receieve information form him/her self
    $query = $this->db->select(array('user_id'))->from('publishers')->where('publishers_id', (int) $user_id)->get();
    foreach($query->result() as $rows)
    {
        $publishers_array[] = $rows->user_id; // add each user id to the array
    }
    //get user's subscribers to whom user wants to send notification
    $sql  =   $this->db->select('subscriber_id')->from('subscribers')->where('user_id', $user_id)->get();

    foreach($sql->result() as $row)
    {
        $subscribers_array[] = $row->subscriber_id; // add each user id to the array
    }
    //get the common users from publishers and subscribers where notification will be inserted
    //array_intersect() ** expect two parameter as array and return common array value
    $result = array_intersect($publishers_array, $subscribers_array);
    foreach ($result as $key => $value) {
      $data = array(
      'user_id' => $user_id,
      'notification_user_id'=>$value,
      'message'=>$message,
      'read_status'=>0,
      'date_added' => date("Y-m-d H:i:s", time()),
      'date_modified' => date("Y-m-d H:i:s", time())
    );
    //insert notification
      $this->db->insert('notification', $data);
    }
}catch (Exception $e) {
				//echo json_encode($e->getMessage());die;
				log_message('error', json_encode($e->getMessage()));
				show_error('Sorry, this is embarrassing. We are on working hard to get it back online.', 500);
			}
  }
/**
 * [delete_account function to delete(set inactive to users)]
 * @param  [int] $user_id [user who wants to delete delete his/her account]
 * @return [boolean]          [true or false]
 */
public function delete_account($user_id){
  //set user active status as 0
  $data = array(
                 'active' => 0,
                 'date_modified' => date("Y-m-d H:i:s", time())
              );
    $this->db->where('id', $user_id);
    $this->db->update('users', $data);
  $user_email = $this->session->userdata('user')['email'];
  //set notification
  $this->set_notification($user_id,''.$user_email. ' has deleted his account');
  return true;
}
/**
 * [get_notification_count get the unread notification count to user]
 * @param  [int] $user_id [who wants to get the notification count]
 * @return [int]          [count of notification]
 */
public function get_notification_count($user_id){
  //get user's unread notification count
     return  $this->db
       ->where(array('read_status'=>0,'notification_user_id'=>$user_id))
       ->count_all_results('notification');
  }
  /**
   * [get_all_notification gets the all latest 30 notification of user]
   * @param  [int] $user_id [who wants to get the notification count]
   * @return [array]          [notification array]
   */
public function get_all_notification($user_id){
      $notification_array = array();
      $notify = $this->db->select(array('message','date_added'))->from('notification')
      ->where(array('notification_user_id'=>$user_id))
      ->limit(30)
      ->order_by('date_added','desc')->get();
      $notification_array = $notify->result_array();
      return $notification_array;
  }
/**
 * [remove_notification_count makes the notification as read]
 * @param  [type] $user_id
 * @return [void]
 */
public function remove_notification_count($user_id){
  //set read status of notification as 1
    $data = array(
                 'read_status' => 1,
                 'date_modified' => date("Y-m-d H:i:s", time())
              );

    $this->db->where('notification_user_id', $user_id);
    //update notification table with that count
    $this->db->update('notification', $data);
}
}
