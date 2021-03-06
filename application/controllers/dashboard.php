<?php

if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Dashboard extends CI_Controller {



    public function index() {

        

        if ($this->auth->is_authenticated()) {

            redirect("dashboard/home");

        }



      else 

    $this->load->view('chemlabhome');

        

    }



    public function home() {

        

        $this->auth->required();

        

        $auth = new Auth();

        

        if($auth->getLevel() == '9') {

            $this->template->write("title", "Dashboard");

            $this->template->write_view("content", "dashboard/home");

            $this->template->render();

        }

        else {

            $this->template->write("title", "Admin Dashboard");

            $this->template->write_view("content", "dashboard/adminHome");

            $this->template->render();

        }

        

    }

    

    public function switch_theme() {

        

        $this->auth->required();

        

        $user_id = $this->auth->get_user_id();

        

        $current_theme = $this->_auth->get_user_theme($user_id);

        

        if ($current_theme == NULL) return;

        

        switch ($current_theme) {

            default:

            case 0:

                $new_theme = 1;

                break;

            case 1:

                $new_theme = 0;

                break;

        }

        

        if ($this->_auth->set_user_theme($user_id, $new_theme)) {

            $this->flash->success("Theme switched");

        } else {

            $this->flash->danger("Error switching theme");

        }

        

        redirect();

        

    }



    public function help() {


        $this->auth->required();
        $user_id = $this->auth->get_user_id();     

        $this->template->write("title", "Help");

        $this->template->write_view("content", "dashboard/help");

        $this->template->render();



    }

    public function about() {

     

        //$this->auth->required();

        

        //$user_id = $this->auth->get_user_id();

        

        $this->template->write("title", "About");
        $this->template->write("heading", "About LARS");

        $this->template->write_view("content", "about");

        $this->template->render();



    }

}