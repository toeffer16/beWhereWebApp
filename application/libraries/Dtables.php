<?php if(!defined('BASEPATH')) exit('No direct script access allowed');


class Dtables{
    
    private $ci;
    //private $string_query = "";
    private $columns = array();
    private $primary_key = "";
    private $db_table = "";
    
    public function __construct(){
        $this->ci =& get_instance();
        $this->ci->load->database();
    }
    
    public function set_db_table($var){
        $this->db_table = $var;
    }
    
    public function set_columns($var){
        $this->columns = $var;
    }
    
    public function set_primary_key($var){
        $this->primary_key = $var;
    }
    
    public function renderTable($returnAsJSON = TRUE){
        
        $in_draw = (int)$this->ci->input->post("draw");
        $in_order_column = (int)$this->ci->input->post("order")['0']['column'];
        $in_order_direction = $this->ci->input->post("order")['0']['dir'];
        $in_start = (int)$this->ci->input->post("start");
        $in_length = (int)$this->ci->input->post("length");
        $in_search = $this->ci->input->post("search")['value'];
        
        //$query = $this->string_query;
        $query = "SELECT ";
        for($i=0; $i<count($this->columns); $i++){
            if ($i > 0){
                    $query .= ", ";
            }
            $query .= $this->columns[$i];
        }
        
        $query .= " FROM " . $this->db_table;
        
        
        $results = $this->ci->db->query($query);
        $records_total = $results->num_rows(); 
        
        if ($in_search !== ""){
            $query .= " WHERE ";
            for ($x=0; $x<count($this->columns); $x++){
                if ($x > 0){
                    $query .= " OR ";
                    
                }
                $query .= $this->columns[$x] . " LIKE \"%$in_search%\" ";
            }
        }
        
        $results = $this->ci->db->query($query);
        $records_filtered = $results->num_rows(); 
        
        $query .= " ORDER BY " . $this->columns[$in_order_column] . " " .
                    $in_order_direction . ", " . $this->primary_key . " asc LIMIT " . $in_start . ", " . $in_length;
        
        $this->ci->load->model("be_debug_model");
        $this->ci->be_debug_model->log($query);
        
        
        $results = $this->ci->db->query($query);
        $record_arr = $results->result_array();
        
        $query_pk = substr_replace($query, $this->primary_key . ", ", 7, 0);
        $results = $this->ci->db->query($query_pk);
        $record_arr_pk = $results->result_array();
        
        
        
        //var_dump($record_arr_pk);
        for($i=0; $i<count($record_arr); $i++){
            $record_arr[$i]["DT_RowId"] = "row_" . $record_arr_pk[$i][$this->primary_key];
        }
        
        $json_response = array(
            'draw'              => $in_draw,
            'recordsTotal'      => $records_total,
            'recordsFiltered'   => $records_filtered,
            'data'              => $record_arr
        );
        
        if ($returnAsJSON === TRUE){
            return json_encode($json_response);
        }else{
            return $json_response;
        }
    }
    
    

}


// End of file