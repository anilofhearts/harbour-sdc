<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_remove_shortcode_from_section extends CI_Migration {

        public function up()
        {
            $this->dbforge->drop_column('section', 'short_code');
        }

        public function down()
        {
            $fields = array(
                'short_code' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '10',
                    'null' => TRUE
                )
            );

            $this->dbforge->add_column('section', $fields);
        }


}
