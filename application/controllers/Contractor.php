<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contractor extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'contractor') {
            redirect($role_id,'refresh');
        }
        
        $this->load->view('contractor/navbar');
        $this->load->model('Master');
        header("Access-Control-Allow-Origin: *");
    }

    public function index() {
        $section = $this->manager->get_details('section', array('section_id'=>$this->user['section_id']))[0];
        $data['bricks'][0] = $this->dash_bricks($section->section_id);
        $this->load->view('dashboard', $data);
        $this->load->view('footer');
    }

    public function vehicle($vehicle_id='') {
        $agreement = $this->manager->get_details('agreement', array('section_id'=>$this->user['section_id']));
        $editVeh = $this->manager->get_details('vehicle', array('vehicle_id'=>$vehicle_id));
        $typeOfVehicle = $this->Master->typeOfVehicle();
        $vehicle = $this->manager->get_details('vehicle', array('vehicle_agreement_id'=>$agreement[0]->agreement_id));
        if(!$vehicle) $vehicle = array();

        $this->load->view('contractor/vehicle', [
            'vehicle' => $vehicle,
            'typeOfVehicle' => $typeOfVehicle,
            'editVeh' => $editVeh
        ]);
        $this->load->view('footer');
    }

    public function addUpdateVehicle() {
        $this->load->library('form_validation');

        $agreement = $this->manager->get_details('agreement', array('section_id'=>$this->user['section_id']));

        // Enhanced validation rules
        $this->form_validation->set_rules('vehicle_no', 'Vehicle No', 'required|alpha_numeric|is_unique[vehicle.vehicle_no]|callback_check_spaces_only');
        $this->form_validation->set_rules('rc_owner', 'RC Owner', 'required|alpha_numeric_spaces|callback_check_spaces_only');

        if ($this->form_validation->run() == TRUE) {

            $vehicle_id = $this->input->post('vehicle_id');
            $data = array(
                'vehicle_agreement_id' => $agreement[0]->agreement_id,
                'vehicle_no' => strip_tags($this->input->post('vehicle_no')),
                'vehicle_type' => strip_tags($this->input->post('vehicle_type')),
                'insurance_end_date' => strip_tags($this->input->post('insurance_end_date')),
                'rc_owner' => strip_tags($this->input->post('rc_owner')),
            );

            if($vehicle_id > 0) {
                $this->manager->log('Updating vehicle. id-'.$vehicle_id, $data);
                $q = $this->manager->update_data('vehicle', $data, array('vehicle_id'=>$vehicle_id));
            } else {
                $this->manager->log("Inserting vehicle", $data);
                $q = $this->manager->insert_data('vehicle', $data);
            }

            if ($q) {
                $this->session->set_flashdata('message', 'Vehicle record added/updated successfully.');
                $this->session->set_flashdata('messageClass', 'alert-success');
                redirect('contractor/vehicle', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Failed to add/update vehicle record. Please try again.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
                $this->vehicle();
            }
        } else {
            $this->vehicle();
        }
    }

    public function deleteVehicle($id) {
        $id = html_escape($id); // Sanitize the input
         if (!$this->input->post($this->security->get_csrf_token_name())) {
                log_message('error', 'CSRF validation failed.');
                show_404(); // Redirect to custom error page
                return;
             }
             if (empty($id) || !is_numeric($id)) {
                log_message('error', 'Invalid ID: ' . $id);
                redirect('errorHandler/error_404'); // Redirect to custom error page
                return;
            }
               
        $this->manager->log('Deleting vehicle', array('vehicle_id' => $id));
        $q = $this->manager->delete_data('vehicle', array('vehicle_id' => $id));

        if ($q > 0) {
            $this->session->set_flashdata('message', 'Vehicle deleted successfully.');
            $this->session->set_flashdata('messageClass', 'alert-warning');
            $this->vehicle();
        } else {
            $this->session->set_flashdata('message', 'Vehicle deletion failed. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
            $this->vehicle();
        }
    }

    public function quarry($quarry_id='') {
        $agreement = $this->manager->get_details('agreement', array('section_id' => $this->user['section_id']));
        $editQry = $this->manager->get_details('quarry', array('quarry_id' => $quarry_id));
        $quarries = $this->manager->get_details('quarry', array('quarry_agreement_id' => $agreement[0]->agreement_id));
        $vehicles = $this->manager->select_join('vehicle', 'quarry', 'vehicle.vehicle_quarry_id = quarry.quarry_id', array('vehicle_agreement_id' => $agreement[0]->agreement_id));
        if(!$quarries) $quarries = array();

        $this->load->view('contractor/quarry', [
            'quarries' => $quarries,
            'vehicles' => $vehicles,
            'editQry' => $editQry
        ]);
        $this->load->view('footer');
    }

    public function addUpdateQuarry() {
        $this->load->library('form_validation');
    
        $agreement = $this->manager->get_details('agreement', array('section_id' => $this->user['section_id']));
        
        // Enhanced validation rules
        $this->form_validation->set_rules('quarry_location', 'Quarry Location', 'trim|required|max_length[50]|alpha_numeric_spaces|callback_check_special_chars');
        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[200]|alpha_numeric_spaces|callback_check_special_chars');
    
        if ($this->form_validation->run() == TRUE) {
            $quarry_id = $this->input->post('quarry_id');
            $data = array(
                'quarry_agreement_id' => $agreement[0]->agreement_id,
                'quarry_location' => strip_tags($this->input->post('quarry_location')),
                'description' => strip_tags($this->input->post('description')),
            );
    
            if ($quarry_id > 0) {
                $this->manager->log('Updating quarry. id-'.$quarry_id, $data);
                $q = $this->manager->update_data('quarry', $data, array('quarry_id' => $quarry_id));
            } else {
                $this->manager->log("Inserting quarry", $data);
                $q = $this->manager->insert_data('quarry', $data);
            }
    
            if ($q) {
                $this->session->set_flashdata('message', 'Quarry record added/updated successfully.');
                $this->session->set_flashdata('messageClass', 'alert-success');
                redirect('contractor/quarry', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Failed to add/update quarry record. Please try again.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
                $this->quarry();
            }
        } else {
            $this->quarry();
        }
    }
    
    // Custom callback function to check for special characters
    public function check_special_chars($str) {
        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $str)) {
            $this->form_validation->set_message('check_special_chars', 'The {field} field contains invalid characters.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    

    public function updateVehicleQuarry() {
        $vehicle_id = $this->input->post('vehicle_id');
        $quarry_id = $this->input->post('quarry_id');

        $data = array(
            'vehicle_id' => strip_tags($vehicle_id),
            'vehicle_quarry_id' => strip_tags($quarry_id)
        );

        $this->manager->log('Updating vehicle quarry. vehicle_id-'.$vehicle_id, $data);
        $q = $this->manager->update_data('vehicle', $data, array('vehicle_id'=>$vehicle_id));

        if ($q) {
            $this->session->set_flashdata('message', 'Vehicle quarry updated successfully.');
            $this->session->set_flashdata('messageClass', 'alert-success');
            redirect('contractor/quarry', 'refresh');
        } else {
            $this->session->set_flashdata('message', 'Failed to update vehicle quarry. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
            $this->quarry();
        }
    }

    public function deleteQuarry($id) {
        $id = html_escape($id); // Sanitize the input
        if (empty($id) || !is_numeric($id)) {
            log_message('error', 'Invalid ID: ' . $id);
            redirect('errorHandler/error_404'); // Redirect to custom error page
            return;
        }
            if (!$this->input->post($this->security->get_csrf_token_name())) {
                // $this->session->set_flashdata('message', 'CSRF Validation failed. Please try again.');
                log_message('error', 'CSRF validation failed.');
                redirect('errorHandler/error_403'); // Redirect to custom error page
                return;
             }
        $this->manager->log('Deleting quarry', array('quarry_id'=>$id));
        $q = $this->manager->delete_data('quarry', array('quarry_id'=>$id));

        if ($q > 0) {
            $this->session->set_flashdata('message', 'Quarry deleted successfully.');
            $this->session->set_flashdata('messageClass', 'alert-warning');
            $this->quarry();
        } else {
            $this->session->set_flashdata('message', 'Quarry deletion failed. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
            $this->quarry();
        }
    }

    public function profile() {
        $this->self();
    }

    public function change_password()
    {
        $this->change_user_password();
        $this->self();
    }


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

    // Custom callback function to check if input is only spaces
    public function check_spaces_only($str)
    {
        if (trim($str) == '') {
            $this->form_validation->set_message('check_spaces_only', 'The {field} field cannot consist of spaces only.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
