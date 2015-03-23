<?php

class Incident_model extends CI_Model {
    
    const TABLENAME = "incident";
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function renderIncidentsTable($filter="all"){
        
        $in_draw = (int)$this->input->post("draw");
        $in_order_column = (int)$this->input->post("order")['0']['column'];
        $in_order_direction = $this->input->post("order")['0']['dir'];
        $in_start = (int)$this->input->post("start");
        $in_length = (int)$this->input->post("length");
        $in_search = $this->input->post("search")['value'];
        
        $primary_key = "Incident_ID";
        $columns = array("Crime", "Incident_Description", "Time", "Author", "Approved_by", "Confirmed");
        
        /*

            SELECT
                `Incident_ID`,
                `C1`.`Crime_Name` AS `Crime`,
                `Incident_Description`,
                `GPS_Coordinates`,
                `Time`,
                `Confirmed`,
                `U1`.`Username` As `Approved_by`,
                `U2`.`Username` As `Author`
            FROM `Incident` 
            INNER JOIN `crimes` as C1
                    on `incident`.`crime_id` = `C1`.`Crime_ID`
            LEFT JOIN `users` as U1
                    on `incident`.`approved_by` = U1.User_ID
            INNER JOIN `users` as U2
                    on `incident`.`author` = `U2`.`User_ID`         

         */
        
        /*
        //$query = $this->string_query;
        $query = "SELECT ";
        for($i=0; $i<count($this->columns); $i++){
            if ($i > 0){
                    $query .= ", ";
            }
            $query .= $this->columns[$i];
        }
        
        $query .= " FROM " . $this->db_table;
        */
        
        $query = " 
                SELECT
                `Incident_ID`,
                `C1`.`Crime_Name` AS `Crime`,
                `Incident_Description`,
                `Time`,
                `U2`.`Username` As `Author`,
                `U1`.`Username` As `Approved_by`,
                `U2`.`Username` As `Author`,
                `Confirmed`
            FROM `incident` 
            INNER JOIN `crimes` as C1
                    on `incident`.`crime_id` = `C1`.`Crime_ID`
            LEFT JOIN `users` as U1
                    on `incident`.`approved_by` = `U1`.`User_ID`
            INNER JOIN `users` as U2
                    on `incident`.`author` = `U2`.`User_ID` ";
        
        $results = $this->db->query($query);
        $records_total = $results->num_rows(); 
        
        /*
        if ($in_search !== ""){
            $query .= " WHERE ";
            for ($x=0; $x<count($this->columns); $x++){
                if ($x > 0){
                    $query .= " OR ";
                    
                }
                $query .= $this->columns[$x] . " LIKE \"%$in_search%\" ";
            }
        }
        */
        
        switch($filter){
            case "all":
                $query .= " WHERE (`Confirmed` = \"1\" OR `Confirmed` = \"0\")";
                break;
            case "approved":
                $query .= " WHERE (`Confirmed` = \"1\")";
                break;
            case "pending":
                $query .= " WHERE (`Confirmed` = \"0\")";
                break;
        }
        
        if ($in_search !== ""){
            $query .= " AND (`Incident_Description` LIKE \"%$in_search%\" OR `Crime_Name` LIKE \"%$in_search%\" OR `U1`.`Username` LIKE \"%$in_search%\" OR `U2`.`Username` LIKE \"%$in_search%\") ";
        }
        
        $results = $this->db->query($query);
        $records_filtered = $results->num_rows(); 
        
        $query .= " ORDER BY " . $columns[$in_order_column] . " " .
                    $in_order_direction . ", " . $primary_key . " asc LIMIT " . $in_start . ", " . $in_length;
        
        //$this->load->model("be_debug_model");
        //$this->be_debug_model->log($query);
        
        
        $results = $this->db->query($query);
        $record_arr = $results->result_array();
        
        //$query_pk = substr_replace($query, $primary_key . ", ", 7, 0);
        //$results = $this->db->query($query_pk);
        //$record_arr_pk = $results->result_array();
        
        
        //var_dump($record_arr_pk);
        for($i=0; $i<count($record_arr); $i++){
            $record_arr[$i]["DT_RowId"] = "row_" . $record_arr[$i][$primary_key];
        }
        
        $json_response = array(
            'draw'              => $in_draw,
            'recordsTotal'      => $records_total,
            'recordsFiltered'   => $records_filtered,
            'data'              => $record_arr
        );
        
        //var_dump($json_response);
        
        
        return $json_response;
    }
    
    
    public function delete_incident($incident_ids){
        //$this->db->delete(User_model::TABLENAME, array('Username' => $username)); 
        for($i=0; $i<count($incident_ids); $i++){
            //$this->load->model("be_debug_model");
            //$this->be_debug_model->log("\$user_ids[$i] = " .$user_ids[$i]);
            $this->db->or_where('Incident_ID = ', $incident_ids[$i]); 
        }
        $this->db->delete(Incident_model::TABLENAME); 
    }
    
    public function confirm_incident($incident_ids, $user_id){
        $data = array(
               'Confirmed' => '1',
               'Approved_by' => $user_id
            );
        
        for($i=0; $i<count($incident_ids); $i++){
            $this->db->or_where('Incident_ID = ', $incident_ids[$i]); 
        }
        $this->db->update(Incident_model::TABLENAME, $data); 
    }
    
    public function unconfirm_incident($incident_ids){
        //$this->db->delete(User_model::TABLENAME, array('Username' => $username)); 
        $data = array(
               'Confirmed' => '0'
            );
        for($i=0; $i<count($incident_ids); $i++){
            $this->db->or_where('Incident_ID = ', $incident_ids[$i]); 
        }
        $this->db->update(Incident_model::TABLENAME, $data); 
    }
    
    public function getPendingConfirmationCount(){
        // SELECT count(Confirmed) from incident where confirmed=1
        $this->db->where('Confirmed', '0'); 
        $this->db->from(Incident_model::TABLENAME);
        return $this->db->count_all_results();
    }
    
    public function addIncident($crime_id, $description, $coordinates, $time, $confirmed, $approved_by, $author){
        $data = array(
           'Crime_ID' => $crime_id ,
           'Incident_Description' => $description ,
           'Latitude' => $coordinates[0],
           'Longitude' => $coordinates[1],
           'Time' => $time, 
           'Confirmed' => $confirmed, 
           'Approved_by' => $approved_by, 
           'Author' => $author
        );
        $this->db->insert(Incident_model::TABLENAME, $data); 
    }
    
    public function addIncidentUnconfirmed($crime_id, $description, $latitude, $longitude, $time, $author){
        $data = array(
           'Crime_ID' => $crime_id ,
           'Incident_Description' => $description ,
           'Latitude' => $latitude,
           'Longitude' => $longitude,
           'Time' => $time, 
           'Confirmed' => '0', 
           'Author' => $author
        );
        $this->db->insert(Incident_model::TABLENAME, $data); 
    }
    
    public function fetch_markers($startLat, $endLat, $startLong, $endLong, $includeUnconfirmed = TRUE){
        $this->db->select('Incident_ID, Latitude, Longitude');
        $this->db->where('Latitude >', $startLat); 
        $this->db->where('Latitude <', $endLat); 
        $this->db->where('Longitude >', $startLong); 
        $this->db->where('Longitude <', $endLong); 
        if (!$includeUnconfirmed){ 
            $this->db->where('Confirmed','1');
        }
        $query = $this->db->get(Incident_model::TABLENAME);
        return $query->result_array();
    }
    
    public function get_incident($incident_id){
        $this->db->select('Incident_ID, incident.Crime_ID, Crime_Name, Incident_Description, Latitude, Longitude, Time, Confirmed, Username');
        $this->db->where('Incident_ID', $incident_id); 
        $this->db->join('crimes', 'crimes.crime_id = incident.crime_id', 'inner');
        $this->db->join('users', 'users.user_id = incident.Author', 'inner');
        $query = $this->db->get(Incident_model::TABLENAME);
        return $query->result_array();
    }
    
    public function update_incident($incident_id, $crime_id, $description, $time){
        $data = array(
           'Crime_ID' => $crime_id ,
           'Incident_Description' => $description ,
           'Time' => $time
        );

        $this->db->where('Incident_ID', $incident_id);
        $this->db->update(Incident_model::TABLENAME, $data); 
    }
    
}