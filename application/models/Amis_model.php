<?php

class Amis_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function getListeAmis($data) {
        $cypher = "MATCH (user1:USER)-[ami:AMI]-(user2:USER) "
                . "WHERE user1.login = {monLogin} "
                . "RETURN {login:user2.login, prenom:user2.prenom, nom:user2.nom, etat:user2.etat} "
                . "ORDER BY user2.prenom";
        return $this->neo->execute_query($cypher, $data);
    }

    public function getDemandesAmisRecues($data) {
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user2.login = {monLogin} AND demandeami.etatDemande = 'en attente' "
                . "RETURN {login:user1.login, prenom:user1.prenom, nom:user1.nom}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function getEtatDemandesAmis($data) {
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = {monLogin} "
                . "RETURN {login:user2.login, prenom:user2.prenom, nom:user2.nom, etatDemande:demandeami.etatDemande}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function getDemandesAccepteesEtRefusees($data) {
        //Récupération des demandes acceptées et refusées pour les indiquer à l'utilisateur
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = {monLogin} AND (demandeami.etatDemande = 'acceptée' OR demandeami.etatDemande = 'rejetée') "
                . "RETURN {login:user2.login, prenom:user2.prenom, nom:user2.nom, etatDemande:demandeami.etatDemande}";

        $query = $this->neo->execute_query($cypher, $data);

        //Suppression du lien demande ami si demande acceptée ou rejetée
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = {monLogin} AND (demandeami.etatDemande = 'acceptée' OR demandeami.etatDemande = 'rejetée') "
                . "DELETE demandeami";

        $this->neo->execute_query($cypher, $data);

        return $query;
    }

    public function getResultatRecherche($data) {
        //Recherche pour une personne dont le nom ou prénom commence par la recherche grâce à STARTS WITH
        //Cette personne ne doit pas être moi ou un ami que j'ai déja ou une personne à qui j'ai déja une demande en attente
        $cypher = "MATCH (user:USER) "
                . "WHERE NOT EXISTS ((:USER{login:{monLogin}})-[:AMI]-(user)) "
                . "AND NOT EXISTS ((:USER{login:{monLogin}})-[:DEMANDEAMI]-(user)) "
                . "AND user.login <> {monLogin} "
                . "AND (user.prenom STARTS WITH {recherche} OR user.nom STARTS WITH {recherche}) "
                . "RETURN {login:user.login, prenom:user.prenom, nom:user.nom}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function ajouter($data) {
        $cypher = "MATCH (user1:USER),(user2:USER) "
                . "WHERE user1.login = {monLogin} AND user2.login = {loginPersonne} "
                . "CREATE (user1)-[demandeami:DEMANDEAMI{etatDemande:'en attente'}]->(user2)";
        $this->neo->execute_query($cypher, $data);
    }

    public function accepterDemande($data) {
        //Création du lien d'amitié
        $cypher = "MATCH (user1:USER),(user2:USER) "
                . "WHERE user1.login = {monLogin} AND user2.login = {loginPersonne} "
                . "CREATE (user1)-[ami:AMI]->(user2)";
        $this->neo->execute_query($cypher, $data);

        //Passage de l'état de la demande d'ami à acceptée
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = {loginPersonne} AND user2.login = {monLogin} "
                . "SET demandeami.etatDemande = 'acceptée'";
        $this->neo->execute_query($cypher, $data);
    }

    public function refuserDemande($data) {
        //Passage de l'état de la demande d'ami à rejetée
        $cypher = "MATCH (user1:USER)-[demandeami:DEMANDEAMI]->(user2:USER) "
                . "WHERE user1.login = {loginPersonne} AND user2.login = {monLogin} "
                . "SET demandeami.etatDemande = 'rejetée'";
        $this->neo->execute_query($cypher, $data);
    }

    public function rechercherAmiPourInviterEvenement($data) {
        $cypher = "MATCH (user1:USER)-[ami:AMI]-(user2:USER) "
                . "WHERE user1.login = {monLogin} "
                . "AND (user2.prenom STARTS WITH {recherche} OR user2.nom STARTS WITH {recherche}) "
                . "AND NOT EXISTS ((user1)-[:INVITEAPARTICIPER{nomEvenement:{nomEvenement}}]->(user2)) "//On recherche un ami qu'on a pas déjà invité à participer
                . "RETURN {login:user2.login, prenom:user2.prenom, nom:user2.nom}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function aimerPublication($data, $idPublication) {
        $cypher = "MATCH (user:USER), (publication:PUBLICATION) "
                . "WHERE ID(publication) = $idPublication AND user.login = {monLogin} "
                . "CREATE UNIQUE (user)-[:AIME]->(publication)";
        $this->neo->execute_query($cypher, $data);
    }

    public function commenterPublication($data, $idPublication) {
        $cypher = "MATCH (user:USER), (publication:PUBLICATION) "
                . "WHERE ID(publication) = $idPublication AND user.login = {monLogin} "
                . "CREATE (user)-[:COMMENTAIRE{commentaire:{commentaire}}]->(publication)";
        $this->neo->execute_query($cypher, $data);
    }

    public function envoiMessage($data) {
        $cypher = "MATCH (user:USER), (ami:USER) "
                . "WHERE user.login = {monLogin} AND ami.login = {loginAmi} "
                . "CREATE (user)-[:MESSAGE{loginEnvoyeur:{monLogin}, message:{message}, dateEnvoi:'" . date('YmdHis') . "'}]->(ami)";
        $this->neo->execute_query($cypher, $data);
    }

    public function getConversation($data) {
        $cypher = "MATCH(user:USER)-[message:MESSAGE]-(ami:USER) "
                . "WHERE user.login = {monLogin} AND ami.login = {loginAmi} "
                . "RETURN {loginEnvoyeur:message.loginEnvoyeur, message:message.message} "
                . "ORDER BY message.dateEnvoi";
        return $this->neo->execute_query($cypher, $data);
    }

}

?>
