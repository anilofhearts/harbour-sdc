<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subdivision extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'subdivision') {
            redirect($role_id,'refresh');
        }

        $this->load->view('subdivision/navbar');
        header("Access-Control-Allow-Origin: *");
    }

    public function index()
    {
        $sections = $this->manager->get_details('section', array('subdivision_id'=>$this->user['section_id']));
        
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

    public function change_password()
    {
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

    // Custom callback for password validation
    public function valid_password($password = '')
    {
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

    // Additional methods that were commented out or trimmed
    /*
    public function user()
    {
        // Method code here...
    }

    public function dashboard_extended($agreement_id)
    {
        // Method code here...
    }
    */
}
