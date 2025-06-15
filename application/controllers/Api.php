<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

public function check_login()
{
    // header('Access-Control-Allow-Origin: *');
	header('content-type:application/json');
    $post_data = file_get_contents("php://input");

	$request = json_decode($post_data);



$out = array();
if(!empty($request->userid) && !empty($request->password)){

	$user_info = $this->manager->get_details('user',array('name'=>$request->userid,'status'=>'1'));

	if(isset($user_info))
             {
            foreach($user_info as $data);
             $check = $this->manager->verify_hash($request->password, $data->password);

                 if($check == 1)
                 {

$this->manager->log("login success", $request);

$out['state'] = "200";
//$out = array(array("state"=>"200", 'agreement_id'=>$agreement_id));
}else
{
	$out = array(array("state"=>"error password"));
}
}else
{

	$out = array(array("state"=>"invalid user"));
}

}else
{
	$out = array(array("state"=>"no post"));
}
print_r(json_encode($out));
}

	public function update_onsite()
	{
		header('content-type:application/json');
	    $post_data = file_get_contents("php://input");

		$request = json_decode($post_data);

		$trip_id = $request->trip_id;
		$data = array(
            'onsite_datetime' => date('Y-m-d H:i:s'),
            'onsite_loss' => $request->onsite_loss,
            'actual_chainage' => $request->actual_chainage,
            'lat_long' => $request->lat_long,
            'onsite_image' => $request->onsite_image,
            'remarks' => $request->remarks,
            'onsite_user_id' => $request->userid
        );


        $this->manager->log('Updating  onsite Trip id-'.$trip_id, $data);
        $q = $this->manager->update_data('trip', $data, array('trip_id'=>$trip_id));

        if($q) {
        	$out['state'] = "200";
        	$out['data'] = $request;
        } else{
        	$out['state'] = "Updation failed!";
        }
		

		print_r(json_encode($out));
	}

    public function vehicle_list()
    {
        header('content-type:application/json');
        $post_data = file_get_contents("php://input");

        $request = json_decode($post_data);
        $out['list'] = array();

        $x = array();
        $y = array();
        $user = $this->manager->get_details('user', array('name'=>$request->userid,'status'=>'1'));
        $agreement_id = $this->manager->get_details('agreement', array('section_id'=>$user[0]->section_id, 'date_of_completion'=>null))[0]->agreement_id;

        $vehicles_in = $this->manager->vehicle_in($agreement_id);
        if(isset($vehicles_in))
        {
            foreach($vehicles_in as $data)
            {
                $x['trip_id'] = $data->trip_id;
                $x['vehicle_no'] = $data->vehicle_no;
                $y[] = $x;
            }
        }

        $out['list'] = $y;
        print_r(json_encode($out));
    }

    public function get_trip()
    {
        header('content-type:application/json');
        $post_data = file_get_contents("php://input");

        $request = json_decode($post_data);
        $out['list'] = array();

        $x = array();
        $y = array();

        $trip = $this->manager->get_trip(array('trip_id'=>$request->trip_id));

        if(isset($trip))
        {
            $x['trip_id'] = $trip[0]->trip_id;
            $x['trip_no'] = $trip[0]->trip_no;
            $x['card_no'] = $trip[0]->card_no;
            $x['vehicle_no'] = $trip[0]->vehicle_no;
            $x['location'] = $trip[0]->location;
            $x['item'] = $trip[0]->item;
            $x['chainage'] = $trip[0]->onsite_chainage;
            $x['in_datetime'] = $trip[0]->in_datetime;
            $x['in_weight'] = $trip[0]->in_weight;
          //  $x['in_image'] = $trip[0]->in_image; error here
            $x['in_user_id'] = $trip[0]->in_user_id;
            // $x['onsite_datetime'] = $trip[0]->onsite_datetime;
            // $x['onsite_image'] = $trip[0]->onsite_image;
            // $x['onsite_user_id'] = $trip[0]->onsite_user_id;
            $x['onsite_loss'] = $trip[0]->onsite_loss;
            // $x['actual_chainage'] = $trip[0]->actual_chainage;
            // $x['remarks'] = $trip[0]->remarks;

            $y[] = $x;
        }

        $out['list'] = $y;
        print_r(json_encode($out));
    }
}
