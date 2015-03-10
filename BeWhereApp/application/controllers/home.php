<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        $this->load->library('session');
        $this->load->helper("url");
        $param = $this->loadNavAndContent('home');
        //var_dump($param);
        $this->load->view("frontpage/v_template", $param);
    }
    
    public function features(){
        $this->load->helper("url");
        $param = $this->loadNavAndContent('features');
        $this->load->view("frontpage/v_template", $param);
    }
    
    public function about(){
        $this->load->helper("url");
        $param = $this->loadNavAndContent('about');
        $this->load->view("frontpage/v_template", $param);
    }
    
    private function loadNavAndContent($page){
        
        $this->load->helper("url");
        
        $display['nav'] = "";
        $display['content'] = "";
        
        $param = array('page' => $page);
        $display['nav'] = $this->load->view("frontpage/v_horizontal_nav", $param, TRUE);
        
        switch($page){
            case 'home':
                $display['content'] = $this->load->view("frontpage/v_home", array(), TRUE);    
                break;
            case 'features':
                $display['content'] = $this->load->view("frontpage/v_features", array(), TRUE);    
                break;
            case 'about':
                $display['content'] = $this->load->view("frontpage/v_about", array(), TRUE);    
                break;
            default :
                $display['content'] = "";
                break;
        }
        return $display;
    }
    
    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */