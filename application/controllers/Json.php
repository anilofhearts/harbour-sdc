<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->session->has_userdata('harbour')) {
            $this->user['section_id'] = $_SESSION['harbour']['section_id'];
            $this->user['user_id'] = $_SESSION['harbour']['user_id'];
            $this->load->model('Manager');
            $userinfo = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('user_id'=>$this->user['user_id']))[0];
        }
    }

    function getDivision() {
        header('content-type:application/json');
        $postData = $this->input->post();
        $data = $this->manager->get_details("division", $postData);
        print_r(json_encode((array)$data));
    }

    function getSubdivision() {
        header('content-type:application/json');
        $postData = $this->input->post();
        $data = $this->manager->get_details("subdivision", $postData);
        print_r(json_encode((array)$data));
    }

    function getSection() {
        header('content-type:application/json');
        $postData = $this->input->post();
        $data = $this->manager->get_details("section", $postData);
        print_r(json_encode((array)$data));
    }

    function getItem() {
        header('content-type:application/json');
        $postData = $this->input->post();
        $data = $this->manager->get_details("work", $postData);
        print_r(json_encode((array)$data));
    }

    function getTrip() {
        header('content-type:application/json');
        $postData = $this->input->post();
        $postData['out_datetime IS NULL'] = null;
        $data = $this->manager->get_trip($postData);
        print_r(json_encode((array)$data));
    }

    function getLocation() {
        header('content-type:application/json');
        $postData = $this->input->post();
        $data = $this->manager->get_details("agreement_location", $postData);
        $data['trip_no'] = 101;
        $data['card_no'] = $this->manager->get_max_where('trip', 'card_no', $postData);
        print_r(json_encode((array)$data));
    }

    public function getChainage() {
        header('content-type:application/json');
        $postData = $this->input->post();
        $data = $this->manager->get_details('chainage', $postData);
        print_r(json_encode((array)$data));
    }

    public function addUpdateTrip($trip_id = '') {
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        $trip_id = $data['trip_id'] ?? '';
        $trip_type = $data['trip_type'] ?? '';
        $agreement_id = $data['agreement_id'] ?? '';
        $trip_vehicle_id = $data['trip_vehicle_id'] ?? '';

        if (!$trip_vehicle_id || !$agreement_id) {
            echo "Missing required fields";
            exit();
        }

        $next_trip_no = $this->manager->get_max_where('trip', 'trip_no', array('agreement_id' => $agreement_id, 'in_datetime >' => date('Y-m-d'))) + 1;
        $next_card_no = $this->manager->get_max_where('trip', 'card_no', array('agreement_id' => $agreement_id)) + 1;

        if ($trip_type == 'new') {
            $tripData = array(
                'agreement_id' => $agreement_id,
                'agreement_location_id' => $data['agreement_location_id'] ?? null,
                'onsite_chainage' => $data['onsite_chainage'] ?? null,
                'in_datetime' => date('Y-m-d H:i:s'),
                'trip_no' => $next_trip_no,
                'card_no' => $next_card_no,
                'trip_vehicle_id' => $trip_vehicle_id,
                'agreement_item_id' => $data['agreement_item_id'] ?? null,
                'in_weight' => $data['weight'] ?? null,
                'in_image' => $data['in_image'] ?? null,
                'in_user_id' => $this->user['user_id']
            );

            $this->manager->log("Inserting Trip", $tripData);
            $q = $this->Manager->insert_data('trip', $tripData);
            echo "tripCard/$q";
            exit();

        } elseif ($trip_type == 'onsite') {
            $onsiteData = array(
                'onsite_datetime' => date('Y-m-d H:i:s'),
                'onsite_loss' => $data['onsite_loss'] ?? null,
                'onsite_user_id' => $this->user['user_id']
            );

            $this->manager->log('Updating onsite Trip id-'.$trip_id, $onsiteData);
            $q = $this->Manager->update_data('trip', $onsiteData, array('trip_id' => $trip_id));

        } else {
            $outData = array(
                'out_weight' => $data['weight'] ?? null,
                'out_datetime' => date('Y-m-d H:i:s'),
                'out_user_id' => $this->user['user_id']
            );

            $this->manager->log('Updating Trip out id-'.$trip_id, $outData);
            $q = $this->Manager->update_data('trip', $outData, array('trip_id' => $trip_id));

            echo "weightmentCard/$trip_id";
            exit();
        }

        if ($q) {
            $this->session->set_flashdata('message', 'Congratulation! Record added/update successfully.');
            $this->session->set_flashdata('messageClass', 'alert-success');
            echo "trip";
        } else {
            $this->session->set_flashdata('message', 'Failed to add/update record. Please try again.');
            $this->session->set_flashdata('messageClass', 'alert-danger');
            echo "trip";
        }
    }
}
