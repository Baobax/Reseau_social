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
            $BDdata['monLogin'] = $this->input->post('login');
            $BDdata['password'] = hash('SHA256', $this->input->post('password'));

            $user = $this->user_model->connexion($BDdata);

            //si $user[0] existe, cela veut dire que la personne existe bien et a rentré les bons identifiants
            if (isset($user[0])) {
                $this->session->set_userdata('user_login', $user[0][0]['login']);
                $this->session->set_userdata('couleur_site', $user[0][0]['couleurSite']);
                $this->session->set_userdata('couleur_texte', $user[0][0]['couleurTexte']);
                $this->session->set_userdata('fond_site', $user[0][0]['fondSite']);
                $BDdata['etat'] = 'connecté';
                $this->user_model->setEtat($BDdata);

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
        $this->form_validation->set_rules('email', 'eMail', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        $this->form_validation->set_rules('prenom', 'Prenom', 'required|trim');
        $this->form_validation->set_rules('nom', 'Nom', 'required|trim');
        $this->form_validation->set_rules('date_naissance', 'Date de naissance', 'required|trim');
        $this->form_validation->set_rules('genre', 'Genre', 'required|trim');
        $this->form_validation->set_rules('annee', 'Année', 'required|trim');
        $this->form_validation->set_rules('discipline', 'Discipline', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $BDdata['login'] = $this->input->post('login');
            $BDdata['email'] = $this->input->post('email');
            $BDdata['password'] = hash('SHA256', $this->input->post('password'));
            $BDdata['prenom'] = $this->input->post('prenom');
            $BDdata['nom'] = $this->input->post('nom');
            $BDdata['dateNaissance'] = $this->input->post('date_naissance');
            $BDdata['genre'] = $this->input->post('genre');
            $BDdata['annee'] = $this->input->post('annee');
            $BDdata['discipline'] = $this->input->post('discipline');
            $BDdata['couleurSite'] = '#222222';
            $BDdata['couleurTexte'] = '#222222';
            $BDdata['fondSite'] = '#eeeeee';
            $BDdata['etat'] = 'connecté';

            $testExistence = $this->user_model->inscription($BDdata);

            if (!$testExistence) {
                $this->session->set_flashdata('message', '<div>Ce login ou email existe déjà, choisissez-en un autre.</div>');
                redirect('user/inscription');
            } else {
                $this->session->set_userdata('user_login', $BDdata['login']);
                $this->session->set_userdata('couleur_site', $BDdata['couleurSite']);
                $this->session->set_userdata('couleur_texte', $BDdata['couleurTexte']);
                $this->session->set_userdata('fond_site', $BDdata['fondSite']);
                $this->session->set_flashdata('message', '<div>Vous êtes maintenant inscrit sur le site.</div>');

                redirect('user/page');
            }
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
        $BDdata['loginUser'] = $this->session->userdata('user_login');
        $data['publications'] = $this->user_model->getPublications($BDdata);

        $this->load->view('layout/header', $data);
        $this->load->view('user/page');
        $this->load->view('layout/footer');
    }

    public function mesInformations() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        $data['page_title'] = 'Mes informations';
        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $data['user'] = $this->user_model->getMesInfos($BDdata);

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
            $BDdata['monLogin'] = $this->session->userdata('user_login');
            $BDdata['couleurSite'] = $this->input->post('couleur_site');
            $BDdata['couleurTexte'] = $this->input->post('couleur_texte');
            $BDdata['fondSite'] = $this->input->post('fond_site');

            $this->user_model->updateParametresCosmetiques($BDdata);


            $this->session->set_userdata('couleur_site', $BDdata['couleurSite']);
            $this->session->set_userdata('couleur_texte', $BDdata['couleurTexte']);
            $this->session->set_userdata('fond_site', $BDdata['fondSite']);
        }


        $data['page_title'] = 'Paramètres';

        $this->load->view('layout/header', $data);
        $this->load->view('user/parametres');
        $this->load->view('layout/footer');
    }

    public function publierTexte() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('texte', 'Texte', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $BDdata['monLogin'] = $this->session->userdata('user_login');
            $BDdata['dateAjout'] = $this->input->post('texte');
            $BDdata['typePublication'] = "texte";
            $BDdata['dateAjout'] = date('YmdHis');

            $this->user_model->publierTexte($BDdata);
        }

        redirect('user/page');
    }

    public function publierVideo() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('legendeVideo', 'Légende', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $file = $this->_file_upload_video('fichierVideo');

            if ($file === 2) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Erreur, types d'extensions acceptées pour le fichier : mp4</div>");
            } else if ($file === false) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Le champ fichier est requis</div>");
            } else {
                $BDdata['monLogin'] = $this->session->userdata('user_login');
                $BDdata['legende'] = $this->input->post('legendeVideo');
                $BDdata['typePublication'] = "vidéo";
                $BDdata['dateAjout'] = date('YmdHis');
                $BDdata['lienMedia'] = $file['file_name'];
                $this->user_model->publierMedia($BDdata);
                $this->session->set_flashdata('message', "<div class='alert alert-success'>La vidéo a été ajoutée avec succès</div>");
            }
        }

        redirect('user/page');
    }

    public function publierImage() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->form_validation->set_rules('legende', 'Légende', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $file = $this->_file_upload_img('fichier');

            if ($file === 2) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Erreur, types d'extensions acceptées pour le fichier : gif, jpg, jpeg et png</div>");
            } else if ($file === false) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Le champ fichier est requis</div>");
            } else {
                $BDdata['monLogin'] = $this->session->userdata('user_login');
                $BDdata['legende'] = $this->input->post('legende');
                $BDdata['typePublication'] = "image";
                $BDdata['dateAjout'] = date('YmdHis');
                $BDdata['lienMedia'] = $file['file_name'];
                $this->user_model->publierMedia($BDdata);
                $this->session->set_flashdata('message', "<div class='alert alert-success'>L'image a été ajoutée avec succès</div>");
            }
        }

        redirect('user/page');
    }

    public function _file_upload_img($file) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        if ($_FILES[$file]['name'] != "") {
            $this->load->helper("text");
            $tampon = explode('.', $_FILES[$file]['name']);
            $filename = convert_accented_characters($tampon[0]);
            $config['file_name'] = url_title($filename, "_", true);
            $config['upload_path'] = "assets/uploads/" . $this->session->userdata('user_login');
            $config['allowed_types'] = 'gif|jpg|jpeg|png';

            if (!file_exists('assets/uploads/' . $this->session->userdata('user_login'))) {
                mkdir('assets/uploads/' . $this->session->userdata('user_login'), 0777, true);
            }

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload($file)) {
                return 2;
            } else {
                return $this->upload->data();
            }
        }
        return false;
    }

    public function _file_upload_video($file) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        if ($_FILES[$file]['name'] != "") {
            $this->load->helper("text");
            $tampon = explode('.', $_FILES[$file]['name']);
            $filename = convert_accented_characters($tampon[0]);
            $config['file_name'] = url_title($filename, "_", true);
            $config['upload_path'] = "assets/uploads/" . $this->session->userdata('user_login');
            $config['allowed_types'] = 'mp4';

            if (!file_exists('assets/uploads/' . $this->session->userdata('user_login'))) {
                mkdir('assets/uploads/' . $this->session->userdata('user_login'), 0777, true);
            }

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload($file)) {
                return 2;
            } else {
                return $this->upload->data();
            }
        }
        return false;
    }

    public function voirCommentaires($idPublication) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        $data['page_title'] = 'Commentaires';
        $BDdata['loginUser'] = $this->session->userdata('user_login');
        $data['publication'] = $this->user_model->getPublication($BDdata, urldecode($idPublication));
        $data['commentaires'] = $this->user_model->getCommentairesPublication(urldecode($idPublication));

        $this->load->view('layout/header', $data);
        $this->load->view('user/publication');
        $this->load->view('layout/footer');
    }

    public function supprimerUser() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $this->user_model->supprimerUser($BDdata);

        $this->session->unset_userdata('user_login');
        redirect('user/connexion');
    }

    public function deconnexion() {
        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['etat'] = 'déconnecté';
        $this->user_model->setEtat($BDdata);

        $this->session->unset_userdata('user_login');

        redirect('user/connexion');
    }

}
