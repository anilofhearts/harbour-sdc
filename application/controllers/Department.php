<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'department') {
            redirect($role_id,'refresh');
        }
/*
        $this->user['section_id'] = $_SESSION['harbour']['section_id'];
        $this->user['user_id'] = $_SESSION['harbour']['user_id'];
        // $this->load->model('Manager');

        $userinfo = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('user_id'=>$this->user['user_id']))[0];
        //$user = $userinfo['name'];
        $this->load->view('header', ['userinfo'=>$userinfo]);
*/
        $this->load->view('department/navbar');

        // $this->load->helper('form');
        // $this->load->model('Master');
        header("Access-Control-Allow-Origin: *");
        // print_r($_SESSION['harbour']);
    }

    public function index()
    {
        $sections = $this->manager->get_details('section', array('section_id'=>$this->user['section_id']));
        
        $x = 0;
        foreach ($sections as $section) {
            $data['bricks'][$x] = $this->dash_bricks($section->section_id);
            $x++;
        }
            
        $this->load->view('dashboard', $data);
        $this->load->view('footer');
    }

    public function profile()
    {
        $this->self();
    }
    /*
    public function index()
    {
        $sections = $this->manager->get_details('section', array('subdivision_id'=>$this->user['section_id']));

        $i=0;
        while ($i < count($sections)) {
            $data['section'][$i] = $sections[$i];
            $data['agreement'][$i] = array();
            $data['stats'][$i] = array();
            $data['est_ttl_cost'][$i] = array();
            $data['ttl_exp'][$i] = array();
            // echo '*sec-'.$data['section'][$i]->section;
            $agr = $this->manager->get_details('agreement', array('section_id'=>$sections[$i]->section_id));
            if (isset($agr)) {
                $data['agreement'][$i] = $agr[0];
            // }
            
            // if (isset($data['agreement'][$i])) {
                // echo '*agr-'.$data['agreement'][$i]->agreement_id;
                $data['stats'][$i] = $this->stats($data['agreement'][$i]->agreement_id);
                $data['est_ttl_cost'][$i] = $this->manager->est_ttl_cost($data['agreement'][$i]->agreement_id);
                $data['ttl_exp'][$i] = $this->manager->ttl_exp($data['agreement'][$i]->agreement_id);
            }
            $i++;
        }
        // exit;
        $this->load->view('subdivision/dashboard', ['data'=>$data]);
        $this->load->view('footer');
    }

    public function dashboard_extended($agreement_id)
    {
        $this->agreement_dashboard($agreement_id);
    }
    
    public function user()
    {
        $user = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$this->user['section_id']));
        $subdivision = $this->manager->get_details('subdivision', array('subdivision_id'=>$this->user['section_id']));
        $division = $this->manager->get_details('division', array('division_id'=>$subdivision[0]->division_id));
        $circle = $this->manager->get_details('circle', array('circle_id'=>$division[0]->circle_id));
        // $subUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$subdivision[0]->subdivision_id));
        $divUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$division[0]->division_id));
        $cirUser = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$circle[0]->circle_id));
        $ce = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$circle[0]->state_id));

        $sections = $this->manager->get_details('section', array('subdivision_id'=>$this->user['section_id']));
        $users = array();
        $i = 0;
        while ( $i < count($sections)) {
            $usrs = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$sections[$i]->section_id));
            foreach($usrs as $usr) {
                array_push($users, array(
                    'user_id' => $usr->user_id,
                    'section_id' => $sections[$i]->section_id,
                    'section' => $sections[$i]->section,
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
            'sections' => $sections,
            'division' => $division,
            'circle' => $circle,
            'divUser' => $divUser,
            'cirUser' => $cirUser,
            'ce' => $ce,
            'designation' => $this->manager->get_details('designation', array())
        );
        //$user = ;
        $this->load->view('subdivision/user', ['data'=>$data]);
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
        $this->user();
    }

    public function add_edit_user()
    {
        $this->add_edit_user_profile('section');
        $this->user();
    }

}
