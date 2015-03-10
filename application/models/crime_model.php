<?php

class Crime_model extends CI_Model {
    
    const TABLENAME = "crimes";
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function add_crime($crime_name, $description){
        $params = array(
           'Crime_Name' => $crime_name ,
           'Crime_Description' => $description
        );
        //var_dump($params);
        $this->db->insert(Crime_model::TABLENAME, $params); 
    }
    
    public function delete_crime($crime_ids){
        //$this->db->delete(User_model::TABLENAME, array('Username' => $username)); 
        for($i=0; $i<count($crime_ids); $i++){
            $this->db->or_where('Crime_ID = ', $crime_ids[$i]); 
        }
        $this->db->delete(Crime_model::TABLENAME); 
    }
    
    public function get_crime_info($crime_id){
        $this->db->select('Crime_ID, Crime_Name, Crime_Description');
        $this->db->where('Crime_ID', $crime_id); 
        return $this->db->get(Crime_model::TABLENAME)->result_array();
    }
    
    public function edit_crime($crime_id, $crime_name, $crime_description=""){
        $params = array(
           'Crime_Name' => $crime_name ,
           'Crime_Description' => $crime_description
        );
        //var_dump($params);
        $this->db->where('Crime_ID', $crime_id);
        $this->db->update(Crime_model::TABLENAME, $params); 
    }
    
}