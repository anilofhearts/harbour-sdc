<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cards extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'section') {
            redirect($role_id,'refresh');
        }
/*
        $this->user['section_id'] = $_SESSION['harbour']['section_id'];
        $this->user['user_id'] = $_SESSION['harbour']['user_id'];
        // $this->load->model('manager');

        $userinfo = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('user_id'=>$this->user['user_id']))[0];
        //$user = $userinfo['name'];
        $this->load->view('header', ['userinfo'=>$userinfo]);
        */
      $this->load->view('section/navbar');

        // $this->load->helper('form');
        $this->load->model('Master');
        header("Access-Control-Allow-Origin: *");
        // print_r($_SESSION['harbour']);
    }

    public function weightmentCard($trip_id)
    {
        $section_id = $this->user['section_id'];
        // $short_code = $this->manager->get_details('section', array('section_id'=>$section_id))[0]->short_code;
        $data = array(
            'section' => $this->manager->get_details('section', array('section_id'=>$this->user['section_id'])),
            'trip' => $this->manager->get_trip(array('trip_id' => $trip_id)),
            'agreement' => $this->manager->get_details('agreement', array('section_id'=>$this->user['section_id'])),
            // 'short_code' => $short_code
            );
        //$trip = $this->manager->get_trip(array('trip_id' => $trip_id));
        $this->load->view('cards/weightmentCard', ['data'=>$data]);
        // $this->load->view('footer');
    }

    public function tripCard($trip_id)
    {
        $section_id = $this->user['section_id'];
        // $short_code = $this->manager->get_details('section', array('section_id'=>$section_id))[0]->short_code;
        $data = array(
            'section' => $this->manager->get_details('section', array('section_id'=>$this->user['section_id'])),
            'trip' => $this->manager->get_trip(array('trip_id' => $trip_id)),
            'agreement' => $this->manager->get_details('agreement', array('section_id'=>$this->user['section_id']))
            // 'short_code' => $short_code
            );
        //$trip = $this->manager->get_trip(array('trip_id' => $trip_id));
         //$this->load->view('header');
        $this->load->view('cards/tripCard', ['data'=>$data]);
         $this->load->view('footer');
    }

} ?>
