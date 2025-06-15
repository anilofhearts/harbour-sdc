<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

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
    public function __construct()
    {
        parent::__construct();
        // authentication/permissions code, or whatever you want to put here
    }
	public function index()
	{
         $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'admin') {
            redirect($role_id,'refresh');
        }
     // $this->load->view('admin/header_admin');
	  $this->load->view('admin/navbar');
    $data['circle'] = $this->manager->get_details("circle",array());

		$this->load->view('admin/chart',$data);
		$this->load->view('footer');
	}

  public function add_node()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('table', 'Invalid Selection', 'required');
        $this->form_validation->set_rules('id', 'Invalid ID', 'required');
        $this->form_validation->set_rules('name', 'Invalid Name', 'required');
        $this->form_validation->set_rules('node', 'Invalid Node', 'required');
        if ($this->form_validation->run() == TRUE)
        {
        //   print_r($_POST);
            $id = uniqid();
            $post = $this->input->post(array("table","id","name"),true);
            $this->manager->log("Making Chart", $post);
            if($post['table'] == 'circle')
            {

           $this->manager->insert_data($post['table'],array("circle_id"=>$id,"circle"=>$post['name']));
            }else if($post['table'] == 'division')
            {

           $this->manager->insert_data("division",
                        array("division_id"=>$id,
                              "division"=>$post['name'],
                                "circle_id"=>$post['id']
                              ));
            }
            else if($post['table'] =='subdivision')
            {

       $this->manager->insert_data("subdivision",
                        array("subdivision_id"=>$id,
                              "subdivision"=>$post['name'],
                              "division_id"=>$post['id']));
            } else if($post['table'] == 'section')
            {

       $this->manager->insert_data("section",
                        array("section_id"=>$id,
                              "section"=>$post['name'],
                              "subdivision_id"=>$post['id']));
            }else
            {
                $this->manager->log("Making Chart Error", $post);
            }

            $pass = $this->manager->hash_it("harbour");
        $this->manager->insert_data("user",
                                    array("password"=>$pass,
                                          "section_id"=>$id,
                                          "name"=>rand(11111,99999),
                                          "status"=>1,
                                          "role_id"=>$post['table']));

        } else{

            $this->manager->log("Making Chart Invalid Data", file_get_contents('php://input'));
           echo validation_errors('<h1 class="error">', '</h1>');
        }


    }
    public function remove_node()
    {
        // Clear output buffer to remove any extra content
        ob_clean();
        $this->output->set_content_type('application/json');
    
        $this->load->library('form_validation');
        $this->form_validation->set_rules('table', 'Invalid Selection', 'required');
        $this->form_validation->set_rules('id', 'Invalid ID', 'required');
    
        // Debugging: Log POST data and CSRF token
        log_message('info', 'POST Data: ' . json_encode($this->input->post()));
        log_message('info', 'CSRF Token: ' . $this->security->get_csrf_hash());
    
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post(array("table", "id"), true);
    
            // Validate the table name
            $allowed_tables = ['circle', 'division', 'subdivision', 'section'];
            if (!in_array($post['table'], $allowed_tables)) {
                echo json_encode(['success' => false, 'message' => 'Invalid table']);
                return;
            }
    
            // Perform deletion
            try {
                switch ($post['table']) {
                    case 'circle':
                        $this->manager->delete_data($post['table'], ["circle_id" => $post['id']]);
                        break;
                    case 'division':
                        $this->manager->delete_data("division", ["division_id" => $post['id']]);
                        break;
                    case 'subdivision':
                        $this->manager->delete_data("subdivision", ["subdivision_id" => $post['id']]);
                        break;
                    case 'section':
                        $this->manager->delete_data("section", ["section_id" => $post['id']]);
                        $this->manager->update_data("user", ["status" => 0], ["section_id" => $post['id']]);
                        break;
                }
    
                // Send success response
                echo json_encode(['success' => true, 'message' => 'Node deleted successfully']);
            } catch (Exception $e) {
                log_message('error', 'Exception during deletion: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'An error occurred while deleting the node.']);
            }
        } else {
            // Validation failed
            log_message('error', 'Validation Errors: ' . validation_errors());
            echo json_encode(['success' => false, 'message' => validation_errors()]);
        }
        exit; // Stop further processing
    }
    
    public function view_branch()
    {
        if(isset($_GET['table']) && isset($_GET['id'])&& isset($_GET['name']))
        {

        $get = $this->input->get(array("table","id","name"),true);
        $data['name'] =   $get['name'];
        $data['id'] =   $get['id'];
        $data['user'] = $this->manager->get_details("user",array("section_id"=>$get['id']));

        $this->load->view('admin/view_branch',$data);
        }
    }
    public function update_pass()
{
    
    // Load form validation library
    $this->load->library('form_validation');
    log_message('error', 'Update password function called');
    // Set validation rules
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('phone_no', 'Mobile', 'required');
    $this->form_validation->set_rules('section_id', 'Section ID', 'required');
    log_message('error', 'Validation rules set');
    // Validate the form input
    if ($this->form_validation->run() == TRUE) {
        // Sanitize and get form data
        $post = $this->input->post(array("name", "password", "phone_no"), true);
        $id = $this->input->post("section_id", true);
        // Hash the password if provided
        if (!empty($post['password'])) {
            log_message('debug', 'Password provided Post empty');
            $post['password'] = $this->manager->hash_it($post['password']);

        } else {
            unset($post['password']); // Do not update the password if it's empty
        }
        // Update the user data
        $update = $this->manager->update_data("user", $post, array("section_id" => $id));

        // Check if the update was successful
        if ($update) {
            $this->session->set_flashdata('success', 'User updated successfully!');
            redirect("welcome");
        } else {
            $this->session->set_flashdata('error', 'Failed to update user. Please try again.');
            redirect("welcome");
        }
    } else {
        // Log validation errors for debugging
        log_message('error', validation_errors());
        
        // Display validation errors
        $this->session->set_flashdata('error', validation_errors());
        redirect("welcome");
    }
}

    public function circle_view()
    {
        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'admin') {
            redirect($role_id,'refresh');
        }
         // $this->load->view('admin/header_admin');
	$this->load->view('admin/navbar');
     $data['circle'] = $this->manager->get_details("circle",array());
	$this->load->view('admin/circle_view',$data);
	$this->load->view('footer');
    }
    public function division_view()
    {
        $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'admin') {
            redirect($role_id,'refresh');
        }
         // $this->load->view('admin/header_admin');
	$this->load->view('admin/navbar');
     $data['division'] =  $this->manager->routing_division(array());
	$this->load->view('admin/division_view',$data);
	$this->load->view('footer');
    }
    public function subdivision_view()
    {
         $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'admin') {
            redirect($role_id,'refresh');
        }
         // $this->load->view('admin/header_admin');
	$this->load->view('admin/navbar');
     $data['subdivision'] = $this->manager->routing_subdivision(array());
	$this->load->view('admin/subdivision_view',$data);
	$this->load->view('footer');
    }
    public function section_view()
    {
         $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'admin') {
            redirect($role_id,'refresh');
        }
       // $this->load->view('admin/header_admin');
	$this->load->view('admin/navbar');
     $data['section'] = $this->manager->routing_section(array());
       // print_r($data['section']);
	$this->load->view('admin/section_view',$data);
	$this->load->view('footer');
    }

    public function manage_users($role, $name)
    {
 $role_id = $_SESSION['harbour']['role_id'];
        if ($role_id != 'admin') {
            redirect($role_id,'refresh');
        }
      $this->load->view('admin/navbar');
      $data = $this->users($role, $name);
      # echo "<pre>"; print_r($data); exit;
      $this->load->view('admin/users', ['data'=>$data]);
      $this->load->view('footer');
    }

    public function add_edit_user()
    {
      $role = $this->input->post('role');
      $name = $this->input->post('name');
      $this->add_edit_user_profile();
      $this->manage_users($role, $name);
    }

    public function delete_user($user_id, $role, $name)
    {
      $this->delete_user_data($user_id);
      $this->manage_users($role, $name);
    }

    public function reset_password()
    {
      $role = $this->input->post('role');
      $name = $this->input->post('name');
      $this->reset_user_password();
      $this->manage_users($role, $name);
    }

}
