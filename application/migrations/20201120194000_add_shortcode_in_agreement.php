<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_shortcode_in_agreement extends CI_Migration {

        public function up()
        {
            $fields = array(
                'short_code' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '10',
                    'null' => TRUE,
                    'after' => 'estimated_quantity'
                )
            );

            $this->dbforge->add_column('agreement', $fields);
        }

        public function down()
        {
            $this->dbforge->drop_column('agreement', 'short_code');
        }
}
