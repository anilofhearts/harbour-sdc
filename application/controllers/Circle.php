<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Circle extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'circle') {
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
        $this->load->view('circle/navbar');


        // $this->load->helper('form');
        // $this->load->model('Master');
        header("Access-Control-Allow-Origin: *");
        // print_r($_SESSION['harbour']);
    }

    public function index()
    {
        $divisions = $this->manager->get_details('division', array('circle_id' => $this->user['section_id']));
        
        $i = 0;
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
        $circle = $this->manager->get_details('circle', array('circle_id'=>$this->user['section_id']));
        // $subUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$subdivision[0]->subdivision_id));
        // $divUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$division[0]->division_id));
        // $cirUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$circle[0]->circle_id));
        $ce = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$circle[0]->state_id));

        $divisions = $this->manager->get_details('division', array('circle_id'=>$this->user['section_id']));
        
        
      //  print_r($subdivisions);
        $users = array();
        
        foreach($divisions as $div_data) {
             $subdivisions = $this->manager->get_details('subdivision', array('division_id'=>$div_data->division_id));
            
            $usrs = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$div_data->division_id));
            foreach($usrs as $usr) {
                array_push($users, array(
                    'user_id' => $usr->user_id,
                    'section_id' => $div_data->division_id,
                    'division' => $div_data->division,
                    'name' => $usr->name,
                    'fullname' => $usr->fullname,
                    'designation_id' => $usr->designation_id,
                    'designation' => $usr->designation,
                    'email_id' => $usr->email_id,
                    'phone_no' => $usr->phone_no,
                ));
                foreach($subdivisions as $sub_v)
                {
         $usrs_new = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$sub_v->subdivision_id));
            foreach($usrs_new as $usr_z) {
                array_push($users, array(
                    'user_id' =>$usr_z->user_id,
                    'section_id' => $sub_v->subdivision_id,
                    'division' =>$div_data->division."=>".$sub_v->subdivision,
                    'name' =>$usr_z->name,
                    'fullname' => $usr_z->fullname,
                    'designation_id' => $usr_z->designation_id,
                    'designation' => $usr_z->designation,
                    'email_id' => $usr_z->email_id,
                    'phone_no' => $usr_z->phone_no,
                ));

            }
                }

            } 
        }
        $i = 0;
        while ( $i < count($subdivisions)) {
            $i++;
        }


        $data = array(
            'user' => $user,
            'users' => $users,
            'divisions' => $divisions,
            // 'division' => $division,
            'circle' => $circle,
            // 'subUser' => $subUser,
            // 'divUser' => $divUser,
            'ce' => $ce,
            'designation' => $this->manager->get_details('designation', array())
        );
        //$user = ;
        $this->load->view('circle/user', ['data'=>$data]);
        $this->load->view('footer');
    }
    */
    public function change_password()
    {
        $this->change_user_password();
        $this->self();
    }

    public function reset_password()
    {
        $this->reset_user_password();
        $this->self();
    }

    public function add_edit_user()
    {
        $this->add_edit_user_profile('division');
        $this->self();
    }


}
