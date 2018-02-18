<?php

class Evenements_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function verifExistenceNom($data) {
        $cypher = "MATCH(evenement:EVENEMENT) "
                . "WHERE evenement.nom = {nom} "
                . "RETURN evenement.nom";
        return $this->neo->execute_query($cypher, $data);
    }

    public function creerEvenement($data) {
        $cypher = "MATCH (user:USER) "
                . "WHERE user.login = {monLogin} "
                . "CREATE (user)-[:PARTICIPE]->(evenement:EVENEMENT{nom:{nom}, type:{type}, date:{date}, lieu:{lieu}})";
        $this->neo->execute_query($cypher, $data);
    }

    public function getEvenementsParticipe($data) {
        //Suppression des événements dépassés
        $cypher = "MATCH (evenement:EVENEMENT) "
                . "WHERE evenement.date < '" . date('Y/m/d') . "' "
                . "DETACH DELETE evenement";
        $this->neo->execute_query($cypher);

        //Récupération des événements auxquels participe l'user
        $cypher = "MATCH (user:USER)-[participe:PARTICIPE]->(evenement:EVENEMENT) "
                . "WHERE user.login = {monLogin} "
                . "RETURN evenement.nom "
                . "ORDER BY evenement.nom";
        return $this->neo->execute_query($cypher, $data);
    }

    public function getResultatRecherche($data) {
        $cypher = "MATCH (evenement:EVENEMENT) "
                . "WHERE NOT EXISTS ((:USER{login:{monLogin}})-[:PARTICIPE]->(evenement)) "
                . "AND evenement.nom STARTS WITH {recherche} "
                . "RETURN evenement.nom";
        return $this->neo->execute_query($cypher, $data);
    }

    public function participerEvenement($data) {
        //Création du lien de participation
        $cypher = "MATCH (user:USER), (evenement:EVENEMENT) "
                . "WHERE evenement.nom = {nom} AND user.login = {monLogin} "
                . "CREATE (user)-[:PARTICIPE]->(evenement)";
        $query = $this->neo->execute_query($cypher, $data);

        //Suppression du lien de demande a participer dans le cas ou la personne
        //a reçu une invitation mais participe à l'événement depuis une recherche
        $cypher = "MATCH (:USER)-[inviteaparticiper:INVITEAPARTICIPER]->(user:USER) "
                . "WHERE inviteaparticiper.nomEvenement = {nom} AND user.login = {monLogin} "
                . "DELETE inviteaparticiper";
        $this->neo->execute_query($cypher, $data);

        return $query;
    }

    public function nePlusParticiperEvenement($data) {
        $cypher = "MATCH (user:USER)-[participe:PARTICIPE]->(evenement:EVENEMENT) "
                . "WHERE evenement.nom = {nom} AND user.login = {monLogin} "
                . "DELETE participe";
        $this->neo->execute_query($cypher, $data);
    }

    public function getEvenement($data) {
        $cypher = "MATCH (user:USER)-[participe:PARTICIPE]->(evenement:EVENEMENT) "
                . "WHERE user.login = {monLogin} AND evenement.nom = {nom} "
                . "RETURN {nom:evenement.nom, type:evenement.type, date:evenement.date, lieu:evenement.lieu}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function demanderAParticiper($data) {
        $cypher = "MATCH (user:USER)-[:AMI]-(ami:USER), (evenement:EVENEMENT) "
                . "WHERE user.login = {monLogin} AND ami.login = {loginAmi} AND evenement.nom = {nomEvenement} "
                . "CREATE (user)-[:INVITEAPARTICIPER{nomEvenement:{nomEvenement}}]->(ami)";
        $this->neo->execute_query($cypher, $data);
    }

    public function getEvenementsInvite($data) {
        $cypher = "MATCH (:USER)-[invite:INVITEAPARTICIPER]->(user:USER) "
                . "WHERE user.login = {monLogin} "
                . "RETURN invite.nomEvenement";
        return $this->neo->execute_query($cypher, $data);
    }

    public function accepterInvitation($data) {
        //Création du lien de participation
        $cypher = "MATCH (user:USER),(evenement:EVENEMENT) "
                . "WHERE user.login = {monLogin} AND evenement.nom = {nomEvenement} "
                . "CREATE (user)-[participe:PARTICIPE]->(evenement)";
        $this->neo->execute_query($cypher, $data);

        //Suppression de la demande
        $cypher = "MATCH (:USER)-[inviteaparticiper:INVITEAPARTICIPER]->(user:USER) "
                . "WHERE user.login = {monLogin} AND inviteaparticiper.nomEvenement = {nomEvenement} "
                . "DELETE inviteaparticiper";
        $this->neo->execute_query($cypher, $data);
    }

    public function refuserInvitation($data) {
        //Suppression de la demande
        $cypher = "MATCH (:USER)-[inviteaparticiper:INVITEAPARTICIPER]->(user:USER) "
                . "WHERE user.login = {monLogin} AND inviteaparticiper.nomEvenement = {nomEvenement} "
                . "DELETE inviteaparticiper";
        $this->neo->execute_query($cypher, $data);
    }

}
