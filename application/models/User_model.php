<?php

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function connexion($login, $password) {
        $cypher = "MATCH(user:USER) WHERE user.login = '$login' AND user.password = '$password' RETURN user.login";
        return $this->neo->execute_query($cypher);
    }

    public function inscription($login, $password, $nom, $prenom) {
        $cypherTest = "MATCH(user:USER) WHERE user.login = '$login' RETURN user.login";
        $existeDeja = $this->neo->execute_query($cypherTest);

        if (isset($existeDeja[0])) {
            return false;
        }


        $cypher = "CREATE(user:USER{login: '$login', password: '$password', nom: '$nom', prenom: '$prenom'})";
        $this->neo->execute_query($cypher);

        return true;
    }

}

?>
