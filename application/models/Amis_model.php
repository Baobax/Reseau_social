<?php

class Amis_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function getListeAmis($monLogin) {
        $cypher = "MATCH (user1:USER)-[ami:AMI]-(user2:USER) "
                . "WHERE user1.login = '$monLogin' "
                . "RETURN {login:user2.login, prenom:user2.prenom, nom:user2.nom}";
        return $this->neo->execute_query($cypher);
    }

    public function getDemandesAmisRecues($monLogin) {
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user2.login = '$monLogin' AND demandeami.etatDemande = 'en attente' "
                . "RETURN {login:user1.login, prenom:user1.prenom, nom:user1.nom}";
        return $this->neo->execute_query($cypher);
    }

    public function getEtatDemandesAmis($monLogin) {
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = '$monLogin' "
                . "RETURN {login:user2.login, prenom:user2.prenom, nom:user2.nom, etatDemande:demandeami.etatDemande}";
        return $this->neo->execute_query($cypher);
    }

    public function getDemandesAccepteesEtRefusees($monLogin) {
        //Récupération des demandes acceptées et refusées pour les indiquer à l'utilisateur
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = '$monLogin' AND (demandeami.etatDemande = 'acceptée' OR demandeami.etatDemande = 'rejetée') "
                . "RETURN {login:user2.login, prenom:user2.prenom, nom:user2.nom, etatDemande:demandeami.etatDemande}";

        $query = $this->neo->execute_query($cypher);

        //Suppression du lien demande ami si demande acceptée ou rejetée
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = '$monLogin' AND (demandeami.etatDemande = 'acceptée' OR demandeami.etatDemande = 'rejetée') "
                . "DELETE demandeami";

        $this->neo->execute_query($cypher);

        return $query;
    }

    public function getResultatRecherche($monLogin, $recherche) {
        //Recherche pour une personne dont le om ou prénom commence par la recherche
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login <> '$monLogin' AND (user.prenom =~ '$recherche.*' OR user.nom =~ '$recherche.*') "
                . "RETURN DISTINCT {login:user.login, prenom:user.prenom, nom:user.nom}";
        return $this->neo->execute_query($cypher);
    }

    public function ajouter($loginMoi, $loginPersonne) {
        $cypher = "MATCH (user1:USER),(user2:USER) "
                . "WHERE user1.login = '$loginMoi' AND user2.login = '$loginPersonne' "
                . "CREATE (user1)-[demandeami:DEMANDEAMI{etatDemande: 'en attente'}]->(user2)";
        $this->neo->execute_query($cypher);
    }

    public function accepterDemande($loginMoi, $loginAmi) {
        //Création du lien d'amitié
        $cypher = "MATCH (user1:USER),(user2:USER) "
                . "WHERE user1.login = '$loginMoi' AND user2.login = '$loginAmi' "
                . "CREATE (user1)-[ami:AMI]->(user2)";
        $this->neo->execute_query($cypher);

        //Passage de l'état de la demande d'ami à acceptée
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = '$loginAmi' AND user2.login = '$loginMoi' "
                . "SET demandeami.etatDemande = 'acceptée'";
        $this->neo->execute_query($cypher);
    }

    public function refuserDemande($loginMoi, $loginAmi) {
        //Passage de l'état de la demande d'ami à rejetée
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = '$loginAmi' "
                . "SET demandeami.etatDemande = 'rejetée'";
        $this->neo->execute_query($cypher);
    }

}

?>
