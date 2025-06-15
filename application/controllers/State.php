<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class State extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'state') {
            redirect($role_id,'refresh');
        }
/*
        $this->user['section_id'] = $_SESSION['harbour']['section_id'];
        $this->user['user_id'] = $_SESSION['harbour']['user_id'];
        $this->load->model('Manager');

        $userinfo = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('user_id'=>$this->user['user_id']))[0];
        //$user = $userinfo['name'];
        $this->load->view('header', ['userinfo'=>$userinfo]);
*/

        $this->load->view('state/navbar');


        // $this->load->helper('form');
        // $this->load->model('Master');
        header("Access-Control-Allow-Origin: *");
        // print_r($_SESSION['harbour']);
    }

    public function index()
    {
        $circles = $this->manager->get_details('circle', array('state_id' => $this->user['section_id']));
        $i = 0;

        foreach($circles as $circle) {

            $divisions = $this->manager->get_details('division', array('circle_id' => $circle->circle_id));

            if(isset($divisions)){
            foreach($divisions as $division) {

                $subdivisions = $this->manager->get_details('subdivision', array('division_id'=>$division->division_id));
                
                if (isset($subdivisions)) {
                    foreach($subdivisions as $subdivision) {

                        $sections = $this->manager->get_details('section', array('subdivision_id'=>$subdivision->subdivision_id));

                        if(isset($sections)) {
                            foreach($sections as $section) {
                                $data['bricks'][$i] = $this->dash_bricks($section->section_id);
                                $i++;
                            }
                        }
                    }
                }
            } 
            }
        }

        $this->load->view('dashboard', $data);
        $this->load->view('footer');
    }

    public function profile()
    {
        $this->self();
    }
/*
    public function user()
    {
        $user = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$this->user['section_id']));
        // $subdivision = $this->manager->get_details('subdivision', array('subdivision_id'=>$this->user['section_id']));
        // $division = $this->manager->get_details('division', array('division_id'=>$this->user['section_id']));
        $state = $this->manager->get_details('state', array('state_id'=>$this->user['section_id']));
        // $subUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$subdivision[0]->subdivision_id));
        // $divUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$division[0]->division_id));
        // $cirUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$circle[0]->circle_id));

        $circles = $this->manager->get_details('circle', array('state_id'=>$this->user['section_id']));
        $users = array();
        $i = 0;
        while ( $i < count($circles)) {
            $usrs = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$circles[$i]->circle_id));
            foreach($usrs as $usr) {
                array_push($users, array(
                    'user_id' => $usr->user_id,
                    'section_id' => $circles[$i]->circle_id,
                    'circle' => $circles[$i]->circle,
                    'name' => $usr->name,
                    'fullname' => $usr->fullname,
                    'designation_id' => $usr->designation_id,
                    'designation' => $usr->designation,
                    'email_id' => $usr->email_id,
                    'phone_no' => $usr->phone_no,
                ));

            } $i++;
        }

        $data = array(
            'user' => $user,
            'users' => $users,
            'circles' => $circles,
            // 'division' => $division,
            'state' => $state,
            // 'subUser' => $subUser,
            // 'divUser' => $divUser,
            // 'cirUser' => $cirUser,
            'designation' => $this->manager->get_details('designation', array())
        );
        //$user = ;
        $this->load->view('state/user', ['data'=>$data]);
        $this->load->view('footer');
    }
*/
    public function change_password()
    {
        $this->change_user_password();
        $this->self();
         // Get the user ID of the currently logged-in user
   // $user_id = $this->session->userdata('user_id');

    // Invalidate user sessions
   // $this->invalidate_user_sessions($user_id);
// Update session status to 'invalid'
     $this->logout_after_password_change();

    // Redirect to the login page or the appropriate page
   // redirect('login', 'refresh'); // Adjust the redirection URL as needed
        // Get the user ID of the currently logged-in user
      
    }
    
    protected function logout_after_password_change() {
        // Get the user ID of the currently logged-in user
        $user_id = $this->session->userdata('harbour')['user_id'];
        $this->manager->update_session_status($user_id, 'invalid', true);
      
        // Invalidate all sessions associated with the user
      //  $this->invalidate_user_sessions($user_id);
    
        // Unset and destroy the current session
        $this->session->sess_destroy();
        $this->session->unset_userdata('harbour');
    // Update session status to 'invalid'
    $this->manager->update_session_status($user_id, 'invalid');

        // Redirect to the login page or the appropriate page
        redirect('login', 'refresh'); // Adjust the redirection URL as needed
    }
    private function invalidate_user_sessions($user_id) {
        // Invalidate all sessions associated with the user in your sessions table
        $this->manager->where('user_id', $user_id)->update('user_sessions', array('status' => 'invalid'));
    }
  
    public function reset_password()
    {
        $this->reset_user_password();
        $this->self();
    }

    public function add_edit_user()
    {
        $this->add_edit_user_profile('circle');
        $this->self();
    }

}
