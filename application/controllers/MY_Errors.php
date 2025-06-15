<?php

class MY_Errors extends CI_Controller {

  public function show_403() {
    // Set the HTTP status header
    $this->output->set_status_header('403');
    
    // Redirect to login page only if the user is not logged in
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    }

    // Otherwise, show a custom 403 error page
    $data['title'] = '403 Forbidden';
    $this->load->view('errors/custom_403', $data);
  }

  public function show_404() {
    // Set the HTTP status header
    $this->output->set_status_header('404');
    
    // Redirect to login page only if the user is not logged in
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    }

    // Otherwise, show a custom 404 error page
    $data['title'] = '404 Page Not Found';
    $this->load->view('errors/custom_404', $data);
  }
}
