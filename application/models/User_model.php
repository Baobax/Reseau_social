
<?php

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function inscription($nom, $prenom) {
        $cypher = "CREATE(user:USER{nom: '$nom', prenom: '$prenom'})";
        $this->neo->execute_query($cypher);
    }

}

?>
