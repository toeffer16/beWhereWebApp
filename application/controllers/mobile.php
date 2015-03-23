<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mobile extends CI_Controller {
    
    public function login(){
        $this->load->model("user_model");
        $username = $this->input->get("username");
        $password = $this->input->get("password");
        $result = array();
        
        if ($this->user_model->is_exist($username, $password)) {
            $privilege = $this->user_model->get_user_privileges($username);
            if($privilege === 'member'){
                $result['logged_in'] = true;
            }else{
                $result['logged_in'] = false;
            }
        }else{
            $result['logged_in'] = false;
        }
        
    echo json_encode($result);
    }
    
    
    public function signup(){
        $this->load->model("user_model");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $first_name = $this->input->post("first_name");
        $last_name = $this->input->post("last_name");
        $response = array();
        
        // check if username already exists
        if ($this->user_model->is_username_exists($username)){
            $response['success'] = false;
        }else{
            $this->user_model->add_user($username, $password, "member", $first_name, $last_name);
            $response['success'] = true;
        }
        echo json_encode($response);    
    }
    
    public function load_crime_markers(){
        $startLat = $this->input->get("fromlat");
        $endLat = $this->input->get("tolat");
        $startLong = $this->input->get("fromlng");
        $endLong = $this->input->get("tolng");
        
        $this->load->model('incident_model');
        echo json_encode($this->incident_model->fetch_markers($startLat, $endLat, $startLong, $endLong, FALSE));
    }
    
    public function load_policeoutpost_markers(){
        $startLat = $this->input->get("fromlat");
        $endLat = $this->input->get("tolat");
        $startLong = $this->input->get("fromlng");
        $endLong = $this->input->get("tolng");
        
        $this->load->model('policeoutposts_model');
        echo json_encode($this->policeoutposts_model->fetch_markers($startLat, $endLat, $startLong, $endLong));
    }
    
    public function get_incident_info(){
        $incident_id = $this->input->get("incident_id");
        $this->load->model('incident_model');
        echo json_encode($this->incident_model->get_incident($incident_id)[0]);
    }
    
    public function get_outpost_info(){
        $outpost_id = $this->input->get("outpost_id");
        $this->load->model('policeoutposts_model');
        echo json_encode($this->policeoutposts_model->get_policeoutpost($outpost_id)[0]);
    }

    public function get_crime_suggestions(){
        $this->load->model('crime_model');
        $user_query = $this->input->get('crime_suggest');
        $fixedq = array();
        $matches = $this->crime_model->get_crimes_match_autocomplete($user_query);
        for($i=0; $i<count($matches); $i++){
            $fixedq[$i]['value'] = $matches[$i]['Crime_ID'];
            $fixedq[$i]['text'] = $matches[$i]['Crime_Name'];
        }
        echo json_encode($fixedq);
    }
    
     public function plot_crime_incident(){
        $this->load->model('incident_model');
        $this->load->model('user_model');
        
        $crime_id = $this->input->post("crime_id");
        $description = $this->input->post("description");
        $incident_time = $this->input->post("time");
        $latitude = $this->input->post("latitude");
        $longitude = $this->input->post("longitude");
        $user_id = $this->user_model->get_user_id($this->input->post("username"));
        
        $this->incident_model->addIncidentUnconfirmed($crime_id, $description, $latitude, $longitude, $incident_time, $user_id);
    }
}
    