<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {

    public function __construct() {
        parent::__construct();
    }

    public function show_403($page = '', $log_error = TRUE) {
        // Redirect to login page
        redirect('login', 'refresh');
    }

    public function show_404($page = '', $log_error = TRUE) {
        // Redirect to login page
        redirect('login', 'refresh');
    }
}
?>