<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (empty($_POST)) {
            parse_str(file_get_contents('php://input'), $_POST);
            log_message('debug', 'Manually populated $_POST: ' . print_r($_POST, TRUE));
            log_message('debug', 'CSRF Token from POST: ' . $this->input->post($this->security->get_csrf_token_name()));
        }
        if (!$this->session->has_userdata('harbour')) {

                return redirect('login');
        }

        $this->user['section_id'] = $_SESSION['harbour']['section_id'];
        $this->user['user_id'] = $_SESSION['harbour']['user_id'];
        $this->user['role'] = $_SESSION['harbour']['role_id'];
            // $this->load->model('manager');

        $userinfo = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('user_id'=>$this->user['user_id']))[0];
            //$user = $userinfo['name'];
        $this->load->view('header', ['userinfo'=>$userinfo]);
            //$this->load->view('section/navbar');

        $this->load->helper('form');
        // $this->load->model('Master');
        // $this->load->model('Manager');
        // header("Access-Control-Allow-Origin: *");
        // print_r($_SESSION['harbour']);
    }
    
    public function self()
    {
        $user = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('user_id'=>$this->user['user_id']))[0];
        $role = $user->role_id;
        $section_id = $user->section_id;

        $data = array(
            'role' => $role,
            'user' => $user
        );

        while ($role <> 'state') {

            if ($role == 'department') {
                $data['sectionUser'] = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$section_id))[0];
                $role = 'section';   
            }

            if ($role == 'contractor') {
                $data['sectionUser'] = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$section_id))[0];
                $role = 'section';   
            }

            if ($role == 'section') {
                $section = $this->manager->get_details('section', array('section_id'=>$section_id))[0];
                $data['section'] = $section;
                $section_id = $section->subdivision_id;
                $data['subdivUser'] = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$section_id))[0];
                $role = 'subdivision';   
            }

            if ($role == 'subdivision') {
                $subdivision = $this->manager->get_details('subdivision', array('subdivision_id'=>$section_id))[0];
                $data['subdivision'] = $subdivision;
                $section_id = $subdivision->division_id;
                $data['divUser'] = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$section_id))[0];
                $role = 'division';
            }

            if ($role == 'division') {
                $division = $this->manager->get_details('division', array('division_id'=>$section_id))[0];
                $data['division'] = $division;
                $section_id = $division->circle_id;
                $data['cirUser'] = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$section_id))[0];
                $role = 'circle';
            }

            if ($role == 'circle') {
                $circle = $this->manager->get_details('circle', array('circle_id'=>$section_id))[0];
                $data['circle'] = $circle;
                $data['state'] = $this->manager->get_details('state', array('state_id'=>$circle->state_id))[0];
                $data['ce'] = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$circle->state_id))[0];
                $role = 'state';
            }
        }
        
        $data['designation'] = $this->manager->get_details('designation', array());
        //$user = ;
        $this->load->view('profile', ['data'=>$data]);
        $this->load->view('footer');
    }

    public function users($role, $name)
    {
        $name = str_replace('%20', ' ', $name);
        // echo $role; echo $name;
        $branch = $this->manager->get_details($role, array($role => $name))[0];
        $branch_id = $role.'_id';
        $users = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('section_id'=>$branch->$branch_id)); 

        $data = array(
            'role' => $role,
            'name' => $name,
            'users' => $users,
            'designation' => $this->manager->get_details('designation', array())
        );

        return $data;
        //$user = ;
        // $this->load->view('admin/users', ['data'=>$data]);
        // $this->load->view('footer');
    }

    public function add_edit_user_profile()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pen', 'PEN', 'trim|numeric');
        $this->form_validation->set_rules('fullname', 'Full name', 'trim|alpha_numeric_spaces|min_length[6]');
        $this->form_validation->set_rules('email_id', 'Email ID', 'trim|valid_email');
        $this->form_validation->set_rules('phone_no', 'Phone No', 'trim|numeric');
        
        $designation_id = $this->input->post('designation_id');
        $designation = $this->manager->get_details('designation', array('designation_id'=>$designation_id))[0]->designation;

        if ($designation == 'Contractor') {
            $role = 'contractor';
        } elseif ($designation == 'Department User') {
            $role = 'department';
        } else {
            $role = $this->input->post('role');
        }

        $user_id = $this->input->post('user_id');
        if(!$user_id){
            $this->form_validation->set_rules('pen', 'PEN', 'is_unique[user.name]');
        }
        if ($this->form_validation->run() == TRUE) {

            $data = array(
                'section_id' => $this->input->post('section_id'),
                'name' => $this->input->post('pen'),
                'fullname' => $this->input->post('fullname'),
                'designation_id' => $designation_id,
                // 'designation' => $designation,
                'email_id' => $this->input->post('email_id'),
                'phone_no' => $this->input->post('phone_no'),
                'role_id' => $role,
                'status' => 1
            );

            // print_r($data); exit;

            if($user_id) {
                $this->manager->log('Editing user. id-'.$user_id, $data);
                $q = $this->manager->update_data('user', $data, array('user_id'=>$user_id));
            } else{
                $this->manager->log('Adding user.', $data);
                $q = $this->manager->insert_data('user', $data);
            }

            if ($q) {
                $this->session->set_flashdata('message', 'Congratulation! User added/updated successfully.');
                $this->session->set_flashdata('messageClass', 'alert-success');
                // redirect('subdivision/user','refresh');
            } else{
                $this->session->set_flashdata('message', 'Failed to add/update user. Please try again.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
                //$this->user();
            }
        } else{
            $this->session->set_flashdata('message', 'Failed to add/update user. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
        }

        // $this->user();
    }

    public function delete_user_data($user_id)
    {
        $this->manager->log('Deleting user. id-'.$user_id, $user_id);
        $q = $this->manager->delete_data('user', array('user_id'=>$user_id));
        if ($q) {
            $this->session->set_flashdata('message', 'Congratulation! User deleted successfully.');
            $this->session->set_flashdata('messageClass', 'alert-success');
        } else{
            $this->session->set_flashdata('message', 'Failed to delete user. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
        }
    }

    public function change_user_password()
{
    $this->load->library('form_validation');

    // Existing password validation
    $this->form_validation->set_rules('password', 'Current Password', 'trim|required');

    // New password validation with complexity check
    $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[8]|callback_valid_password');

    // Confirm password validation
    $this->form_validation->set_rules('cnf_password', 'Password Confirmation', 'trim|required|matches[new_password]');

    if ($this->form_validation->run() == TRUE) {

        $user_id = $this->input->post('user_id');
        $password = $this->input->post('password');
        $new_password = $this->input->post('new_password');

        $user = $this->manager->get_details('user', array('user_id' => $user_id));

        if (isset($user)) {

            $check = $this->manager->verify_hash($password, $user[0]->password);

            if ($check == 1) {

                // Update the password
                $data = array(
                    'password' => $this->manager->hash_it($new_password)
                );

                $this->manager->log('Changing Password for user id-' . $user_id, $data);
                $q = $this->manager->update_data('user', $data, array('user_id' => $user_id));

                if ($q) {
                    // Invalidate all sessions for the user
                    $this->db->where('user_id', $user_id);
                    $this->db->update('user_sessions', array('status' => 'invalid'));

                    // Destroy current session
                    session_destroy();

                    $this->session->set_flashdata('message', 'Password changed successfully. All sessions have been logged out.');
                    $this->session->set_flashdata('messageClass', 'alert-success');
                } else {
                    $this->session->set_flashdata('message', 'Failed to change password. Please try again.');
                    $this->session->set_flashdata('messageClass', 'alert-danger');
                }
            } else {
                $this->session->set_flashdata('message', 'Wrong current password. Please try again.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
            }
        }
    } else {
        $this->session->set_flashdata('message', 'Failed to change password. Please check the complexity of the password.');
    }

    // $this->self(); // Redirect or load the profile page after password change
}
    
    // Password complexity validation callback
    public function valid_password($password)
    {
        // Minimum eight characters, at least one uppercase letter, one lowercase letter, one number, and one special character
        if (!preg_match('/[A-Z]/', $password)) {
            $this->form_validation->set_message('valid_password', 'The {field} must include at least one uppercase letter.');
            return FALSE;
        }
        if (!preg_match('/[a-z]/', $password)) {
            $this->form_validation->set_message('valid_password', 'The {field} must include at least one lowercase letter.');
            return FALSE;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->form_validation->set_message('valid_password', 'The {field} must include at least one number.');
            return FALSE;
        }
        if (!preg_match('/[@$!%*?&]/', $password)) {
            $this->form_validation->set_message('valid_password', 'The {field} must include at least one special character (@$!%*?&).');
            return FALSE;
        }
        return TRUE;
    }
    

    public function reset_user_password()
    {
        $this->load->library('form_validation');
        // $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('cnf_password', 'Password Confirmaton', 'trim|required|matches[new_password]');

        if ($this->form_validation->run() == TRUE) {

            $user_id = $this->input->post('user_id');
            // $password = $this->input->post('password');
            $new_password = $this->input->post('new_password');

            $data = array(
                'password' => $this->manager->hash_it($new_password)
            );

            $this->manager->log('Changing Password for user id-'.$user_id, $data);
            $q = $this->manager->update_data('user', $data, array('user_id'=>$user_id));

            if ($q) {
                session_destroy();
                $this->session->set_flashdata('message', 'Password reset successfully.');
                $this->session->set_flashdata('messageClass', 'alert-success');
            } else{
                $this->session->set_flashdata('message', 'Failed to reset password. Please try again.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
            }
        } else{
            $this->session->set_flashdata('message', 'Failed to reset password. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
        }

        // $this->user();
    }

    public function stats($agreement_id)
    {
        // $agreement_id = $this->manager->get_details('agreement', array('section_id'=>$this->user['section_id']))[0]->agreement_id;

        $data = array(
            'ttlTrips' => $this->manager->count_data('trip', array('agreement_id'=>$agreement_id)),
            'dailyTrips' => $this->manager->count_data('trip', array('agreement_id'=>$agreement_id, 'in_datetime >'=>date('Y-m-d'))),
            'dailyVehicles' => $this->manager->count_distinct_where('trip', 'trip_vehicle_id', array('agreement_id' => $agreement_id, 'in_datetime >'=>date('Y-m-d'))),
            'estimated_quantity' => $this->manager->get_sum('agreement_item', 'estimated_quantity', array('agreement_id'=>$agreement_id)),
            //'dailyGrossWeight' => $this->manager->get_sum('trip', 'in_weight', array('agreement_id'=>$agreement_id, 'in_datetime>' => date('Y-m-d'))),
            'dailyGrossWeight' => $this->manager->get_details('weight', array('agreement_id' => $agreement_id, 'in_datetime'=> date('Y-m-d'))),
            'dailyItemWeight' => $this->manager->get_details('weight', array('agreement_id' => $agreement_id, 'in_datetime'=> date('Y-m-d'))),
            'dailyNetWeight' => $this->manager->get_details('weight', array('agreement_id' => $agreement_id, 'in_datetime'=> date('Y-m-d'))),
            'ttlNetWeight' => $this->manager->get_sum('weight', 'net_weight', array('agreement_id' => $agreement_id)),
        );
        return $data;
    }

    public function dash_bricks($section_id)
    {
        $role_id = $_SESSION['harbour']['role_id'];
        $section = $this->manager->get_details('section', array('section_id'=>$section_id))[0];
        $agreement = $this->manager->get_details('agreement', array('section_id'=>$section->section_id));

        if (isset($agreement[0]->agreement_id)) {
            $agr_id = $agreement[0]->agreement_id;
            $agr = "<a href='$role_id/agreement_dashboard/$agr_id'>".strtoupper($agreement[0]->agreement)."</a>";
            $stats = $this->stats($agr_id);
            $est_ttl_cost = $this->manager->est_ttl_cost($agr_id);
            $ttl_exp = $this->manager->ttl_exp($agr_id);
            
            $today = time();
            $difference = floor(($today - strtotime($agreement[0]->date_of_commencement) + 86400)/86400);

            $ttlTrips = $stats['ttlTrips'];
            $estWeight = $stats['estimated_quantity'];
            $dumpSite = round($stats['ttlNetWeight']/1000,2);
            $phyProg = round(($dumpSite/$estWeight)*100,2);
            $finProg = round(($ttl_exp->ttl_exp*100)/$est_ttl_cost->ttl_cost, 2);

        } else{
            $agr = 'Not Made';
            $difference = 0;
            $ttlTrips = 0;
            $estWeight = 0;
            $dumpSite = 0;
            $phyProg = 0;
            $finProg = 0;
        }

        $brick = "<div class='card col-md-12'>
                    <div class='card-body'>
                        <div class='row'>
                            <div class='col-md-4'>
                                <h4 class='card-title'>SECTION : ".strtoupper($section->section)."</h4>
                            </div>
                            <div class='col-md-8'>
                                <h4 class='card-title'>AGREEMENT : ".$agr."</h4>
                            </div>
                        </div>
                        <!-- CARDS -->
                        <div class='row'>
                            <!-- Column -->
                            <div class='col-md-2 col-lg-2'>
                                <div class='card card-hover'>
                                    <div class='box bg-warning text-center'>
                                        <h1 class='font-light text-white'><i class='mdi mdi-truck'></i><br> $difference </h1>
                                        <h6 class='text-white'>No of Days</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class='col-md-2 col-lg-2'>
                                <div class='card card-hover'>
                                    <div class='box bg-cyan text-center'>
                                        <h1 class='font-light text-white'><i class='mdi mdi-truck'></i><br> $ttlTrips </h1>
                                        <h6 class='text-white'>Total Trips</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class='col-md-2 col-lg-2'>
                                <div class='card card-hover'>
                                    <div class='box bg-success text-center'>
                                        <h1 class='font-light text-white'><i class='mdi mdi-weight'></i><br> $estWeight  T</h1>
                                        <h6 class='text-white'>Estimated Weight</h6>
                                    </div>
                                </div>
                            </div>
                             <!-- Column -->
                            <div class='col-md-2 col-lg-2'>
                                <div class='card card-hover'>
                                    <div class='box bg-info text-center'>
                                        <h1 class='font-light text-white'><i class='mdi mdi-weight-kilogram'></i><br> $dumpSite  T
                                        <h6 class='text-white'>Dumped at Site</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class='col-md-2 col-lg-2'>
                                <div class='card card-hover'>
                                    <div class='box bg-danger text-center'>
                                        <h1 class='font-light text-white'><i class='mdi mdi-ticket-percent'></i><br> $phyProg %</h1>
                                        <h6 class='text-white'>Physical Progress</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class='col-md-2 col-lg-2'>
                                <div class='card card-hover'>
                                    <div class='box bg-warning text-center'>
                                        <h1 class='font-light text-white'>&#x20B9;<br> $finProg %</h1>
                                        <h6 class='text-white'>Financial Progress</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";

        return $brick;
    }

    public function agreement_dashboard($agreement_id)
{
    $role_id = $_SESSION['harbour']['role_id'];
    $user_section_id = $this->user['section_id']; // Get the logged-in user's section ID

    // Fetch the agreement details by agreement_id
    $agreement = $this->manager->get_details('agreement', array('agreement_id' => $agreement_id));

    // Check if the agreement belongs to the user's section
    // if ($agreement[0]->section_id != $user_section_id) {
    //     // If the section IDs do not match, redirect or show an error
    //     $this->session->set_flashdata('message', 'Unauthorized access to this agreement.');
    //     redirect('contractor/index', 'refresh');
    //     return;  // Exit function to prevent further execution
    // }

    // Continue loading the dashboard if section ID matches
    $chainage = $this->manager->get_chainage($agreement[0]->agreement_id);
    $est_ttl_cost = $this->manager->est_ttl_cost($agreement[0]->agreement_id);
    $ttl_exp = $this->manager->ttl_exp($agreement[0]->agreement_id);

    if (isset($chainage)) {
        foreach ($chainage as $cng) {
            $ttl_weight = $this->manager->get_sum('trip', 'in_weight', array('agreement_item_id'=>$cng->chainage_item_id, 'onsite_chainage'=>$cng->chainage));
            $tare_weight = $this->manager->get_sum('trip', 'out_weight', array('agreement_item_id'=>$cng->chainage_item_id, 'onsite_chainage'=>$cng->chainage));
            $loss = $this->manager->get_sum('trip', 'onsite_loss', array('agreement_item_id'=>$cng->chainage_item_id, 'onsite_chainage'=>$cng->chainage));
            $item_weight = $ttl_weight - $tare_weight;
            $net_weight = $item_weight * (1 - $loss / 100);
            $cng->dumped = $net_weight;
        }
    }

    // Prepare data for the dashboard view
    $data = array(
        'role' => $role_id,
        'agreement' => $agreement,
        'chainage' => $chainage,
        'finprog' => round(($ttl_exp->ttl_exp * 100) / $est_ttl_cost->ttl_cost, 2),
        'stats' => $this->stats($agreement[0]->agreement_id)
    );

    // Load the dashboard view with the data
    $this->load->view('section/dashboard', ['data' => $data]);
    $this->load->view('footer');
}


public function generate_report($agreement_id)
{
    $user_section_id = $this->user['section_id']; // Get the logged-in user's section ID

    // Fetch the agreement details by agreement_id
    $agreement = $this->manager->get_details('agreement', array('agreement_id' => $agreement_id));

    // Check if the agreement belongs to the user's section
    // if (!isset($agreement) || $agreement[0]->section_id != $user_section_id) {
    //     // If the section IDs do not match or the agreement does not exist, redirect
    //     $this->session->set_flashdata('message', 'Unauthorized access to this agreement report.');
    //     redirect('contractor/index', 'refresh');
    //     return;  // Exit function to prevent further execution
    // }

    // Continue generating the report if section ID matches
    $where_agr = array('agreement_id' => $agreement_id);
    $location = $this->manager->get_details('agreement_location', $where_agr);
    $item = $this->manager->get_details('agreement_item', $where_agr);
    $vehicle = $this->manager->get_details('vehicle', array('vehicle_agreement_id' => $agreement_id));

    $trip_vehicle_id = $this->input->post('trip_vehicle_id');
    $agreement_location_id = $this->input->post('agreement_location_id');
    $agreement_item_id = $this->input->post('agreement_item_id');
    $onsite_chainage = $this->input->post('onsite_chainage');
    $card_no = $this->input->post('card_no');
    $date_from = $this->input->post('date_from');
    $date_to = $this->input->post('date_to');

    $fields = [];
    $fields['trip.agreement_id'] = $agreement_id;
    if ($trip_vehicle_id) { $fields['trip_vehicle_id'] = $trip_vehicle_id; }
    if ($agreement_location_id) { $fields['trip.agreement_location_id'] = $agreement_location_id; }
    if ($agreement_item_id) { $fields['trip.agreement_item_id'] = $agreement_item_id; }
    if ($onsite_chainage) { $fields['onsite_chainage'] = $onsite_chainage; }
    if ($card_no) { $fields['card_no'] = $card_no; }
    if ($date_from) { $fields['in_datetime >'] = $date_from; }
    if ($date_to) { $fields['in_datetime <'] = $date_to; }

    $trip = $this->manager->get_trip($fields);

    // Load the report view with the data
    $this->load->view('report', [
        'vehicle' => $vehicle,
        'agreement' => $agreement,
        'location' => $location,
        'item' => $item,
        'trip' => $trip,
        'role' => $this->user['role'],
        'data' => $fields
    ]);

    $this->load->view('footer.php');
}


    public function trip_single($trip_id)
    {
        $trip = $this->manager->get_details('trip', ['trip_id'=>$trip_id])[0];

        $data = array(
            'trip' => $trip,
            'location' => $this->manager->get_details('agreement_location', ['agreement_location_id'=>$trip->agreement_location_id])[0],
            'vehicle' => $this->manager->get_details('vehicle', ['vehicle_id'=>$trip->trip_vehicle_id])[0]
            );
        
        $this->load->view('trip_single', $data);
        $this->load->view('footer');
    }

    public function test()
    {
        $data = "My_Controller Tested fine";
        // echo $data;
        return $data;
    }

}
