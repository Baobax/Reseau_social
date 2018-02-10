<?php

class Amis_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function getListeAmis($login) {
        $cypher = "MATCH(user:USER) WHERE user.login <> '$login' RETURN user.login";
        return $this->neo->execute_query($cypher);
    }

    public function getResultatRecherche($personne) {
        $cypher = "MATCH(user:USER) WHERE user.prenom = '$personne' OR user.nom = '$personne' RETURN {login:user.login, prenom:user.prenom, nom:user.nom}";
        return $this->neo->execute_query($cypher);
    }

}

?>
