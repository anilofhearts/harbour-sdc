<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends CI_Model {

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

    public function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass f$
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function get_details($table, $array)
	{
		$query = $this->db->get_where($table, $array);
    
		if ($query->num_rows() > 0)
			{
           
		return $query->result();
            
		$query->free_result();
			}
     
	}
  
     public function get_custom($table, $array)
	{
$this->db->where($array);
$query = $this->db->get($table);
		if ($query->num_rows() > 0)
			{
		return $query->result();
		$query->free_result();
			}
	}

    public function get_last($table,$array)
    {
$this->db->from($table);
$this->db->order_by($array, "ASC");
$query = $this->db->get(); 
return $query->result();
    }    
    
    public function get_sort($table,$array,$field,$sort)
    {
$this->db->from($table);
$this->db->where($array);
$this->db->order_by($field, $sort);
$query = $this->db->get();
return $query->result();
    }    
    

    public function get_distinct($table, $dis)
    {
        $this->db->distinct();
        $this->db->select($dis);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0)
  			{
      		return $query->result();
      		$query->free_result();
  			}
    }

  public function get_distinct_where($table, $dis,$array)
  {
    $this->db->select();
    $this->db->where($array);
    $this->db->group_by($dis);
    $query = $this->db->get($table);
    
    if ($query->num_rows() > 0)
		{
  		return $query->result();
  		$query->free_result();
		} else{
      return 0;
    }
  }
         public function get_distinct_wherev2($table, $dis,$field,$array)
    {
       
        $this->db->select($field);
        $this->db->where($array);
        $this->db->group_by($dis);
        $query = $this->db->get($table);
        	if ($query->num_rows() > 0)
			{
		return $query->result();
		$query->free_result();
			}
    }
    public function insert_data($table, $array)
    {

        $this->db->set($array);
        $this->db->insert($table);
        return $this->db->insert_id();
    }
    public function insert_batch($table, $array)
    {
      $q = $this->db->insert_batch($table, $array);
      if ($q) {
        return true;
      } else{
        return false;
      }
    }
    public function delete_data($table, $array)
    {

        $this->db->where($array);
        $this->db->delete($table);

        return $this->db->affected_rows();
    }
    public function update_data($table, $data, $array)
    {
        $this->db->set($data);
        $this->db->where($array);
        $q = $this->db->update($table);
        if ($q) {
          return TRUE;
        } else {
          return FALSE;
        }
        
    }
    
       
        public function get_max($table, $field)
        {
    // SELECT max(`id`) as 'last' , `dev_id`, `lat`, `log`, `speed`, `time`, `driver` FROM `gps_log` WHERE dev_id = "no"
    $this->db->select_max($field);
    $query = $this->db->get($table);
        if ($query->num_rows() > 0)
			{
		$x =  $query->result_array();
            return $x['0'][$field];
			}
        }

        public function get_max_where($table, $field,$where)
        {
    $this->db->select_max($field);
    $this->db->where($where);
    $query = $this->db->get($table);

        if ($query->num_rows() > 0)
			{
		$x =  $query->result_array();
            return $x['0'][$field];
			}
        } 

    public function get_in($table , $field, $ids)
    {
       return  $this->get_custom($table,"$field in ($ids)");


    }
    public function get_first($table, $field)
        {
    // SELECT max(`id`) as 'last' , `dev_id`, `lat`, `log`, `speed`, `time`, `driver` FROM `gps_log` WHERE dev_id = "no"
        //SELECT * FROM `gps_log` WHERE `stamp` BETWEEN '2019-01-01 00:00:00.000000' AND '2019-01-01 23:59:00.000000'
    $this->db->select_min('gps_id');
    $this->db->where($field);
    $query = $this->db->get($table);
        if ($query->num_rows() > 0)
			{
		$g = $query->result();
     foreach($g as $d);
       $q = $this->get_details($table,array('gps_id'=>$d->gps_id));
            return $q;
			}
        }
   
   public function procedure($pro,$array)
   {
$query = $this->db->query( $pro, $array);
$g =  $query->result();
$query->next_result(); 
$query->free_result();
       return $g;
   }
  public function dis_where($table,$dis,$field,$array)  
{  
   $this->db->select('DISTINCT('.$dis.'), '.$field.'');  
   $this->db->from($table);  
   $this->db->where($array);  
   $query=$this->db->get();  
   $g =  $query->result();  
      $query->free_result();
      return $g;
}

  public function count_data($table, $where)
  {
    $query = $this->db->get_where($table, $where);

		if ($query->num_rows() > 0) {
  		return $query->num_rows();
  		$query->free_result();
		} else {
      return 0;
    }
      
  }

  public function get_trip($array)
  {
    /*if($array['fromAjax'] == 1){
      $this->db->where('trip.vehicle_id', $array['vehicle_id']);
      $this->db->where('trip.out_datetime IS NULL', null);
    } else{*/
      $this->db->where($array);
    //}
    
    $this->db->from('trip');
    $this->db->join('agreement', 'trip.agreement_id = agreement.agreement_id', 'left');
    $this->db->join('vehicle', 'vehicle.vehicle_id = trip.trip_vehicle_id', 'left');
    $this->db->join('quarry', 'quarry.quarry_id = trip.trip_quarry_id', 'left');
    $this->db->join('agreement_location', 'agreement_location.agreement_location_id = trip.agreement_location_id', 'left');
    $this->db->join('agreement_item', 'agreement_item.agreement_item_id = trip.agreement_item_id', 'left');
    $q = $this->db->get();

    if ($q->num_rows() > 0) {

      return $q->result();
      $q->free_result();

    }
    
  }

  // Vehicle list which are IN but not ON SITE
  public function vehicle_in($agreement_id)
  {
    $this->db->select('trip.trip_id, vehicle_no');
    $this->db->from('trip');
    $this->db->join('vehicle', 'vehicle.vehicle_id = trip.trip_vehicle_id', 'left');
    $this->db->where('onsite_datetime', NULL);
    $this->db->where('trip.agreement_id', $agreement_id);
    $q = $this->db->get();

    if ($q->num_rows()>0) {
      return $q->result();
      $q->free_result();
    }
  }

  // Vehicle list which are ON SITE but not IN
  public function vehicle_onsite()
  {
    $this->db->select('trip.vehicle_id');
    $this->db->from('trip');
    $this->db->join('vehicle', 'vehicle.vehicle_id = trip.vehicle_id', 'left');
    $this->db->where('onsite_datetime !=', NULL);
    $this->db->where('in_datetime', NULL);
    $q = $this->db->get();

    if ($q->num_rows()>0) {
      return $q->row_array();
      $q->free_result();
    }
  }
    
  // Vehicle list which are OUT and not IN
  public function vehicle_out()
  {
  $this->db->select('vehicle_id')->from('vehicle');
  $this->db->where('`vehicle_id` NOT IN (SELECT `vehicle_id` FROM `trip` WHERE `in_datetime` IS NULL)', NULL, FALSE);

  $q = $this->db->get();
  if ($q->num_rows()>0) {
      return $q->result();
      $q->free_result();
    }
  }






     public function decode($hex)
    {
       $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
     return base64_decode($string);

    }
public function encode($string)
{
        $otp = base64_encode($string);
        $otp = strtoupper(implode(unpack("H*", $otp)));
        return $otp;
}


public function handler($cmd) {  

    if (substr(php_uname(), 0, 7) == "Windows"){ 
       pclose(popen("start /B ". $cmd, "r"));  
    } 
    else if(substr(php_uname(), 0, 7) == "Darwin") {
        exec($cmd . " &");

    }else
    {
        exec($cmd . " > /dev/null &");   
    } 
  
} 

	


  public function email_bill($html)
    {
       $this->load->library('email');
       $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = "itdocktechnologies@gmail.com";
        $config['smtp_pass']    = "v@YU$3N1K";
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['validate'] = TRUE; // bool whether to validate email or not     

        $this->email->initialize($config);

        $this->email->from('itdocktechnologies@gmail.com', 'ITDOCK TECHNOLOGIES (EDUDOCK)');
        $this->email->to('praveen.pl03@gmail.com'); 

        $this->email->subject('Admission Registration Done');
        $this->email->message($html);  
        $this->email->send();

        echo $this->email->print_debugger();

       // $this->load->view('email_view');
    }
    public function swift_mail($body)
    {
        $this->load->library('swift_mail');
        $transport = (new Swift_SmtpTransport('smtp.zoho.com', 465, 'ssl'))
        ->setUsername("care@itdock.in")
        ->setPassword("V@yu$3n1k");
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message('Registration Alert'))
        ->setFrom(["care@itdock.in" => 'edudock.in'])
        ->setTo(['angelkidscarehome@gmail.com'])
        ->setBody($body)
        ->setContentType('text/html');
if (!$mailer->send($message, $failures))
{
  echo "Failures:";
  print_r($failures);
}
    }
        public function swift_mail_add($body,$subject,$to)
    {
        $this->load->library('swift_mail');
        $transport = (new Swift_SmtpTransport('smtp.zoho.com', 465, 'ssl'))
        ->setUsername("care@itdock.in")
        ->setPassword("V@yu$3n1k");
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message($subject))
        ->setFrom(["care@itdock.in" => 'edudock.in'])
        ->setTo([$to])
        ->setBody($body)
        ->setContentType('text/html');
if (!$mailer->send($message, $failures))
{
  echo "Failures:";
  print_r($failures);
}
    }

function random_color() {
$colour =  sprintf('#%06X', mt_rand(0, 0xFFFFFF));
     
$rgb = $this->HTMLToRGB($colour);
$hsl = $this->RGBToHSL($rgb);
 
if($hsl->lightness > 188) {
$font = "black";
}else
{
  $font = "whitesmoke";  
}
    return array("color"=>$colour,"light"=>$font);
        
}
    function random_icon()
    {

$query = $this->db->query("SELECT trim(icon) as icon FROM `icons` order by rand() limit 1");
$ic =  $query->result();
        foreach($ic as $v);
      echo  trim($v->icon);
   

            
    }
function HTMLToRGB($htmlCode)
  {
    if($htmlCode[0] == '#')
      $htmlCode = substr($htmlCode, 1);

    if (strlen($htmlCode) == 3)
    {
      $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
    }

    $r = hexdec($htmlCode[0] . $htmlCode[1]);
    $g = hexdec($htmlCode[2] . $htmlCode[3]);
    $b = hexdec($htmlCode[4] . $htmlCode[5]);

    return $b + ($g << 0x8) + ($r << 0x10);
  }

function RGBToHSL($RGB) {
    $r = 0xFF & ($RGB >> 0x10);
    $g = 0xFF & ($RGB >> 0x8);
    $b = 0xFF & $RGB;

    $r = ((float)$r) / 255.0;
    $g = ((float)$g) / 255.0;
    $b = ((float)$b) / 255.0;

    $maxC = max($r, $g, $b);
    $minC = min($r, $g, $b);

    $l = ($maxC + $minC) / 2.0;

    if($maxC == $minC)
    {
      $s = 0;
      $h = 0;
    }
    else
    {
      if($l < .5)
      {
        $s = ($maxC - $minC) / ($maxC + $minC);
      }
      else
      {
        $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
      }
      if($r == $maxC)
        $h = ($g - $b) / ($maxC - $minC);
      if($g == $maxC)
        $h = 2.0 + ($b - $r) / ($maxC - $minC);
      if($b == $maxC)
        $h = 4.0 + ($r - $g) / ($maxC - $minC);

      $h = $h / 6.0; 
    }

    $h = (int)round(255.0 * $h);
    $s = (int)round(255.0 * $s);
    $l = (int)round(255.0 * $l);

    return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
  }
    public function hash_it($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
    public function verify_hash($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }

    public function log($event, $data)
    {
        $data = serialize($data);
   $this->insert_data("logs",array("event"=>$event,"data"=>$data,"time"=>time(),"ip"=>$this->ip()));

    }
public function select_join($table,$join,$join_on,$where)
{
$this->db->select('*');
$this->db->from($table);
$this->db->join($join, $join_on, 'left');
$this->db->where($where);
$query = $this->db->get();
     if ($query->num_rows() > 0)
  			{
      		return $query->result();
      		$query->free_result();
  			}
}
    public function routing_section($where)
    {
        $this->db->select('*');
$this->db->from("section");
$this->db->join("subdivision", "section.subdivision_id = subdivision.subdivision_id");
$this->db->join("division", "subdivision.division_id = division.division_id");
$this->db->join("circle", "division.circle_id = circle.circle_id");
$this->db->where($where);
$query = $this->db->get();
     if ($query->num_rows() > 0)
  			{
      		return $query->result();
      		$query->free_result();
  			}
    }
    public function routing_subdivision($where)
    {
        $this->db->select('*');
$this->db->from("subdivision");
$this->db->join("division", "subdivision.division_id = division.division_id");
$this->db->join("circle", "division.circle_id = circle.circle_id");
$this->db->where($where);
$query = $this->db->get();
     if ($query->num_rows() > 0)
  			{
      		return $query->result();
      		$query->free_result();
  			}
    } 

    public function routing_division($where)
    {
        $this->db->select('*');
$this->db->from("division");
$this->db->join("circle", "division.circle_id = circle.circle_id");
$this->db->where($where);
$query = $this->db->get();
     if ($query->num_rows() > 0)
  			{
      		return $query->result();
      		$query->free_result();
  			}
    }

  public function get_sum($table, $sum_on, $where)
  {
    $this->db->select_sum($sum_on);
    $this->db->where($where);
    $query = $this->db->get($table);

    if ($query->num_rows() > 0)
    {
      return $query->result()[0]->$sum_on;
      $query->free_result();
    } else
    {
      return 0;
    }
    
  }

  public function count_distinct_where($table, $dis,$array)
  {
    $this->db->select();
    $this->db->where($array);
    $this->db->group_by($dis);
    $query = $this->db->get($table);
    
    return $query->num_rows();
  }

  public function get_chainage($agreement_id)
  {
    $q = $this->db->query("SELECT * 
            FROM chainage C
            LEFT JOIN agreement_location L ON L.agreement_location_id = C.chainage_agr_loc_id 
            LEFT JOIN agreement_item I ON I.agreement_item_id = C.chainage_item_id
            WHERE C.chainage_agr_loc_id IN (
              SELECT agreement_location_id
              FROM agreement_location
              WHERE agreement_id = $agreement_id)"
            );

    if ($q->num_rows() > 0)
        {
          return $q->result();
          $q->free_result();
        }
  }

  public function est_ttl_cost($agreement_id)
  {
    $q = $this->db->query("
        SELECT agreement_id, amount AS ttl_cost
        FROM agreement
        WHERE agreement_id = $agreement_id
        ");
    return $q->result()[0];
    $q->free_result();
  }

  public function ttl_exp($agreement_id)
  {
    $q = $this->db->query("
        SELECT S.agreement_id, round(SUM(net_weight*estimated_rate)/10000, 2) AS ttl_exp
        FROM item_summary_cumulative S
        LEFT JOIN agreement_item I ON I.item = S.item 
        WHERE S.agreement_id = $agreement_id
        ");
    return $q->result()[0];
    $q->free_result();
  }
  public function insert_login_record($user_id, $session_id)
  {
      $data = array(
        'user_id' => $user_id,
        'session_id' => $session_id,
        'created_at' => date('Y-m-d H:i:s')
          // Add any other fields you want to store for the login record
      );
  
      $this->insert_data('user_sessions', $data);
      // 'user_sessions' is the corrected name of your login records table
  }
  
public function update_session_status($user_id, $status, $invalidate_other_sessions = false) {
  if ($invalidate_other_sessions) {
      // Invalidate all other sessions for the same user
      $this->db->where('user_id', $user_id)
               ->where('session_id !=', session_id())
               ->update('user_sessions', array('status' => 'invalid'));
  }

  // Update the current session status
  $this->db->where('user_id', $user_id)
           ->where('session_id', session_id())
           ->update('user_sessions', array('status' => $status));
}
}

