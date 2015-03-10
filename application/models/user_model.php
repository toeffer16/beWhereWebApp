<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user_model
 *
 * @author krisiam
 */
class User_model extends CI_Model {
    
    const TABLENAME = "users";

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function is_exist($username, $password) {

        $params['username'] = $username;
        $params['password'] = sha1($password);
        
        $query = $this->db->get_where(User_model::TABLENAME, $params);
        
        return $query->num_rows() > 0;
    }
    
    public function add_user($username, $password, $type = "member", $first_name="", $last_name=""){
        $params = array(
           'Username' => $username ,
           'Password' => sha1($password),
           'Type' => $type,
           'First_name' => $first_name,
           'Last_name' => $last_name
        );
        //var_dump($params);
        $this->db->insert(User_model::TABLENAME, $params); 
    }
    
    public function delete_user($user_ids){
        //$this->db->delete(User_model::TABLENAME, array('Username' => $username)); 
        for($i=0; $i<count($user_ids); $i++){
            //$this->load->model("be_debug_model");
            //$this->be_debug_model->log("\$user_ids[$i] = " .$user_ids[$i]);
            if($user_ids[$i] != 0){
                $this->db->or_where('User_ID = ', $user_ids[$i]); 
            }else{
                return array(
                    'error' => 0,
                    'description' => 'Deleting \'root\' account is not allowed!'
                );
            }
        }
        $this->db->delete(User_model::TABLENAME); 
    }
    
    public function get_user_privileges($username){
        
    }
    
    public function get_user_id($username){
        $this->db->select('User_ID, Username');
        $this->db->where('Username', $username); 
        return $this->db->get('users')->row()->User_ID;
    }
    
    public function get_user_info($user_id){
        $this->db->select('User_ID, Username, Type, First_name, Last_name');
        $this->db->where('User_ID', $user_id); 
        return $this->db->get('users')->result_array();
    }
    
    public function edit_user($user_id, $username, $password="", $type = "member", $first_name="", $last_name=""){
        $params = array();
        if($password !== ""){
            $params = array(
               'Username' => $username ,
               'Password' => sha1($password),
               'Type' => $type,
               'First_name' => $first_name,
               'Last_name' => $last_name
            );
        }else{
            $params = array(
               'Username' => $username ,
               'Type' => $type,
               'First_name' => $first_name,
               'Last_name' => $last_name
            );
        }
        //var_dump($params);
        $this->db->where('User_ID', $user_id);
        $this->db->update(User_model::TABLENAME, $params); 
    }
    
    
    /*
    public function get_users_table_json($start, $length, $filter = ""){
        $this->db->select('Username, Type');
        $total_count = $query = $this->db->get('users')->num_rows();
        if ($filter != ""){
            //$this->db->where('Username', $filter);
            $this->db->like('Username', $filter);
            $this->db->or_like('Type', $filter); 
        }
        $this->db->select('Username, Type');
        $query = $this->db->get('users');
        $tabledata = $query->result_array();
        $filtered_count = $query->num_rows();
        
        
        
        $result_arr = array(
            'total'     => $total_count,
            'filtered'  => $filtered_count,
            'data'      => $tabledata
        );
        
        return $result_arr;
    }
    */
    


}
