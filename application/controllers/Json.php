<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
function __construct() {
         //check user log in before process all data to db
         parent::__construct();
        if ($this->session->has_userdata('harbour')) {

            $this->user['section_id'] = $_SESSION['harbour']['section_id'];
            $this->user['user_id'] = $_SESSION['harbour']['user_id'];
            $this->load->model('Manager');

            $userinfo = $this->manager->select_join('user', 'designation', 'user.designation_id = designation.designation_id', array('user_id'=>$this->user['user_id']))[0];


        }

}

 function getDivision()
{
    header('content-type:application/json');
    $postData = $this->input->post();
   	$data = $this->manager->get_details("division",$postData);
    $out= (array)$data;
    print_r(json_encode($out));
}

function getSubdivision()
{
	header('content-type:application/json');
	$postData = $this->input->post();
   	$data = $this->manager->get_details("subdivision",$postData);
    $out= (array)$data;
    print_r(json_encode($out));
}

function getSection()
{
	header('content-type:application/json');
	$postData = $this->input->post();
   	$data = $this->manager->get_details("section",$postData);
    $out= (array)$data;
    print_r(json_encode($out));
}

function getItem()
{
	header('content-type:application/json');
	$postData = $this->input->post();
   	$data = $this->manager->get_details("work",$postData);
    $out= (array)$data;
    print_r(json_encode($out));
}

function getTrip()
{
	header('content-type:application/json');
	$postData = $this->input->post();
	$postData['out_datetime IS NULL'] = null;
	//echo $postData;
	//exit;
	//$data = $this->manager->get_details("trip",$postData);
	$data = $this->manager->get_trip($postData);
    $out= (array)$data;
    print_r(json_encode($out));
}

function getLocation()
{
	header('content-type:application/json');
	$postData = $this->input->post();
	//console.log($postData);
   	$data = $this->manager->get_details("agreement_location",$postData);
   	$data['trip_no'] = 101;
   	$data['card_no'] = $this->manager->get_max_where('trip', 'card_no', $postData);
    $out= (array)$data;
    print_r(json_encode($out));
}

public function getChainage()
{
	header('content-type:application/json');
	$postData = $this->input->post();
	$data = $this->manager->get_details('chainage', $postData);
	$out= (array)$data;
    print_r(json_encode($out));
}
   public function addUpdateTrip($trip_id='')
    {
        $this->load->library('form_validation');

        $trip_id = $this->input->post('trip_id');
        $trip_type = $this->input->post('trip_type');
        $agreement_id = $this->input->post('agreement_id');
        $this->form_validation->set_rules('trip_vehicle_id', 'Vehicle No', 'required');


        if ($this->form_validation->run() == TRUE) {

            $trip_vehicle_id = $this->input->post('trip_vehicle_id');
            $trip_id = $this->input->post('trip_id');

            $next_trip_no = $this->manager->get_max_where('trip', 'trip_no',array('agreement_id' => $agreement_id, 'in_datetime >'=>date('Y-m-d'))) + 1;
            $next_card_no = $this->manager->get_max_where('trip', 'card_no', array('agreement_id'=>$agreement_id)) + 1;


            if ($trip_type=='new') {
                $data = array(
                    'agreement_id' => $agreement_id,
                    'agreement_location_id' => $this->input->post('agreement_location_id'),
                    'onsite_chainage' => $this->input->post('onsite_chainage'),
                    'in_datetime' => date('Y-m-d H:i:s'),
                    // 'trip_no' => $this->input->post('trip_no'),
                    // 'card_no' => $this->input->post('card_no'),
                    'trip_no' => $next_trip_no,
                    'card_no' => $next_card_no,
                    'trip_vehicle_id' => $trip_vehicle_id,
                    // 'trip_quarry_id' => $this->input->post('trip_quarry_id'),
                    'agreement_item_id' => $this->input->post('agreement_item_id'),
                    'in_weight' => $this->input->post('weight'),
                    'in_image' => rawurlencode($this->input->post('in_image')),
                    'in_user_id' => $this->user['user_id']
                );

                 //print_r($data);
                 //exit();
                // if ($trip_id > 0) {
                //     $this->manager->log('Updating Trip in id-'.$trip_id, $data);
                //     $q = $this->Manager->update_data('trip', $data, array('trip_id'=>$trip_id));
                // } else{
                    $this->manager->log("Inserting Trip", $data);
                    $q = $this->Manager->insert_data('trip', $data);
                // }



                echo "tripCard/$q";
                exit();

            } elseif ($trip_type=='onsite') {      // ONSITE TRIP
                $data = array(
                    'onsite_datetime' => date('Y-m-d H:i:s'),
                    'onsite_loss' => $this->input->post('onsite_loss'),
                    'onsite_user_id' => $this->user['user_id']
                );



                $this->manager->log('Updating  onsite Trip id-'.$trip_id, $data);
                $q = $this->Manager->update_data('trip', $data, array('trip_id'=>$trip_id));

            } else {    // OUT trip
                $data = array(
                    'out_weight' => $this->input->post('weight'),
                    'out_datetime' => date('Y-m-d H:i:s'),
                    'out_user_id' => $this->user['user_id']
                );

                $this->manager->log('Updating Trip out id-'.$trip_id, $data);
                $q = $this->Manager->update_data('trip', $data, array('trip_id'=>$trip_id));


                echo "weightmentCard/$trip_id";
                 exit();
            }

            if ($q) {
                //print_r($q);
                $this->session->set_flashdata('message', 'Congratulation! Record added/update successfully.');
                $this->session->set_flashdata('messageClass', 'alert-success');
              echo "trip";
                 exit();
            } else{
                $this->session->set_flashdata('message', 'Failed to add/update record. Please try again.');
                $this->session->set_flashdata('messageClass', 'alert-danger');
                $this->trip();
            }

        } else{
              echo "trip";
             exit();
        }
    }

}
