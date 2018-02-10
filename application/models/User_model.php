<?php

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function connexion($login, $password) {
        $cypher = "MATCH(user:USER) WHERE user.login = '$login' AND user.password = '$password' RETURN {login:user.login, couleurSite:user.couleurSite, couleurTexte:user.couleurTexte, fondSite:user.fondSite}";
        return $this->neo->execute_query($cypher);
    }

    public function inscription($login, $password, $nom, $prenom) {
        $cypherTest = "MATCH(user:USER) WHERE user.login = '$login' RETURN user.login";
        $existeDeja = $this->neo->execute_query($cypherTest);

        if (isset($existeDeja[0])) {
            return false;
        }


        $cypher = "CREATE(user:USER{login: '$login', password: '$password', prenom: '$prenom', nom: '$nom'})";
        $this->neo->execute_query($cypher);

        return true;
    }

    public function check_si_email_existe($email) {
        $cypher = "MATCH(user:USER) WHERE user.email = '$email' RETURN user.email";
        $existeDeja = $this->neo->execute_query($cypher);

        if (isset($existeDeja[0])) {
            return false;
        } else {
            return true;
        }
    }

    public function updateParametresCosmetiques($login, $couleurSite, $couleurTexte, $fondSite) {
        $cypher = "MATCH(user:USER) WHERE user.login = '$login' "
                . "SET user.couleurSite = '$couleurSite', user.couleurTexte = '$couleurTexte', user.fondSite = '$fondSite'";
        $this->neo->execute_query($cypher);
    }

}

?>
