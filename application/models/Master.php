<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Model {

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

	public function typeOfWork()
	{
		$options = array(
			'Break Water' => 'Break Water',
			'Road Repair' => 'Road Repair',
			'Road Construction' => 'Road Construction' );
		return $options;
	}

	public function typeOfVehicle()
	{
		$options = array(
			'Truck' => 'Truck',
			'Container' => 'Container');
		return $options;
	}

	public function typeOfTrip()
	{
		$options = array(
			'in' => 'IN',
			'out' => 'OUT',
			'onsite' => 'ON SITE');
		return $options;
	}

	public function category_of_stone()
	{
		$options = array(
			'5-10 Kg' => '5-10 Kg',
            '10-200 Kg' => '10-200 Kg',
            '300-500 Kg' => '300-500 Kg');
		return $options;
	}

	public function unit()
	{
		$options = array(
			'Ea' => 'Ea',
			'Kg' => 'Kg',
			'Ton' => 'Ton');
		return $options;
	}
/*
	public function getDivision($postData)
	{
		print_r($postData);
		$this->db->select('division_id, division');
		$this->db->where('circle_id', $postData);
		$q = $this->db->get('division');
		return $q->result();
	}
*/
	public function chainage()
	{
		$options = array(
			'0 - 20' => '0 - 20',
			'20 - 30' => '20 - 30',
			'30 - 60' => '30 - 60',
			// '60 - 90' => '60 - 90',
			// '90 - 120' => '90 - 120',
			// '120 - 150' => '120 - 150',
			// '150 - 180' => '150 - 180',
			// '180 - 210' => '180 - 210',
			// '210 - 240' => '210 - 240',
			// '240 - 270' => '240 - 270',
			// '270 - 300' => '270 - 300',
			// '300 - 330' => '300 - 330',
			// '330 - 360' => '330 - 360',
			// '360 - 390' => '360 - 390',
			// '390 - 420' => '390 - 420',
			// '420 - 430' => '420 - 430',
			// '430 - 490' => '430 - 490',
		);
		return $options;
	}
}