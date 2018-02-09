<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function index() {
        redirect('user/connexion');
    }

    public function connexion() {
        if ($this->session->userdata('user_login') != NULL) {
            redirect('user/page');
        }


        $this->form_validation->set_rules('login', 'Login', 'required|trim');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $postdata['login'] = $this->input->post('login');
            $postdata['password'] = hash('SHA256', $this->input->post('password'));

            //$user = $this->user_model->get_by($postdata);
            $user = 1;
            if (isset($user)) {
                $this->session->set_userdata('user_login', "test_sans_bd");

                redirect("user/page");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Mauvais login ou mot de passe.</div>');
            }
        }


        $data['page_title'] = 'Connexion';

        $this->load->view('layout/header_identification', $data);
        $this->load->view('user/connexion');
        $this->load->view('layout/footer');
    }

    public function inscription() {
        if ($this->session->userdata('user_login') != NULL) {
            redirect('user/page');
        }


        $this->form_validation->set_rules('login', 'Login', 'required|trim');
        //$this->form_validation->set_rules('email', 'E-mail', 'required|trim|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $postdata['login'] = $this->input->post('login');
            $nom = $this->input->post('nom');
            $prenom = $this->input->post('prenom');
            $postdata['email'] = $this->input->post('email');
            $postdata['password'] = hash('SHA256', $this->input->post('password'));

            $this->load->model('user_model');
            $this->user_model->inscription($nom, $prenom);
            $this->session->set_userdata('login', 'test');

            $this->session->set_flashdata('message', '<div>Vous êtes maintenant inscrit sur le site.</div>');

            redirect('user/page');
        }


        $data['page_title'] = 'Inscription';

        $this->load->view('layout/header_identification', $data);
        $this->load->view('user/inscription');
        $this->load->view('layout/footer');
    }

    public function page() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $data['page_title'] = 'Votre page';

        $this->load->view('layout/header', $data);
        $this->load->view('user/page');
        $this->load->view('layout/footer');
    }

    public function deconnexion() {
        $this->session->unset_userdata('user_login');
        redirect('user/connexion');
    }

}
