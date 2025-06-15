<?php

class Migrate extends CI_Controller
{

        public function index()
        {
                $this->load->library('migration');

                if(!$this->migration->latest()) {
                    echo 'Error '.$this->migration->error_string();
                } else{
                    echo 'Migration ran successfully!';
                }
        }

}
