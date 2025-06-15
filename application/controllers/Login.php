<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
 
        // Example to set a cookie with SameSite=Strict
                // Disable PHPSESSID cookie
                ini_set('session.use_cookies', 0);
                ini_set('session.use_only_cookies', 0);
    setcookie(
        "PHPSESSID",    // Cookie name
        session_id(),    // Session ID
        [
            'expires' => time() + 3600,   // Cookie expiration time (1 hour from now)
            'path' => '/',                // Cookie path
            'domain' => 'trips.hed.kerala.gov.in',   // Domain for the cookie
            'secure' => true,             // Set to true for HTTPS
            'httponly' => true,           // Prevent access via JavaScript
            'samesite' => 'Strict'        // Set the SameSite attribute
        ]
    );
    setcookie(
        "ci_session",    // Cookie name
        session_id(),    // Session ID
        [
            'expires' => time() + 3600,   // Cookie expiration time (1 hour from now)
            'path' => '/',                // Cookie path
            'domain' => 'trips.hed.kerala.gov.in',   // Domain for the cookie
            'secure' => true,             // Set to true for HTTPS
            'httponly' => true,           // Prevent access via JavaScript
            'samesite' => 'Strict'        // Set the SameSite attribute
        ]
    );
    // setcookie(
    //     "ci_csrf_cookie",    // Cookie name
    //     session_id(),    // Session ID
    //     [
    //         'expires' => time() + 3600,   // Cookie expiration time (1 hour from now)
    //         'path' => '/',                // Cookie path
    //         'domain' => 'test.hedkerala.in',   // Domain for the cookie        
    //         'secure' => true,             // Set to true for HTTPS
    //         'httponly' => true,           // Prevent access via JavaScript
    //         // 'samesite' => 'Strict'        // Set the SameSite attribute
    //     ]
    // );
    session_start();
        $method = $this->router->fetch_method();

        if($this->session->has_userdata('harbour') && $method != 'logout') {
            $role_id = $_SESSION['harbour']['role_id'];
            redirect($role_id);
        } 
    }

    public function index() {
        $this->load->helper('captcha');

        // Determine the OS and set the path accordingly
        $x = substr(PHP_OS, 0, 3);
        $path = str_replace("application/controllers","",__DIR__);

        if($x == 'Dar' || $x == 'WIN') {
            $path = str_replace("application\\controllers","",__DIR__);
            $url = base_url() . 'public/captcha/';
            $path = $path . '/public/captcha/';
        } else {
            $url = base_url() . 'captcha/';
            $path = $path . 'captcha/';
        }

        // Configure captcha settings
        $vals = array(
            'word'          => rand(10000,99999),
            'img_path'      => $path,
            'img_url'       => $url,
            'img_width'     => '200',
            'img_height'    => '50',
            'expiration'    => 7200,
            'font_size'     => 20,
            'img_id'        => 'captcha_image',
            'colors'        => array(
                'background' => array(52,58,64),
                'border'     => array(52,58,64),
                'text'       => array(255, 255, 255),
                'grid'       => array(52,58,64)
            )
        );

        // Create the captcha
        $cap = create_captcha($vals);

        // If captcha creation failed, log error and show a message
        if (!$cap) {
            $data['captcha_error'] = "Captcha creation failed. Please try again.";
        } else {
            // Store captcha data in the database
            $data = array(
                'captcha_time'  => $cap['time'],
                'ip_address'    => $this->input->ip_address(),
                'word'          => $cap['word']
            );

            $this->db->insert('captcha', $data);
            
            // Pass the captcha image and word to the view
            $data['captcha'] = $cap['image'];
            $data['captcha_value'] = $cap['time'];
        }

        $this->load->view('login', $data);
    }

    public function login_post() {
      $this->load->library('form_validation');
  
      $this->form_validation->set_rules('user', 'User Name', 'required');
      $this->form_validation->set_rules('password', 'Password', 'required');
      $this->form_validation->set_rules('time', 'Time', 'required');
      $this->form_validation->set_rules('recaptcha', 'Captcha', 'required|numeric|exact_length[5]');
  
      if ($this->form_validation->run() == TRUE) {
          $post = $this->input->post(array('user','password','recaptcha','time'), TRUE);
  
          // Verify captcha
          $capc = $this->db->where(array(
              'captcha_time' => $post['time'],
              'word' => $post['recaptcha']
          ))->get('captcha')->row();
  
          if($capc) {
              // Delete the captcha after successful verification
              $this->db->delete('captcha', array('captcha_time' => $post['time'], 'word' => $post['recaptcha']));
  
              // Proceed with user authentication
              $user_info = $this->db->get_where('user', array('name' => $post['user'], 'status' => '1'))->row();
  
              if($user_info && password_verify($post['password'], $user_info->password)) {
  
                  // Check for active sessions
                  $active_session = $this->db->get_where('user_sessions', array(
                      'user_id' => $user_info->user_id,
                      'status' => 'valid'
                  ))->row();

                  if ($active_session) {
                      // Option 1: Invalidate existing session and allow the new one
                      $this->db->update('user_sessions', array('status' => 'invalid'), array('id' => $active_session->id));
  
                      // OR
  
                      // Option 2: Deny the new login attempt
                      $this->session->set_flashdata('status', "You are already logged in from another device or session.");
                      redirect($_SERVER['HTTP_REFERER']);
                      return;
                  }
  
                  // Insert new session record
                  $session_id = session_id();
                  $session_data = array(
                      'user_id' => $user_info->user_id,
                      'session_id' => $session_id,
                      'created_at' => date('Y-m-d H:i:s'),
                      'status' => 'valid'
                  );
                  $this->db->insert('user_sessions', $session_data);
  
                  // Set session data
                  $session = (array)$user_info;
                  unset($session['password']);
                  $this->session->set_userdata('harbour', $session);
  
                  // Redirect based on role
                  redirect($user_info->role_id);
              } else {
                  $this->session->set_flashdata('status', "Invalid Username or Password.");
                  redirect($_SERVER['HTTP_REFERER']);
              }
          } else {
              $this->session->set_flashdata('status', "Invalid Captcha.");
              redirect($_SERVER['HTTP_REFERER']);
          }
      } else {
          echo validation_errors('<h1 class="error">', '</h1>');
      }
  }
  public function logout() {
    $session_data = $this->session->userdata('harbour'); // Replace 'harbour' with your session key

    if ($session_data) {
        $this->db->where('user_id', $session_data['user_id']);
        $this->db->where('session_id', session_id());
        $this->db->update('user_sessions', array('status' => 'invalid'));
    }

    // Destroy the session
    $this->session->unset_userdata('harbour');
    $this->session->sess_destroy();

    // Redirect to login page or home page
    redirect('login'); // Adjust to your actual route
}


    // New Method to change password and invalidate sessions
    public function change_password() {
      var_dump($this->input->post());
    exit;
      $this->load->library('form_validation');
  
      // Add password complexity rules
      $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]|callback_password_complexity_check');
      $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
  
      if ($this->form_validation->run() == TRUE) {
          $post = $this->input->post(array('new_password'), TRUE);
          
          // Assuming the user is already authenticated and you have user_id in session
          $user_id = $this->session->userdata('harbour')['user_id'];
  
          // Update the user's password
          $this->db->where('user_id', $user_id);
          $this->db->update('user', array('password' => password_hash($post['new_password'], PASSWORD_DEFAULT)));
          
          // Invalidate all sessions for this user
          $this->db->where('user_id', $user_id);
          $this->db->update('user_sessions', array('status' => 'invalid'));
  
          // Destroy the current session
          $this->session->sess_destroy();
          redirect('login', 'refresh');
      } else {
          $this->load->view('change_password');
      }
  }
  
  public function password_complexity_check($password) {
    // Debug statement to confirm the function is called
    var_dump($password);
    exit;
      if (!preg_match('/[a-z]/', $password)) {
          $this->form_validation->set_message('password_complexity_check', 'The {field} must include at least one lowercase letter.');
          return FALSE;
      }
      if (!preg_match('/[A-Z]/', $password)) {
          $this->form_validation->set_message('password_complexity_check', 'The {field} must include at least one uppercase letter.');
          return FALSE;
      }
      if (!preg_match('/\d/', $password)) {
          $this->form_validation->set_message('password_complexity_check', 'The {field} must include at least one number.');
          return FALSE;
      }
      if (!preg_match('/[@$!%*?&]/', $password)) {
          $this->form_validation->set_message('password_complexity_check', 'The {field} must include at least one special character (@$!%*?&).');
          return FALSE;
      }
      return TRUE;
  }
  
  
  
  
  
    // New Method to update role and invalidate sessions
    public function update_role($user_id, $new_role) {
        // Update role in the database
        $this->db->where('user_id', $user_id);
        $this->db->update('user', array('role_id' => $new_role));

        // Invalidate all sessions for this user
        $this->db->delete('ci_sessions', array('user_id' => $user_id));

        // Redirect or notify the user to log in again
        redirect('login', 'refresh');
    }

    // New Method to deactivate account and invalidate sessions
    public function deactivate_account($user_id) {
        // Deactivate account in the database
        $this->db->where('user_id', $user_id);
        $this->db->update('user', array('status' => '0'));

        // Invalidate all sessions for this user
        $this->db->delete('ci_sessions', array('user_id' => $user_id));

        // Destroy the current session if the logged-in user is the same
        if ($this->session->userdata('harbour')['user_id'] == $user_id) {
            $this->session->sess_destroy();
        }
        
        redirect('login', 'refresh');
    }
}
