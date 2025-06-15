<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SessionValidator {
    public function validate() {
        $CI =& get_instance();
        $session_data = $CI->session->userdata('harbour'); // Replace 'harbour' with your session key

        if ($session_data) {
            // Check if the session is valid in the database
            $valid_session = $CI->db->get_where('user_sessions', array(
                'user_id'    => $session_data['user_id'],
                'session_id' => session_id(),
                'status'     => 'valid'
            ))->row();

            if (!$valid_session) {
                // Invalidate session and redirect to login
                $CI->session->unset_userdata('harbour');
                $CI->session->set_flashdata('message', 'Your session has been logged out due to a password change or invalidation.');
                redirect('login'); // Adjust to your login route
                exit;
            }
        }
    }
}
