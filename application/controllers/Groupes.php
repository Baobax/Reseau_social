<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groupes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('groupes_model');
    }

    public function index() {
        redirect('groupes/afficher');
    }

    public function afficher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $data['page_title'] = 'Mes groupes';
        $data['groupesAdmin'] = $this->groupes_model->getGroupesAdmin($BDdata);
        $data['groupesMembre'] = $this->groupes_model->getGroupesMembre($BDdata);
        $data['demandesIntegration'] = $this->groupes_model->getDemandesIntegration($BDdata);
        $data['etatDemandesGroupes'] = $this->groupes_model->getEtatDemandes($BDdata);
        $data['demandesAccepteesEtRefusees'] = $this->groupes_model->getDemandesAccepteesEtRefusees($BDdata);

        $this->load->view('layout/header', $data);
        $this->load->view('groupes/afficher');
        $this->load->view('layout/footer');
    }

    public function page($label) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        //Je passe en argument le login de l'user pour être sur qu'il appartient bien au groupe
        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['label'] = urldecode($label);
        $groupe = $this->groupes_model->getGroupe($BDdata);

        //Je vérifie s'il appartient bien au groupe
        if (isset($groupe[0])) {
            $data['page_title'] = 'Mes groupes';
            $data['groupe'] = $groupe;

            $this->load->view('layout/header', $data);
            $this->load->view('groupes/page');
            $this->load->view('layout/footer');
        } else {
            redirect('groupes/afficher');
        }
    }

    public function creer() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        $this->form_validation->set_rules('label', 'Label du groupe', 'required|trim');
        $this->form_validation->set_rules('configuration', 'Configuration du groupe', 'required|trim');

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        if ($this->form_validation->run() !== FALSE) {
            $BDdata['label'] = $this->input->post('label');
            $BDdata['configuration'] = $this->input->post('configuration');

            $groupe = $this->groupes_model->verifExistenceNom($BDdata);

            if (!isset($groupe[0])) {
                $this->groupes_model->creerGroupe($BDdata);
                $this->session->set_flashdata('message', '<div class="alert alert-success">Le groupe a bien été créé.</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Ce nom de groupe existe déjà.</div>');
            }
        }


        $data['page_title'] = 'Mes groupes';
        $data['groupesAdmin'] = $this->groupes_model->getGroupesAdmin($BDdata);
        $data['groupesMembre'] = $this->groupes_model->getGroupesMembre($BDdata);
        $data['demandesIntegration'] = $this->groupes_model->getDemandesIntegration($BDdata);
        $data['etatDemandesGroupes'] = $this->groupes_model->getEtatDemandes($BDdata);
        $data['demandesAccepteesEtRefusees'] = $this->groupes_model->getDemandesAccepteesEtRefusees($BDdata);

        $this->load->view('layout/header', $data);
        $this->load->view('groupes/afficher');
        $this->load->view('layout/footer');
    }

    public function rechercher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['recherche'] = $this->input->post('nom');
        $resultat = $this->groupes_model->getResultatRecherche($BDdata);

        if (isset($resultat[0])) {
            $return[] = '<ul>';
            foreach ($resultat as $groupe) {
                if ($groupe[0]['configuration'] == 'ouvert') {
                    $return[] = '<li>' . $groupe[0]['label'] . ' (groupe ouvert) <a href="' . base_url('groupes/rejoindre/') . $groupe[0]['label'] . '" title="Rejoindre"><i class="fa fa-plus"></i></a></li>';
                } else {
                    $return[] = '<li>' . $groupe[0]['label'] . ' (groupe fermé) <a href="' . base_url('groupes/envoyerDemande/') . $groupe[0]['label'] . '" title="Envoyer une demande"><i class="fa fa-plus"></i></a></li>';
                }
            }
            $return[] = '</ul>';

            echo json_encode($return);
        } else {
            echo json_encode('Pas de résultat');
        }
    }

    public function rejoindre($labelGroupe) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['label'] = urldecode($labelGroupe);
        $this->groupes_model->rejoindreGroupe($BDdata);

        redirect('groupes/afficher');
    }

    public function envoyerDemande($labelGroupe) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['label'] = urldecode($labelGroupe);
        $this->groupes_model->demanderRejoindreGroupe($BDdata);

        redirect('groupes/afficher');
    }

    public function accepterDemande($loginPersonne, $labelGroupe) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['loginUser'] = urldecode($loginPersonne);
        $BDdata['label'] = urldecode($labelGroupe);
        $this->groupes_model->accepterDemande($BDdata);

        redirect('groupes/afficher');
    }

    public function refuserDemande($loginPersonne, $labelGroupe) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['loginUser'] = urldecode($loginPersonne);
        $BDdata['label'] = urldecode($labelGroupe);
        $this->groupes_model->refuserDemande($BDdata);

        redirect('groupes/afficher');
    }

    public function quitterGroupe($label) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['label'] = urldecode($label);
        $this->groupes_model->quitterGroupe($BDdata);

        redirect('groupes/afficher');
    }

    public function supprimerGroupe($label) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['label'] = urldecode($label);
        $this->groupes_model->supprimerGroupe($BDdata);

        redirect('groupes/afficher');
    }

}
