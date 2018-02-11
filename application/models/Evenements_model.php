<?php

class Evenements_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function verifExistenceNom($nom) {
        $cypher = "MATCH(evenement:EVENEMENT) "
                . "WHERE evenement.nom = '$nom' "
                . "RETURN evenement.nom";
        return $this->neo->execute_query($cypher);
    }

    public function creerEvenement($nom, $type, $date, $lieu) {
        //Récupération des groupes auxquels appartient l'user
        $cypher = "CREATE (evenement:EVENEMENT{nom:'$nom', type:'$type', date:'$date', lieu:'$lieu'})";
        return $this->neo->execute_query($cypher);
    }

    public function getEvenementsParticipe($login) {
        //Suppression des événements dépassés
        $cypher = "MATCH (evenement:EVENEMENT) "
                . "WHERE evenement.date < " . date('d/m/Y') . " "//TODO!!!!!
                . "DETACH DELETE evenement";
        $this->neo->execute_query($cypher);

        //Récupération des événements auxquels participe l'user
        $cypher = "MATCH (user:USER)-[participe:PARTICIPE]->(evenement:EVENEMENT) "
                . "WHERE user.login = '$login' "
                . "RETURN evenement.nom";
        return $this->neo->execute_query($cypher);
    }

    public function getResultatRecherche($monLogin, $recherche) {
        //Recherche pour une personne dont le om ou prénom commence par la recherche
        $cypher = "MATCH (evenement:EVENEMENT) "
                . "WHERE NOT EXISTS ((:USER{login:'$monLogin'})-[:PARTICIPE]->(evenement)) AND evenement.nom =~ '$recherche.*' "
                . "RETURN evenement.nom";
        return $this->neo->execute_query($cypher);
    }

    public function participerEvenement($monLogin, $nom) {
        //Recherche pour une personne dont le om ou prénom commence par la recherche
        $cypher = "MATCH (user:USER), (evenement:EVENEMENT) "
                . "WHERE evenement.nom = '$nom' AND user.login = '$monLogin' "
                . "CREATE (user)-[:PARTICIPE]->(evenement)";
        return $this->neo->execute_query($cypher);
    }

    public function getEvenement($loginUser, $nom) {
        $cypher = "MATCH (user:USER)-[participe:PARTICIPE]->(evenement:EVENEMENT) "
                . "WHERE user.login = '$loginUser' AND evenement.nom = '$nom' "
                . "RETURN {nom:evenement.nom, type:evenement.type, date:evenement.date, lieu:evenement.lieu}";
        return $this->neo->execute_query($cypher);
    }

    public function demanderAParticiper($monLogin, $loginAmi, $nomEvenement) {
        //Recherche pour une personne dont le om ou prénom commence par la recherche
        $cypher = "MATCH (user:USER)-[:AMI]-(ami:USER), (evenement:EVENEMENT) "
                . "WHERE user.login = '$monLogin' AND ami.login = '$loginAmi' AND evenement.nom = '$nomEvenement' "
                . "CREATE (user)-[:INVITEAPARTICIPER{nomEvenement:'$nomEvenement'}]->(ami)";
        $this->neo->execute_query($cypher);
    }

    public function getEvenementsInvite($monLogin) {
        $cypher = "MATCH (:USER)-[invite:INVITEAPARTICIPER]->(user:USER) "
                . "WHERE user.login = '$monLogin' "
                . "RETURN invite.nomEvenement";
        return $this->neo->execute_query($cypher);
    }

    public function accepterInvitation($loginMoi, $nomEvenement) {
        //Création du lien de participation
        $cypher = "MATCH (user:USER),(evenement:EVENEMENT) "
                . "WHERE user.login = '$loginMoi' AND evenement.nom = '$nomEvenement' "
                . "CREATE (user)-[participe:PARTICIPE]->(evenement)";
        $this->neo->execute_query($cypher);

        //Suppression de la demande
        $cypher = "MATCH (:USER)-[inviteaparticiper:INVITEAPARTICIPER]->(user:USER) "
                . "WHERE user.login = '$loginMoi' AND inviteaparticiper.nomEvenement = '$nomEvenement' "
                . "DELETE inviteaparticiper";
        $this->neo->execute_query($cypher);
    }

    public function refuserInvitation($loginMoi, $nomEvenement) {
        //Suppression de la demande
        $cypher = "MATCH (:USER)-[inviteaparticiper:INVITEAPARTICIPER]->(user:USER) "
                . "WHERE user.login = '$loginMoi' AND inviteaparticiper.nomEvenement = '$nomEvenement' "
                . "DELETE inviteaparticiper";
        $this->neo->execute_query($cypher);
    }

}
