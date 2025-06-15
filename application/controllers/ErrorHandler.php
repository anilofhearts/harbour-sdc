<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ErrorHandler extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // Set HTTP status header
        $this->output->set_status_header(404);

        // Pass error details to the view (optional, based on redirection logic)
        $data['heading'] = "404 Page Not ";
        $data['message'] = "The page you are looking for does not exist.";

        // Load a custom 404 view
        $this->load->view('errors/custom_404', $data);
    }

    public function error_403() {
        // Log the error for debugging
        log_message('debug', '403 Forbidden page accessed.');
    
        // Set the HTTP status header to 403
        $this->output->set_status_header(403);
        
        // Pass error details to the view (optional, based on redirection logic)
        $data['heading'] = "403 Page Not Found";
        $data['message'] = "The page is Forbidden.";

    
        // Load the custom 403 view
        $this->load->view('errors/error_403', $data);
    }

    public function error_404() {
        // 404 error page
        $this->output->set_status_header(404);
         
        // Pass error details to the view (optional, based on redirection logic)
        $data['heading'] = "404 Page Not ";
        $data['message'] = "The page you are looking for does not exist.";

        $this->load->view('errors/html/error_404', $data);
    }

    public function general() {
        // Set HTTP status header
        $this->output->set_status_header(500);

        // Pass error details to the view (optional, based on redirection logic)
        $data['heading'] = "An Error Occurred";
        $data['message'] = "Something went wrong. Please try again later.";
        
        // Load a custom view for general errors
        $this->load->view('errors/error_403', $data);
    }
}
