<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

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
            $login = $this->input->post('login');
            $password = hash('SHA256', $this->input->post('password'));

            $user = $this->user_model->connexion($login, $password);

            //si $user[0] existe, cela veut dire que la personne existe bien et a rentré les bons identifiants
            if (isset($user[0])) {
                $this->session->set_userdata('user_login', $user[0][0]['login']);
                $this->session->set_userdata('couleur_site', $user[0][0]['couleurSite']);
                $this->session->set_userdata('couleur_texte', $user[0][0]['couleurTexte']);
                $this->session->set_userdata('fond_site', $user[0][0]['fondSite']);

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
        $this->form_validation->set_rules('email', 'eMail', 'required|trim|email_valide|email_inexistant');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        $this->form_validation->set_rules('prenom', 'Prenom', 'required|trim');
        $this->form_validation->set_rules('nom', 'Nom', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $login = $this->input->post('login');
            $email = $this->input->post('email');
            $password = hash('SHA256', $this->input->post('password'));
            $prenom = $this->input->post('prenom');
            $nom = $this->input->post('nom');

            $testExistance = $this->user_model->inscription($login, $password, $nom, $prenom);

            if (!$testExistance) {
                $this->session->set_flashdata('message', '<div>Ce login existe déjà, choisissez-en un autre.</div>');
                redirect('user/inscription');
            } else {
                $this->session->set_userdata('user_login', $login);
                $this->session->set_flashdata('message', '<div>Vous êtes maintenant inscrit sur le site.</div>');

                redirect('user/page');
            }
        }


        $data['page_title'] = 'Inscription';

        $this->load->view('layout/header_identification', $data);
        $this->load->view('user/inscription');
        $this->load->view('layout/footer');
    }

    function __email_valide($email) {
        $pieces = explode('@', $email);

        if ($pieces[1] != 'enssat.fr') {
            return false;
        } else {
            return true;
        }
    }

    function __email_inexistant($email) {
        $this->load->model('user_model');

        if ($this->user_model->check_si_email_existe($email)) {
            return TRUE;
        } else {
            return FALSE;
        }
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

    public function mesInformations() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $data['page_title'] = 'Mes informations';


        $this->load->view('layout/header', $data);
        $this->load->view('user/mes_informations');
        $this->load->view('layout/footer');
    }

    public function parametres() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('couleur_site', 'Couleur du site', 'required|trim');
        $this->form_validation->set_rules('couleur_texte', 'Couleur du texte', 'required|trim');
        $this->form_validation->set_rules('fond_site', 'Fond du site', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $couleurSite = $this->input->post('couleur_site');
            $couleurTexte = $this->input->post('couleur_texte');
            $fondSite = $this->input->post('fond_site');

            $this->user_model->updateParametresCosmetiques($this->session->userdata('user_login'), $couleurSite, $couleurTexte, $fondSite);


            $this->session->set_userdata('couleur_site', $couleurSite);
            $this->session->set_userdata('couleur_texte', $couleurTexte);
            $this->session->set_userdata('fond_site', $fondSite);
        }


        $data['page_title'] = 'Paramètres';

        $this->load->view('layout/header', $data);
        $this->load->view('user/parametres');
        $this->load->view('layout/footer');
    }

    public function deconnexion() {
        $this->session->unset_userdata('user_login');
        redirect('user/connexion');
    }

}
