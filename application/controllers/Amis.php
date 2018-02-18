<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Amis extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('amis_model');
    }

    public function index() {
        redirect('amis/afficher');
    }

    public function afficher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $amis = $this->amis_model->getListeAmis($this->session->userdata('user_login'));
        $demandesAmisRecues = $this->amis_model->getDemandesAmisRecues($this->session->userdata('user_login'));
        $etatDemandesAmis = $this->amis_model->getEtatDemandesAmis($this->session->userdata('user_login'));
        $demandesAmisAccepteesEtRefusees = $this->amis_model->getDemandesAccepteesEtRefusees($this->session->userdata('user_login'));

        $data['page_title'] = 'Amis';
        $data['amis'] = $amis;
        $data['demandesAmis'] = $demandesAmisRecues;
        $data['demandesAmisAccepteesEtRefusees'] = $demandesAmisAccepteesEtRefusees;
        $data['etatDemandesAmis'] = $etatDemandesAmis;

        $this->load->view('layout/header', $data);
        $this->load->view('amis/liste');
        $this->load->view('layout/footer');
    }

    public function page($loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->load->model('user_model');
        $data['page_title'] = 'Page';
        $data['user'] = $this->user_model->getUser(urldecode($loginAmi));
        $data['publications'] = $this->user_model->getPublications(urldecode($loginAmi));

        $this->load->view('layout/header', $data);
        $this->load->view('amis/page');
        $this->load->view('layout/footer');
    }

    public function rechercher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $recherche = $this->input->post('personne');
        $resultat = $this->amis_model->getResultatRecherche($this->session->userdata('user_login'), $recherche);

        if (isset($resultat[0])) {
            $return[] = '<ul>';
            foreach ($resultat as $personne) {
                $return[] = '<li><a href="' . base_url('amis/ajouter/') . $personne[0]['login'] . '">' . $personne[0]['prenom'] . ' ' . $personne[0]['nom'] . '<i class="fa fa-user-plus"></i></a></li>';
            }
            $return[] = '</ul>';

            echo json_encode($return);
        } else {
            echo json_encode('Pas de résultat');
        }
    }

    public function ajouter($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->ajouter($this->session->userdata('user_login'), urldecode($loginPersonne));

        redirect('amis/afficher');
    }

    public function accepterDemande($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->accepterDemande($this->session->userdata('user_login'), urldecode($loginPersonne));

        redirect('amis/afficher');
    }

    public function refuserDemande($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->refuserDemande($this->session->userdata('user_login'), urldecode($loginPersonne));

        redirect('amis/afficher');
    }

    public function aimerPublication($idPublication, $loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->aimerPublication($this->session->userdata('user_login'), urldecode($idPublication));

        redirect('amis/page/' . urldecode($loginAmi));
    }

    public function aimerDepuisPublication($idPublication, $loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->aimerPublication($this->session->userdata('user_login'), urldecode($idPublication));

        redirect('amis/voirCommentaires/' . urldecode($idPublication) . '/' . urldecode($loginAmi));
    }

    public function voirCommentaires($idPublication, $loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->load->model('user_model');
        $data['page_title'] = 'Commentaires';
        $data['publication'] = $this->user_model->getPublication(urldecode($loginAmi), urldecode($idPublication));
        $data['commentaires'] = $this->user_model->getCommentairesPublication(urldecode($idPublication));

        $this->load->view('layout/header', $data);
        $this->load->view('amis/publication');
        $this->load->view('layout/footer');
    }

    public function commenter() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('commentaire', 'Commentaire', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $loginAmi = $this->input->post('loginAmi');
            //Bizarrement cela ne marche pas si je met idPublication dans data donc je le laisse en dehors
            $idPublication = $this->input->post('idPubli');
            $data = array('monLogin' => $this->session->userdata('user_login'),
                'commentaire' => $this->input->post('commentaire')
            );

            $this->amis_model->commenterPublication($data, $idPublication);
        }

        redirect('amis/voirCommentaires/' . $idPublication . '/' . $loginAmi);
    }

    public function chat() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('etat', 'État', 'required|trim');
        $this->load->model('user_model');

        if ($this->form_validation->run() !== FALSE) {
            $etat = $this->input->post('etat');
            $this->user_model->setEtat($this->session->userdata('user_login'), $etat);
        }

        $data['page_title'] = 'Chat';
        $data['user'] = $this->user_model->getUser($this->session->userdata('user_login'));
        $data['amis'] = $this->amis_model->getListeAmis($this->session->userdata('user_login'));

        $this->load->view('layout/header', $data);
        $this->load->view('user/accueil_chat');
        $this->load->view('layout/footer');
    }

    public function pageChat($loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('message', 'Message', 'required|trim');
        $this->load->model('user_model');

        if ($this->form_validation->run() !== FALSE) {
            $message = $this->input->post('message');
            $this->amis_model->envoiMessage($this->session->userdata('user_login'), urldecode($loginAmi), $message);
        }

        $data['page_title'] = 'Chat';
        $this->load->model('user_model');
        $data['ami'] = $this->user_model->getUser(urldecode($loginAmi));
        $data['conversation'] = $this->amis_model->getConversation($this->session->userdata('user_login'), urldecode($loginAmi));

        $this->load->view('layout/header', $data);
        $this->load->view('amis/page_chat');
        $this->load->view('layout/footer');
    }

}
