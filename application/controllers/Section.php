<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section extends MY_Controller {

    public function __construct()
    {
        parent::__construct();


        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'section') {
            redirect($role_id, 'refresh');
        }

        $this->load->view('section/navbar');
        $this->load->model('Master');
        header("Access-Control-Allow-Origin: *");
    }

    public function index()
    {
        $agreement = $this->manager->get_details('agreement', array('section_id' => $this->user['section_id'], 'date_of_completion' => null));
        if ($agreement) {
            $chainage = $this->manager->get_chainage($agreement[0]->agreement_id);
            $est_ttl_cost = $this->manager->est_ttl_cost($agreement[0]->agreement_id);
            $ttl_exp = $this->manager->ttl_exp($agreement[0]->agreement_id);

            if ($chainage) {
                foreach ($chainage as $cng) {
                    $trip = $this->manager->get_details('trip', array(
                        'agreement_location_id' => $cng->agreement_location_id,
                        'agreement_item_id' => $cng->agreement_item_id,
                        'onsite_chainage' => $cng->chainage
                    ));

                    $wt = 0;
                    $net_weight = 0;
                    if ($trip) {
                        foreach ($trip as $tp) {
                            $wt = ($tp->in_weight - $tp->out_weight) * (1 - $tp->onsite_loss / 100);
                            $net_weight += $wt;
                        }
                    }

                    $cng->dumped = $net_weight;
                }
            }

            $data = array(
                'role' => html_escape($_SESSION['harbour']['role_id']),
                'agreement' => $agreement,
                'chainage' => $chainage,
                'finprog' => round(($ttl_exp->ttl_exp * 100) / $est_ttl_cost->ttl_cost, 2),
                'stats' => $this->stats($agreement[0]->agreement_id)
            );
        } else {
            redirect('agreement', 'refresh');
        }
        $this->load->view('section/dashboard', ['data' => $data]);
        $this->load->view('footer');
    }

    public function trip()
    {
        $agreement = $this->manager->get_details('agreement', array('section_id' => $this->user['section_id']));
        if ($agreement) {
            $where_agr = array('agreement_id' => $agreement[0]->agreement_id);
            $location = $this->manager->get_details('agreement_location', $where_agr);
            $item = $this->manager->get_details('agreement_item', $where_agr);
            $vehicle = $this->manager->get_details('vehicle', array('vehicle_agreement_id' => $agreement[0]->agreement_id));
            $quarry = $this->manager->get_details('quarry', array());
            $typeOfVehicle = $this->Master->typeOfVehicle();
            $trip = $this->manager->get_trip(array('section_id' => $this->user['section_id'], 'in_datetime >' => date('Y-m-d')));
            $unit = $this->Master->unit();
            $typeOfTrip = $this->Master->typeOfTrip();
            $vehicle_out = $this->manager->vehicle_in($agreement[0]->agreement_id);
            $next_trip_no = $this->manager->get_max_where('trip', 'trip_no', array('agreement_id' => $agreement[0]->agreement_id, 'in_datetime >' => date('Y-m-d'))) + 1;
            $next_card_no = $this->manager->get_max_where('trip', 'card_no', $where_agr) + 1;
            $chainage = $this->manager->get_details('chainage', array('chainage_agr_loc_id' => $location[0]->agreement_location_id, 'chainage_item_id' => $item[0]->agreement_item_id));

            $data = array(
                'item_today' => $this->manager->get_details('item_summary_today', $where_agr),
                'item_cum' => $this->manager->get_details('item_summary_cumulative', $where_agr),
                'trips_today' => $this->manager->count_data('trip', array('agreement_id' => $agreement[0]->agreement_id, 'in_datetime >' => date('Y-m-d'))),
                'trips_all' => $this->manager->count_data('trip', $where_agr),
                'est_ttl' => $this->manager->get_sum('agreement_item', 'estimated_quantity', $where_agr),
                'est_ttl_cost' => $this->manager->est_ttl_cost($agreement[0]->agreement_id),
                'ttl_exp' => $this->manager->ttl_exp($agreement[0]->agreement_id)
            );
        } else {
            redirect('agreement', 'refresh');
        }
        $this->load->view('section/trip', [
            'vehicle' => $vehicle,
            'quarry' => $quarry,
            'typeOfVehicle' => $typeOfVehicle,
            'agreement' => $agreement,
            'location' => $location,
            'item' => $item,
            'trip' => $trip,
            'unit' => $unit,
            'typeOfTrip' => $typeOfTrip,
            'next_trip' => $next_trip_no,
            'next_card' => $next_card_no,
            'chainage' => $chainage,
            'data' => $data
        ]);

        $this->load->view('footer.php');
    }

    public function deleteTrip($trip_id)
    {
        $trip_id = html_escape($trip_id); // Sanitize the input
        // Validate ID
        if (empty($trip_id) || !is_numeric($trip_id)) {
            log_message('error', 'Invalid ID: ' . $trip_id);
            redirect('errorHandler/error_404'); // Redirect to custom error page
            return;
        }
        if (!$this->input->post($this->security->get_csrf_token_name())) {
            log_message('error', 'CSRF validation failed.');
            redirect('errorHandler/error_403'); // Redirect to custom error page
            return;
         }
        $this->manager->log('Deleting Trip', array('trip_id' => $trip_id));
        $q = $this->manager->delete_data('trip', array('trip_id' => $trip_id));

        if ($q > 0) {
            $this->session->set_flashdata('message', 'Congratulation. Trip deleted successfully.');
            $this->session->set_flashdata('messageClass', 'alert-warning');
        } else {
            $this->session->set_flashdata('message', 'Trip Deletion failed. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
        }
        $this->trip();
    }

    public function agreement()
    {
        $agreement = $this->manager->get_sort('agreement', array('section_id' => $this->user['section_id']), 'agreement_no', 'DESC');
        if ($agreement) {
            $items = $this->manager->get_details('agreement_item', array('agreement_id' => $agreement[0]->agreement_id));
            $locations = $this->manager->get_details('agreement_location', array('agreement_id' => $agreement[0]->agreement_id));
        } else {
            $agreement = array();
            $items = array();
            $locations = array();
        }

        $this->load->view('section/agreement', [
            'agreement' => $agreement,
            'items' => $items,
            'locations' => $locations
        ]);
        $this->load->view('footer');
    }

    public function agreementForm($agreement_id = '')
    {
        log_message('debug', 'CSRF Token Generated: ' . $this->security->get_csrf_hash());
        log_message('debug', 'CSRF Token from Cookie: ' . $this->input->cookie($this->config->item('csrf_cookie_name')));
        log_message('debug', 'CSRF Token from POST: ' . $this->input->post($this->security->get_csrf_token_name()));
        $agreement_id = html_escape($agreement_id); // Sanitize the input
        if (empty($agreement_id) || !is_numeric($agreement_id)) {
            log_message('error', 'Invalid ID: ' . $agreement_id);
            redirect('errorHandler/error_404'); // Redirect to custom error page
            return;
        }
            if (!$this->input->post($this->security->get_csrf_token_name())) {
                log_message('error', 'CSRF validation failed.');
                redirect('errorHandler/error_403'); // Redirect to custom error page
                return;
             }
        $editAgre = $this->manager->get_details('agreement', array('agreement_id' => $agreement_id));

        $dept = array(
            '' => '-- Select Dept --',
            'CTE' => 'CTE',
            'KIFFB' => 'KIFFB',
            'KSCADC' => 'KSCADC',
            'Tourism' => 'Tourism',
            'Fisheries' => 'Fisheries'
        );

        $data = array(
            'loc' => $this->manager->get_details('agreement_location', array('agreement_id' => $agreement_id)),
            'item' => $this->manager->get_details('agreement_item', array('agreement_id' => $agreement_id)),
            'dept' => $dept
        );

        $circle = $this->manager->get_details('circle', array());
        $type_of_work = $this->manager->get_distinct('work', 'work_type');

        $this->load->view('section/agreementForm', [
            'data' => $data,
            'circle' => $circle,
            'type_of_work' => $type_of_work,
            'editAgre' => $editAgre
        ]);
        $this->load->view('footer');
    }

    public function addUpdateAgreement()
    {
        $this->load->library('form_validation');

        $agreement_id = html_escape($this->input->post('agreement_id', TRUE)); // Sanitize input
        if (!$agreement_id) {
            $this->form_validation->set_rules('agreement_no', 'Agreement No', 'trim|required|is_unique[agreement.agreement_no]|callback_check_spaces_only');
        }
        $this->form_validation->set_rules('agreement', 'Agreement', 'trim|required|callback_check_spaces_only');
        $this->form_validation->set_rules('amount', 'Amount', 'numeric|required');
        $this->form_validation->set_rules('period_of_commencement', 'Period of Commencement', 'trim|required|alpha_numeric_spaces|callback_check_spaces_only');
        $this->form_validation->set_rules('contractor_email_id', 'Email ID', 'valid_email|required');
        $this->form_validation->set_rules('name_of_contractor', 'Name of Contractor', 'trim|required|alpha_numeric_spaces|callback_check_spaces_only');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'agreement' => html_escape($this->input->post('agreement', TRUE)),
                'amount' => html_escape($this->input->post('amount', TRUE)),
                'date_of_agreement' => html_escape($this->input->post('date_of_agreement', TRUE)),
                'date_of_commencement' => html_escape($this->input->post('date_of_commencement', TRUE)),
                'exp_date_of_completion' => html_escape($this->input->post('exp_date_of_completion', TRUE)),
                'type_of_work' => html_escape($this->input->post('type_of_work', TRUE)),
                'name_of_contractor' => html_escape($this->input->post('name_of_contractor', TRUE)),
                'address' => html_escape($this->input->post('address', TRUE)),
                'contractor_email_id' => html_escape($this->input->post('contractor_email_id', TRUE)),
                'contractor_phone_no' => html_escape($this->input->post('contractor_phone_no', TRUE)),
                'period_of_commencement' => html_escape($this->input->post('period_of_commencement', TRUE)),
                'short_code' => html_escape($this->input->post('short_code', TRUE)),
                'section_id' => html_escape($this->input->post('section_id', TRUE)),
                'department' => html_escape($this->input->post('department', TRUE))
            );

            if ($agreement_id > 0) {
                $this->manager->log('Updating agreement. id-' . $agreement_id, $data);
                $q = $this->manager->update_data('agreement', $data, array('agreement_id' => $agreement_id));
            } else {
                $data['agreement_no'] = html_escape($this->input->post('agreement_no', TRUE));
                $this->manager->log('Inserting agreement', $data);
                $agreement_id = $this->manager->insert_data('agreement', $data);
            }

            if ($agreement_id > 0) {
                $agreement_item_id = $this->input->post('agreement_item_id[]', TRUE);
                $item = $this->input->post('item[]', TRUE);
                $unit = $this->input->post('unit[]', TRUE);
                $estimated_quantity = $this->input->post('estimated_quantity[]', TRUE);
                $estimated_rate = $this->input->post('estimated_rate[]', TRUE);

                $items = array();
                $i = 0;
                while ($i < count($item)) {
                    if ($item[$i] == "Tetrapod") {
                        $estimated_quantity[$i] = $estimated_quantity[$i] * 2;
                        $estimated_rate[$i] = $estimated_rate[$i] / 2;
                    }
                    array_push($items, array(
                        'agreement_item_id' => isset($agreement_item_id[$i]) ? html_escape($agreement_item_id[$i]) : '',
                        'agreement_id' => html_escape($agreement_id),
                        'item' => html_escape($item[$i]),
                        'unit' => html_escape($unit[$i]),
                        'estimated_quantity' => html_escape($estimated_quantity[$i]),
                        'estimated_rate' => html_escape($estimated_rate[$i])
                    ));
                    $i++;
                }

                $agreement_location_id = $this->input->post('agreement_location_id[]', TRUE);
                $location = $this->input->post('location[]', TRUE);
                $locations = array();
                $x = 0;
                while ($x < count($location)) {
                    array_push($locations, array(
                        'agreement_location_id' => isset($agreement_location_id[$x]) ? html_escape($agreement_location_id[$x]) : '',
                        'agreement_id' => html_escape($agreement_id),
                        'location' => html_escape($location[$x])
                    ));
                    $x++;
                }

                if ($agreement_id > 0) {
                    $this->manager->log('Deleting to update agreement_item', $agreement_id);
                    $this->manager->delete_data('agreement_item', array('agreement_id' => $agreement_id));
                    $this->manager->log('Deleting to update agreement_location', $agreement_id);
                    $this->manager->delete_data('agreement_location', array('agreement_id' => $agreement_id));
                }

                $this->manager->log('Inserting agreement_item', $items);
                $q1 = $this->manager->insert_batch('agreement_item', $items);

                $this->manager->log('Inserting agreement_location', $locations);
                $q2 = $this->manager->insert_batch('agreement_location', $locations);

                if (!$q1) {
                    $this->session->set_flashdata('message', 'Failed to add/update items. Please try again.');
                    $this->session->set_flashdata('messageClass', 'alert-danger');
                    $this->agreementForm();
                }
                if (!$q2) {
                    $this->session->set_flashdata('message', 'Failed to add/update locations. Please try again.');
                    $this->session->set_flashdata('messageClass', 'alert-danger');
                    $this->agreementForm();
                }

                $this->session->set_flashdata('message', 'Congratulation! Agreement added/updated successfully.');
                $this->session->set_flashdata('messageClass', 'alert-success');
                redirect('section/agreement', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Failed to add/update agreement. Please try again.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
                $this->agreementForm();
            }
        } else {
            $this->agreementForm();
        }
    }

    public function deleteAgreement($id)
    {
        $id = html_escape($id); // Sanitize the input
        if (empty($id) || !is_numeric($id)) {
            log_message('error', 'Invalid ID: ' . $id);
            redirect('errorHandler/error_404'); // Redirect to custom error page
            return;
        }
        if (!$this->input->post($this->security->get_csrf_token_name())) {
            log_message('error', 'CSRF validation failed.');
            redirect('errorHandler/error_403'); // Redirect to custom error page
            return;
         }
        $this->manager->log('Deleting agreement', array('agreement_id' => $id));
        $q = $this->manager->delete_data('agreement', array('agreement_id' => $id));

        if ($q > 0) {
            $this->session->set_flashdata('message', 'Congratulation. Agreement deleted successfully.');
            $this->session->set_flashdata('messageClass', 'alert-warning');
            $this->agreement();
        } else {
            $this->session->set_flashdata('message', 'Agreement deletion failed. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
            $this->agreement();
        }
    }

    public function chainage($agreement_id)
    {
        $agreement_id = html_escape($agreement_id); // Sanitize the input
        $data = array(
            'agreement_location_id' => html_escape($this->input->post('agreement_location_id', TRUE)),
            'agreement' => $this->manager->get_details('agreement', array('agreement_id' => $agreement_id)),
            'items' => $this->manager->get_details('agreement_item', array('agreement_id' => $agreement_id)),
            'locations' => $this->manager->get_details('agreement_location', array('agreement_id' => $agreement_id)),
            'list' => $this->manager->get_chainage($agreement_id)
        );
        if (!$data['list']) {
            $data['list'] = array();
        }
        $this->load->view('section/chainage', ['data' => $data]);
        $this->load->view('footer');
    }

    public function add_chainage()
    {
        $agreement_id = html_escape($this->input->post('agreement_id', TRUE));
        $chainage_item_id = $this->input->post('chainage_item_id[]', TRUE);
        $chainage_agr_loc_id = html_escape($this->input->post('chainage_agr_loc_id', TRUE));
        $chainage = $this->input->post('chainage[]', TRUE);
        $chainage_quantity = $this->input->post('chainage_quantity[]', TRUE);

        $tetrapod_id = $this->manager->get_details('agreement_item', array('agreement_id' => $agreement_id, 'item' => 'Tetrapod'))[0]->agreement_item_id;

        $noItem = count($chainage_item_id) / count($chainage);
        $rows = array();
        $r = 0;
        $i = 0;
        while ($r < count($chainage)) { // Loop over rows

            $c = 0;
            while ($c < $noItem) { // Loop over columns
                if (isset($tetrapod_id)) { // Convert tetrapod nos to weight
                    if ($tetrapod_id == $chainage_item_id[$i]) {
                        $chainage_quantity[$i] = $chainage_quantity[$i] * 2;
                    }
                }
                array_push($rows, array(
                    'chainage' => html_escape($chainage[$r]),
                    'chainage_agr_loc_id' => $chainage_agr_loc_id,
                    'chainage_item_id' => html_escape($chainage_item_id[$i]),
                    'chainage_quantity' => html_escape($chainage_quantity[$i])
                ));
                $i++;
                $c++;
            }
            $r++;
        }

        $this->manager->delete_data('chainage', array('chainage_agr_loc_id' => $chainage_agr_loc_id));

        $q = $this->manager->insert_batch('chainage', $rows);

        if ($q) {
            $this->session->set_flashdata('message', 'Congratulation! Chainage added successfully.');
            $this->session->set_flashdata('messageClass', 'alert-success');
        } else {
            $this->session->set_flashdata('message', 'Failed to add chainage. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
        }
        redirect("chainage/$agreement_id", 'refresh');
    }

    public function vehicle($vehicle_id = '')
    {
        $vehicle_id = html_escape($vehicle_id); // Sanitize the input
        $agreement = $this->manager->get_details('agreement', array('section_id' => $this->user['section_id']));
        $editVeh = $this->manager->get_details('vehicle', array('vehicle_id' => $vehicle_id));
        $typeOfVehicle = $this->Master->typeOfVehicle();
        $vehicle = $this->manager->get_details('vehicle', array('vehicle_agreement_id' => $agreement[0]->agreement_id));
        if (!$vehicle) {
            $vehicle = array();
        }

        $this->load->view('section/vehicle', [
            'vehicle' => $vehicle,
            'typeOfVehicle' => $typeOfVehicle,
            'editVeh' => $editVeh
        ]);
        $this->load->view('footer');
    }

    public function addUpdateVehicle()
    {
        $this->load->library('form_validation');

        $agreement = $this->manager->get_details('agreement', array('section_id' => $this->user['section_id']));

        // Validation rules
        $this->form_validation->set_rules('vehicle_no', 'Vehicle No', 'alpha_numeric|required|callback_check_spaces_only');
        $this->form_validation->set_rules('rc_owner', 'RC Owner', 'trim|required|alpha_numeric_spaces|callback_check_spaces_only');

        $dashboard = html_escape($this->input->post('dashboard', TRUE));
        $vehicle_id = html_escape($this->input->post('vehicle_id', TRUE));

        if ($this->form_validation->run() == TRUE) {
            $vehicle_no = html_escape($this->input->post('vehicle_no', TRUE));
            // Exclude current vehicle from duplicate check when updating
            $checkVehicleWhere = array('vehicle_agreement_id' => $agreement[0]->agreement_id, 'vehicle_no' => $vehicle_no);
            if ($vehicle_id) {
                $checkVehicleWhere['vehicle_id !='] = $vehicle_id;
            }
            $checkVehicle = $this->manager->get_details('vehicle', $checkVehicleWhere);

            if ($checkVehicle) {
                $this->session->set_flashdata('message', 'Vehicle already exists. Please try another.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
                if ($dashboard) {
                    $this->trip();
                } else {
                    $this->vehicle();
                }
            } else {
                $data = array(
                    'vehicle_agreement_id' => html_escape($agreement[0]->agreement_id),
                    'vehicle_no' => $vehicle_no,
                    'vehicle_type' => html_escape($this->input->post('vehicle_type', TRUE)),
                    'insurance_end_date' => html_escape($this->input->post('insurance_end_date', TRUE)),
                    'rc_owner' => html_escape($this->input->post('rc_owner', TRUE))
                );

                if ($vehicle_id > 0) {
                    $this->manager->log('Updating vehicle. id-' . $vehicle_id, $data);
                    $q = $this->manager->update_data('vehicle', $data, array('vehicle_id' => $vehicle_id));
                } else {
                    $this->manager->log("Inserting vehicle", $data);
                    $q = $this->manager->insert_data('vehicle', $data);
                }
                if ($q) {
                    $this->session->set_flashdata('message', 'Congratulation! Record added/updated successfully.');
                    $this->session->set_flashdata('messageClass', 'alert-success');
                    if ($dashboard) {
                        redirect('section/', 'refresh');
                    } else {
                        redirect('section/vehicle', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Failed to add/update record. Please try again.');
                    $this->session->set_flashdata('messageClass', 'alert-danger');
                    if ($dashboard) {
                        $this->trip();
                    } else {
                        $this->vehicle();
                    }
                }
            }
        } else {
            $this->vehicle();
        }
    }

    public function deleteVehicle($id)
    {
        $id = html_escape($id); // Sanitize the input
        // Validate ID
    if (empty($id) || !is_numeric($id)) {
        log_message('error', 'Invalid ID: ' . $id);
        redirect('errorHandler/error_404'); // Redirect to custom error page
        return;
    }
        if (!$this->input->post($this->security->get_csrf_token_name())) {
            log_message('error', 'CSRF validation failed.');
            redirect('errorHandler/error_403'); // Redirect to custom error page
            return;
         }
         $this->manager->log('Deleting vehicle', array('vehicle_id' => $id));
        $q = $this->manager->delete_data('vehicle', array('vehicle_id' => $id));
        if ($q > 0) {
            $this->session->set_flashdata('message', 'Congratulation. Vehicle deleted successfully.');
            $this->session->set_flashdata('messageClass', 'alert-warning');
            $this->vehicle();
        } else {
            $this->session->set_flashdata('message', 'Vehicle Deletion failed. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
            $this->vehicle();
        }
    }

    public function workDetail($work_id = '')
    {
        $work_id = html_escape($work_id); // Sanitize the input
        
        $editWork = $this->manager->get_details('work', array('work_id' => $work_id));
        $typeOfWork = $this->Master->typeOfWork();
        $work = $this->manager->get_details('work', array());
        if (!$work) {
            $work = array();
        }

        $this->load->view('section/work', [
            'work' => $work,
            'typeOfWork' => $typeOfWork,
            'editWork' => $editWork
        ]);
        $this->load->view('footer');
    }

    public function addUpdateWork()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('item_no', 'Item No', 'decimal|required');
        $this->form_validation->set_rules('item_name', 'Item Name', 'trim|required|regex_match[/^[A-Za-z0-9\- ]+$/]|callback_check_spaces_only');
        $this->form_validation->set_rules('unit', 'Unit', 'trim|required|callback_check_spaces_only');
        $this->form_validation->set_rules('item_description', 'Item Description', 'trim|required|callback_check_spaces_only');

        if ($this->form_validation->run() == TRUE) {

            $work_id = html_escape($this->input->post('work_id', TRUE));
            $data = array(
                'work_type' => html_escape($this->input->post('work_type', TRUE)),
                'item_no' => html_escape($this->input->post('item_no', TRUE)),
                'item_name' => html_escape($this->input->post('item_name', TRUE)),
                'unit' => html_escape($this->input->post('unit', TRUE)),
                'item_description' => html_escape($this->input->post('item_description', TRUE))
            );

            if ($work_id > 0) {
                $this->manager->log('Updating work. id-' . $work_id, $data);
                $q = $this->manager->update_data('work', $data, array('work_id' => $work_id));
            } else {
                $this->manager->log('Inserting work', $data);
                $q = $this->manager->insert_data('work', $data);
            }
            if ($q) {
                $this->session->set_flashdata('message', 'Congratulation! Work details added/updated successfully.');
                $this->session->set_flashdata('messageClass', 'alert-success');
                redirect('section/workDetail', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Failed to add/update work details. Please try again.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
                $this->workDetail();
            }
        } else {
            $this->workDetail();
        }
    }

//     public function deleteWork($id = null)
// {
// // Validate CSRF token
//     if (!$this->input->post($this->security->get_csrf_token_name())) {
//         // Redirect to the custom 403 error page
//         redirect('errorHandler/error_403');
//         return;
//     }

// // Check if ID is provided and valid
// if (empty($id) || !is_numeric($id)) {
//     show_404(); // Redirect to 404 if ID is invalid
//     return;
// }
//     $id = html_escape($id); // Sanitize the input
//     $this->manager->log('Deleting work', array('work_id' => $id));
//     $q = $this->manager->delete_data('work', array('work_id' => $id));

//     if ($q > 0) {
//         $this->session->set_flashdata('message', 'Work details deleted successfully.');
//         $this->session->set_flashdata('messageClass', 'alert-warning');
//     } else {
//         $this->session->set_flashdata('message', 'Work details deletion failed. Please try again.');
//         $this->session->set_flashdata('messageClass', 'alert-danger');
//     }

//     redirect('section/workDetail'); // Redirect to work details page
// }
public function deleteWork($id = null)
{
    // Debug incoming CSRF token and cookie
    log_message('debug', 'CSRF Token Generated: ' . $this->security->get_csrf_hash());
   
     log_message('debug', 'CSRF Token from Cookie: ' . $this->input->cookie($this->config->item('csrf_cookie_name')));
     log_message('debug', 'POST Data: ' . print_r($this->input->post(), TRUE));
    // log_message('debug', 'Raw POST Input: ' . file_get_contents('php://input'));
    //  log_message('debug', '$_POST: ' . print_r($_POST, TRUE));
    // // log_message('debug', '$_SERVER: ' . print_r($_SERVER, TRUE));
    // log_message('debug', 'php://input: ' . file_get_contents('php://input'));
    // // Validate CSRF token
    // // if (empty($_POST)) {
    // //     parse_str(file_get_contents('php://input'), $_POST);
    // //     log_message('debug', 'Manually populated $_POST: ' . print_r($_POST, TRUE));
        log_message('debug', 'CSRF Token from POST: ' . $this->input->post($this->security->get_csrf_token_name()));
    // // }
    if (!$this->input->post($this->security->get_csrf_token_name())) {
        log_message('error', 'CSRF validation failed.');
        redirect('errorHandler/error_403'); // Redirect to custom error page
        return;
    }
   
    // Validate ID
    if (empty($id) || !is_numeric($id)) {
        log_message('error', 'Invalid ID: ' . $id);
        redirect('errorHandler/error_404'); // Redirect to custom error page
        return;
    }

    // Proceed with delete logic
    $result = $this->manager->delete_data('work', ['work_id' => $id]);
    if ($result > 0) {
        $this->session->set_flashdata('message', 'Work details deleted successfully.');
    } else {
        $this->session->set_flashdata('message', 'Work deletion failed.');
    }

    redirect('section/workDetail');
}

    public function report()
    {
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

    public function profile()
    {
        $this->self();
    }

 public function change_password() {
    if ($this->input->post()) { // Only process if the form is submitted
        $this->change_user_password();
    }
    $this->self(); // Load the profile page or change password view
}
    public function posttriping()
    {
        $this->load->view('section/preview_trip');
        $this->load->view('footer');
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
