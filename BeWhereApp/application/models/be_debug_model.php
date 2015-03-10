<?php

class Be_debug_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function log($description){
        $data = array(
           'Content' => $description
        );

        $this->db->insert('debug', $data); 
    }

}
