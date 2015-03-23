<?php

class Policeoutposts_model extends CI_Model {
    
    const TABLENAME = "policeoutposts";
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function addOutpost($outpost_name, $description, $coordinates){
        $data = array(
           'Outpost_Name' => $outpost_name ,
           'Outpost_Description' => $description,
           'Latitude' => $coordinates[0],
           'Longitude' => $coordinates[1]
        );
        $this->db->insert(Policeoutposts_model::TABLENAME, $data); 
    }
    
    public function fetch_markers($startLat, $endLat, $startLong, $endLong){
        $this->db->select('Outpost_ID, Latitude, Longitude');
        $this->db->where('Latitude >', $startLat); 
        $this->db->where('Latitude <', $endLat); 
        $this->db->where('Longitude >', $startLong); 
        $this->db->where('Longitude <', $endLong); 
        $query = $this->db->get(Policeoutposts_model::TABLENAME);
        return $query->result_array();
    }
    
    public function get_policeoutpost($outpost_id){
        //$this->db->select('Incident_ID, incident.Crime_ID, Crime_Name, Incident_Description, Latitude, Longitude, Time, Confirmed, Username');
        $this->db->where('Outpost_ID', $outpost_id); 
        $query = $this->db->get(Policeoutposts_model::TABLENAME);
        return $query->result_array();
    }
    
    public function delete_outpost($outpost_id){
        for($i=0; $i<count($outpost_id); $i++){
            $this->db->or_where('Outpost_ID = ', $outpost_id[$i]); 
        }
        $this->db->delete(Policeoutposts_model::TABLENAME); 
    }
    
    public function update_outpost($outpost_id, $outpost_name, $description){
        $data = array(
           'Outpost_Name' => $outpost_name ,
           'Outpost_Description' => $description
        );

        $this->db->where('Outpost_ID', $outpost_id);
        $this->db->update(Policeoutposts_model::TABLENAME, $data); 
    }
}
    