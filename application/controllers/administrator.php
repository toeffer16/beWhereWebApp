<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of administrator
 *
 * @author krisiam
 */
class Administrator extends CI_Controller {

    // Default page for the administrator panel
    function index() {
        // Load necessary CodeIgniter libraries
        $this->load->library('session');
        $this->load->helper("url");
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            redirect("administrator/login");
            return; // exits this function
        }
        
        // Retrieve username and render the page for the admin page.
        $vars['username'] = $this->session->userdata("username");
        $vars['logged_in'] = true;
        $vars['content'] = $this->get_content('usermgt');
        $vars['custom_heads'] = $this->get_custom_heads('usermgt');

        // Check if there's pending screen notification. If there is, render it in page.
        if($this->session->flashdata('notification')){
            $vars['notification'] = $this->session->flashdata('notification');
        }
        
        // Render the page
        $this->load->view('v_template', $vars);
    }
    
    // Login page of the administrator panel
    public function login(){
        // Load necessary CodeIgniter Libraries
        $this->load->library('session');
        $this->load->helper("url");
        
        // Check if the user is already loggen in. If he/she is, redirect him/her to the admin page.
        if ($this->session->userdata("username") !== FALSE) {
            redirect("administrator");
            return; //exits this function
        }
        
        // Check if there's pending screen notification. If there is, render it in page.
        if($this->session->flashdata('notification')){
            $vars['notification'] = $this->session->flashdata('notification');
        }
        
        // Render the login page.
        $vars['content'] = $this->load->view("v_login", array(), TRUE);
        $vars['logged_in'] = false;
        $vars['username'] = "";
         $vars['custom_heads'] = "";
        $this->load->view('v_template', $vars);
    }
    
    public function verify_login() {

        // Load necessary CodeIgniter libraries
        $this->load->library('session');
        $this->load->helper("url");
        $this->load->model("user_model");
        
        // Check if the user is already loggen in. If he/she is, redirect him/her to the admin page.
        if ($this->session->userdata("username") !== FALSE) {
            redirect("administrator");
            return; //exits this function
        }
        
        // Retrieve the username and password information given by the user.
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        
        // Check if that user exists in the database and that he/she is giving the correct password
        if ($this->user_model->is_exist($username, $password)) {
            
            $privilege = $this->user_model->get_user_privileges($username);
            if($privilege === 'admin' || $privilege === 'contributor'){
            
                // If the user is register and provides the correct password,
                // set a session cookie in his/her browser to indicate that he/she 
                // is currently logged in the site. The application will check this
                // session cookie to verify if the current user is logged in or not.
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('user_id', $this->user_model->get_user_id($username));
                redirect("administrator"); // redirect to admin page
            }else{
                $this->session->set_flashdata('notification', "Only users with Admin and Contributor accounts are allowed access.");
                redirect("administrator/login");
            }
        } else {
            
            // If the user entered incorrect login credentials, the application
            // will redirect him/her again back to the login page complete
            // with a notification about the situation.
            $this->session->set_flashdata('notification', "Login Error! Wrong password or username.");
            redirect("administrator/login");
        }
    }

    public function logout() {
        $this->load->helper("url");
        $this->load->library('session');
        $this->session->set_flashdata('notification', "Successfully Logout!");
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('user_id');
        redirect("administrator/login");
    }
    
    // =======================================================================================
    // User Management
    
    public function usermgt(){
        $this->load->library('session');
        $this->load->helper("url");
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            redirect("administrator/login");
            return; // exits this function
        }

        $vars['username'] = $this->session->userdata("username");
        $vars['logged_in'] = true;
        $vars['content'] = $this->get_content('usermgt');
        $vars['custom_heads'] = $this->get_custom_heads('usermgt');

        if($this->session->flashdata('notification')){
            $vars['notification'] = $this->session->flashdata('notification');
        }
        $this->load->view('v_template', $vars);
    }
    
    public function render_user_tables(){
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $this->load->library("dtables");
        $this->dtables->set_db_table("users");
        $this->dtables->set_columns(array("Username", "Type", "First_Name", "Last_Name"));
        $this->dtables->set_primary_key("User_ID");
        echo $this->dtables->renderTable();
    }
    
    public function delete_users(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $user_ids = $this->input->post()['user_id'];
        //var_dump($user_ids);
        $this->load->model("user_model");
        $this->user_model->delete_user($user_ids);
    }
    
    public function add_user(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $firstname = $this->input->post("first_name");
        $lastname = $this->input->post("last_name");
        $type = $this->input->post("type");

        $this->load->model("user_model");
        $this->user_model->add_user($username, $password, $type, $firstname, $lastname);
            
    }
    
    public function get_user(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $user_id = $this->input->post()['user_id'];
        $this->load->model("user_model");
        $result = $this->user_model->get_user_info($user_id)[0];
        echo json_encode($result);
    }
    
    public function edit_user(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $user_id = $this->input->post()['user_id'];
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $firstname = $this->input->post("first_name");
        $lastname = $this->input->post("last_name");
        $type = $this->input->post("type");

        $this->load->model("user_model");
        $this->user_model->edit_user($user_id, $username, $password, $type, $firstname, $lastname);
            
    }
    
    
    // =======================================================================================
    // Crime Map
    
    public function crime_map(){
        $this->load->library('session');
        $this->load->helper("url");
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            redirect("administrator/login");
            return; // exits this function
        }

        $vars['username'] = $this->session->userdata("username");
        $vars['logged_in'] = true;
        $vars['content'] = $this->get_content('crimemap');
        $vars['custom_heads'] = $this->get_custom_heads('crimemap');

        if($this->session->flashdata('notification')){
            $vars['notification'] = $this->session->flashdata('notification');
        }
        $this->load->view('v_template', $vars);
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
        $this->load->library('session');
        
        $crime_id = $this->input->post("crime_id");
        $description = $this->input->post("description");
        $incident_time = $this->input->post("time");
        $coordinates = $this->input->post("coordinates");
        $user_id = $this->session->userdata('user_id');
        
        //var_dump($coordinates);
        
        $this->incident_model->addIncident($crime_id, $description, $coordinates, $incident_time, '1', $user_id, $user_id);
    }
    
    public function fetch_crime_markers(){
        $startLat = $this->input->get("fromlat");
        $endLat = $this->input->get("tolat");
        $startLong = $this->input->get("fromlng");
        $endLong = $this->input->get("tolng");
        
        $this->load->model('incident_model');
        echo json_encode($this->incident_model->fetch_markers($startLat, $endLat, $startLong, $endLong));
    }
    
    public function plot_police_outposts(){
        $this->load->model('policeoutposts_model');
        
        $outpost_name = $this->input->post("outpost_name");
        $description = $this->input->post("outpost_desc");
        $coordinates = $this->input->post("coordinates");
        
        //var_dump($coordinates);
        
        $this->policeoutposts_model->addOutpost($outpost_name, $description, $coordinates);
    }
    
    public function fetch_policeoutpost_markers(){
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
    
    public function delete_outposts(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $outpost_id = $this->input->post()['outpost_id'];
        //var_dump($user_ids);
        $this->load->model("policeoutposts_model");
        $this->policeoutposts_model->delete_outpost($outpost_id);
        
    }
    
    public function update_crime_incident(){
        $this->load->model('incident_model');
        $this->load->library('session');
        
        $incident_id = $this->input->post("incident_id");
        $crime_id = $this->input->post("crime_id");
        $description = $this->input->post("description");
        $incident_time = $this->input->post("time");
        
        $this->incident_model->update_incident($incident_id, $crime_id, $description, $incident_time);
    }
    
    public function update_police_outpost(){
        $this->load->model('policeoutposts_model');
        $this->load->library('session');
        
        $outpost_id = $this->input->post("outpost_id");
        $outpost_name = $this->input->post("outpost_name");
        $description = $this->input->post("outpost_desc");
        
        $this->policeoutposts_model->update_outpost($outpost_id, $outpost_name, $description);
    }
    
    // =======================================================================================
    // User_reports
    
    public function user_reports(){
        $this->load->library('session');
        $this->load->helper("url");
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            redirect("administrator/login");
            return; // exits this function
        }

        $vars['username'] = $this->session->userdata("username");
        $vars['logged_in'] = true;
        $vars['content'] = $this->get_content('user_reports');
        $vars['custom_heads'] = $this->get_custom_heads('user_reports');

        if($this->session->flashdata('notification')){
            $vars['notification'] = $this->session->flashdata('notification');
        }
        
        $this->load->view('v_template', $vars);
    }
    
    public function render_incidents_table(){
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $this->load->model("incident_model");
        $tableData = $this->incident_model->renderIncidentsTable();
        
        for($i=0; $i<count($tableData['data']); $i++){
            $tableData['data'][$i]['Incident_Description'] = $this->truncate(strip_tags($tableData['data'][$i]['Incident_Description']), 45);
        }
        
        echo json_encode($tableData);
    }
    public function render_incidents_table_approved(){
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $this->load->model("incident_model");
        $tableData = $this->incident_model->renderIncidentsTable("approved");
        
        for($i=0; $i<count($tableData['data']); $i++){
            $tableData['data'][$i]['Incident_Description'] = $this->truncate(strip_tags($tableData['data'][$i]['Incident_Description']), 45);
        }
        
        echo json_encode($tableData);
    }
    
    public function render_incidents_table_pending(){
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $this->load->model("incident_model");
        $tableData = $this->incident_model->renderIncidentsTable("pending");
        
        for($i=0; $i<count($tableData['data']); $i++){
            $tableData['data'][$i]['Incident_Description'] = $this->truncate(strip_tags($tableData['data'][$i]['Incident_Description']), 45);
        }
        
        echo json_encode($tableData);
    }
    
    
    public function delete_incidents(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $incident_ids = $this->input->post()['incident_id'];
        //var_dump($user_ids);
        $this->load->model("incident_model");
        $this->incident_model->delete_incident($incident_ids);
        
    }
    
    public function confirm_incidents(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $incident_ids = $this->input->post()['incident_id'];
        $user_id = $this->session->userdata("user_id");
        //var_dump($user_ids);
        $this->load->model("incident_model");
        $this->incident_model->confirm_incident($incident_ids, $user_id);
        
    }
    
    public function unconfirm_incidents(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $incident_ids = $this->input->post()['incident_id'];
        //var_dump($user_ids);
        $this->load->model("incident_model");
        $this->incident_model->unconfirm_incident($incident_ids);
        
    }
    
    public function get_pending_incident_count(){
        $this->load->model("incident_model");
        $result = array('count' => $this->incident_model->getPendingConfirmationCount());
        echo json_encode($result);
    }
    
    // =======================================================================================
    // Crime Pedia
    
    public function crime_pedia(){
        $this->load->library('session');
        $this->load->helper("url");
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            redirect("administrator/login");
            return; // exits this function
        }
        
        $vars['username'] = $this->session->userdata("username");
        $vars['logged_in'] = true;
        $vars['content'] = $this->get_content('crime_pedia');
        $vars['custom_heads'] = $this->get_custom_heads('crime_pedia');

        if($this->session->flashdata('notification')){
            $vars['notification'] = $this->session->flashdata('notification');
        }
        $this->load->view('v_template', $vars);
    }
    
    public function render_crime_pedia_tables(){
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $this->load->library("dtables");
        $this->dtables->set_db_table("crimes");
        $this->dtables->set_columns(array("Crime_Name", "Crime_Description"));
        $this->dtables->set_primary_key("Crime_ID");
        $tableData = $this->dtables->renderTable(FALSE);
        
        for($i=0; $i<count($tableData['data']); $i++){
            $tableData['data'][$i]['Crime_Description'] = $this->truncate(strip_tags($tableData['data'][$i]['Crime_Description']));
        }
        
        echo json_encode($tableData);
    }
    
    private function truncate($string,$length=100,$append="&hellip;") {
        $string = trim($string);

        if(strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }

        return $string;
    }
    
    
    public function add_crime_pedia(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $crime_name = $this->input->post("crime_name");
        $crime_description = $this->input->post("crime_description");

        $this->load->model("crime_model");
        $this->crime_model->add_crime($crime_name, $crime_description);
            
    }
    
    public function delete_crimes(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $crime_ids = $this->input->post()['crime_id'];
        //var_dump($user_ids);
        $this->load->model("crime_model");
        $this->crime_model->delete_crime($crime_ids);
    }
    
    public function get_crime(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $crime_id = $this->input->post()['crime_id'];
        $this->load->model("crime_model");
        $result = $this->crime_model->get_crime_info($crime_id)[0];
        echo json_encode($result);
    }
    
    public function edit_crime(){
        
        $this->load->library('session');
        
        // Check if the user is already logged in. If not, redirect to login page.
        if ($this->session->userdata("username") === FALSE) {
            //redirect("administrator/login");
            return; // exits this function
        }
        
        $crime_id = $this->input->post()['crime_id'];
        $crime_name = $this->input->post("crime_name");
        $crime_description = $this->input->post("crime_description");

        $this->load->model("crime_model");
        $this->crime_model->edit_crime($crime_id, $crime_name, $crime_description);
        
    }
    
    // =======================================================================================
    // get_content
    // - returns data on the content based on the page being clicked on (either
    //   User Management, Crime Map, or Submissions
    private function get_content($currentpage){
        
        switch ($currentpage) {
            case 'usermgt':
                $content = $this->load->view("v_usermgt", array(), TRUE);
                break;
            case 'crimemap':
                $content = $this->load->view("v_crime_map", array(), TRUE);
                break;
            case 'user_reports':
                $content = $this->load->view("v_user_reports", array(), TRUE);
                break;
            case 'crime_pedia':
                $content = $this->load->view("v_crime_pedia", array(), TRUE);
                break;
            default:
                $content = "";
                break;
        }
        
        $param = array(
            'currentpage' => $currentpage,
            'inline_content' => $content
        );
        return $this->load->view("v_vertical_nav", $param, TRUE);
    }
    
    private function get_custom_heads($currentpage){
        
        $head_content = "";
        
        switch ($currentpage) {
            case 'usermgt':
                $head_content = $this->load->view("heads/v_head_usermgt", array(), TRUE);
                break;
            case 'crimemap':
                $head_content = $this->load->view("heads/v_head_crime_map", array(), TRUE);
                break;
            case 'user_reports':
                $head_content = $this->load->view("heads/v_head_user_reports", array(), TRUE);
                break;
            case 'crime_pedia':
                $head_content = $this->load->view("heads/v_head_crime_pedia", array(), TRUE);
                break;
            default:
                $head_content = "";
                break;
        }
        
        return $head_content;
    }
    
    
}
