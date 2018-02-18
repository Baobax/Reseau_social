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

        //Récupération de toutes les infos sur les amis (liste, demandes, ...)
        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $amis = $this->amis_model->getListeAmis($BDdata);
        $demandesAmisRecues = $this->amis_model->getDemandesAmisRecues($BDdata);
        $etatDemandesAmis = $this->amis_model->getEtatDemandesAmis($BDdata);
        $demandesAmisAccepteesEtRefusees = $this->amis_model->getDemandesAccepteesEtRefusees($BDdata);

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

        $data['page_title'] = 'Page';

        $this->load->model('user_model');
        $BDdata['loginUser'] = urldecode($loginAmi);
        $data['user'] = $this->user_model->getUser($BDdata);
        $data['publications'] = $this->user_model->getPublications($BDdata);

        $this->load->view('layout/header', $data);
        $this->load->view('amis/page');
        $this->load->view('layout/footer');
    }

    public function rechercher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['recherche'] = $this->input->post('personne');
        $resultat = $this->amis_model->getResultatRecherche($BDdata);

        //Formatage du résultat de la recherche
        if (isset($resultat[0])) {
            $return[] = '<ul>';
            foreach ($resultat as $personne) {
                $return[] = '<li><a href="' . base_url('amis/ajouter/') . $personne[0]['login'] . '">' . $personne[0]['prenom'] . ' ' . $personne[0]['nom'] . '<i class="fa fa-user-plus"></i></a></li>';
            }
            $return[] = '</ul>';

            //On retourne le résultat formaté en json
            echo json_encode($return);
        } else {
            echo json_encode('Pas de résultat');
        }
    }

    public function ajouter($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['loginPersonne'] = urldecode($loginPersonne);
        $this->amis_model->ajouter($BDdata);

        redirect('amis/afficher');
    }

    public function accepterDemande($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['loginPersonne'] = urldecode($loginPersonne);
        $this->amis_model->accepterDemande($BDdata);

        redirect('amis/afficher');
    }

    public function refuserDemande($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['loginPersonne'] = urldecode($loginPersonne);
        $this->amis_model->refuserDemande($BDdata);

        redirect('amis/afficher');
    }

    public function aimerPublication($idPublication, $loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $this->amis_model->aimerPublication($BDdata, urldecode($idPublication));

        redirect('amis/page/' . urldecode($loginAmi));
    }

    public function aimerDepuisPublication($idPublication, $loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $this->amis_model->aimerPublication($BDdata, urldecode($idPublication));

        redirect('amis/voirCommentaires/' . urldecode($idPublication) . '/' . urldecode($loginAmi));
    }

    public function voirCommentaires($idPublication, $loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->load->model('user_model');
        $BDdata['loginUser'] = urldecode($loginAmi);
        $data['page_title'] = 'Commentaires';
        $data['publication'] = $this->user_model->getPublication($BDdata, urldecode($idPublication));
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
            $BDdata['monLogin'] = $this->session->userdata('user_login');
            $BDdata['commentaire'] = $this->input->post('commentaire');
            $idPublication = $this->input->post('idPubli');

            $this->amis_model->commenterPublication($BDdata, $idPublication);
        }

        redirect('amis/voirCommentaires/' . $idPublication . '/' . $loginAmi);
    }

    //Le chat est en post, nous n'avons pas eu le temps d'implémenter un chat avec websocket
    public function chat() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('etat', 'État', 'required|trim');
        $this->load->model('user_model');

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        if ($this->form_validation->run() !== FALSE) {
            $BDdata['etat'] = $this->input->post('etat');
            $this->user_model->setEtat($BDdata);
        }

        $data['page_title'] = 'Chat';

        $BDdata['loginUser'] = $this->session->userdata('user_login');
        $data['user'] = $this->user_model->getUser($BDdata);
        $data['amis'] = $this->amis_model->getListeAmis($BDdata);

        $this->load->view('layout/header', $data);
        $this->load->view('user/accueil_chat');
        $this->load->view('layout/footer');
    }

    public function pageChat($loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('message', 'Message', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $BDdata['monLogin'] = $this->session->userdata('user_login');
            $BDdata['loginAmi'] = urldecode($loginAmi);
            $BDdata['message'] = $this->input->post('message');
            $this->amis_model->envoiMessage($BDdata);
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
