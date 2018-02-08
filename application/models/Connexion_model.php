
<?php
    class Connexion_model extends CI_Model {
		public function __construct(){
			parent::__construct();
			$this->load->library('neo');
	     }

		public function connectionUser(){
			$cypher = "";
			$this->neo->execute_query($cypher);
		}
    }
?>
