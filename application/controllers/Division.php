<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Division extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'division') {
            redirect($role_id, 'refresh');
        }

        $this->load->view('division/navbar');
        header("Access-Control-Allow-Origin: *");
    }

    public function index() {
        $subdivisions = $this->manager->get_details('subdivision', array('division_id'=>$this->user['section_id']));
        $data = [];
        $i = 0;
        foreach($subdivisions as $subdivision) {
            $sections = $this->manager->get_details('section', array('subdivision_id'=>$subdivision->subdivision_id));
            foreach($sections as $section) {
                $data['bricks'][$i] = $this->dash_bricks($section->section_id);
                $i++;
            }
        }

        $this->load->view('dashboard', $data);
        $this->load->view('footer');
    }

    public function profile() {
        $this->self();
    }

    public function change_password() {
        $this->load->library('form_validation');

        // Enhanced password validation rules
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]|callback_valid_password');
        $this->form_validation->set_rules('cnf_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() == TRUE) {
            $user_id = $this->session->userdata('harbour')['user_id'];
            $new_password = password_hash($this->input->post('new_password'), PASSWORD_DEFAULT);

            // Invalidate all sessions for this user
            $this->db->delete('ci_sessions', array('user_id' => $user_id));
            $this->db->update('user_sessions', array('status' => 'invalid'), array('user_id' => $user_id));

            // Update password
            $this->manager->update_data('user', array('password' => $new_password), array('user_id' => $user_id));

            // Destroy current session
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        } else {
            $this->self();
        }
    }

    public function reset_password() {
        $this->reset_user_password();
        $this->self();
    }

    public function add_edit_user() {
        $this->add_edit_user_profile('subdivision');
        $this->self();
    }

    public function report() {
        $agreement = $this->manager->get_details('agreement', array('section_id'=>$this->user['section_id']));
        if(isset($agreement)) {
            $where_agr = array('agreement_id'=>$agreement[0]->agreement_id);
            $location = $this->manager->get_details('agreement_location', $where_agr);
            $item = $this->manager->get_details('agreement_item', $where_agr);
            $vehicle = $this->manager->get_details('vehicle', array());

            // Sanitize and escape inputs
            $trip_vehicle_id = html_escape($this->input->post('trip_vehicle_id', TRUE));
            $agreement_location_id = html_escape($this->input->post('agreement_location_id', TRUE));
            $agreement_item_id = html_escape($this->input->post('agreement_item_id', TRUE));
            $onsite_chainage = html_escape($this->input->post('onsite_chainage', TRUE));
            $card_no = html_escape($this->input->post('card_no', TRUE));

            $fields = [];
            $fields['section_id'] = $this->user['section_id'];
            if($trip_vehicle_id) $fields['trip_vehicle_id'] = $trip_vehicle_id;
            if($agreement_location_id) $fields['trip.agreement_location_id'] = $agreement_location_id;
            if($agreement_item_id) $fields['trip.agreement_item_id'] = $agreement_item_id;
            if($onsite_chainage) $fields['onsite_chainage'] = $onsite_chainage;
            if($card_no) $fields['card_no'] = $card_no;

            $trip = $this->manager->get_trip($fields);
        } else {
            redirect('agreement','refresh');
        }

        $this->load->view('section/report', [
            'vehicle' => $vehicle,
            'agreement' => $agreement,
            'location' => $location,
            'item' => $item,
            'trip' => $trip,
            'data' => $fields
        ]);

        $this->load->view('footer.php');
    }

    // Custom callback for password validation
    public function valid_password($password = '') {
        $password = trim($password);

        if (strlen($password) < 8) {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least 8 characters in length.');
            return FALSE;
        }

        if (!preg_match('#[0-9]+#', $password)) {
            $this->form_validation->set_message('valid_password', 'The {field} field must contain at least one number.');
            return FALSE;
        }

        if (!preg_match('#[A-Z]+#', $password)) {
            $this->form_validation->set_message('valid_password', 'The {field} field must contain at least one uppercase letter.');
            return FALSE;
        }

        if (!preg_match('#[a-z]+#', $password)) {
            $this->form_validation->set_message('valid_password', 'The {field} field must contain at least one lowercase letter.');
            return FALSE;
        }

        if (!preg_match('#[\W]+#', $password)) {
            $this->form_validation->set_message('valid_password', 'The {field} field must contain at least one special character.');
            return FALSE;
        }

        return TRUE;
    }

    // Custom callback to check if input is only spaces
    public function check_spaces_only($str) {
        if (trim($str) == '') {
            $this->form_validation->set_message('check_spaces_only', 'The {field} field cannot consist of spaces only.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
