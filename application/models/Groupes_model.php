<?php

class Groupes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function verifExistenceNom($data) {
        $cypher = "MATCH(groupe:GROUPE) "
                . "WHERE groupe.label = {label} "
                . "RETURN groupe.label";
        return $this->neo->execute_query($cypher, $data);
    }

    public function creerGroupe($data) {
        //Création du groupe
        $cypher = "CREATE(groupe:GROUPE{label: {label}, configuration: {configuration}}) ";
        $this->neo->execute_query($cypher, $data);

        //Définition de l'admin
        $cypher = "MATCH (user:USER), (groupe:GROUPE) "
                . "WHERE user.login = {monLogin} "
                . "AND groupe.label = {label} "
                . "CREATE (user)-[membre:MEMBRE{admin: 'oui'}]->(groupe)";
        $this->neo->execute_query($cypher, $data);
    }

    public function getDemandesAccepteesEtRefusees($data) {
        //Récupération des demandes acceptées et refusées pour les indiquer à l'utilisateur
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} "
                . "AND (demandegroupe.etatDemande = 'acceptée' OR demandegroupe.etatDemande = 'rejetée') "
                . "RETURN {label:groupe.label, etatDemande:demandegroupe.etatDemande}";

        $query = $this->neo->execute_query($cypher, $data);

        //Suppression du lien demande groupe si demande acceptée ou rejetée
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} "
                . "AND (demandegroupe.etatDemande = 'acceptée' OR demandegroupe.etatDemande = 'rejetée') "
                . "DELETE demandegroupe";

        $this->neo->execute_query($cypher, $data);

        return $query;
    }

    public function getEtatDemandes($data) {
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} "
                . "RETURN {label:groupe.label, etatDemande:demandegroupe.etatDemande}";

        return $this->neo->execute_query($cypher, $data);
    }

    public function getDemandesIntegration($data) {
        //Récupération des demandes pour rejoindre un groupe dont l'utilisateur est admin pour les lui indiquer
        $cypher = "MATCH (user1:USER)-[membre:MEMBRE]->(groupe:GROUPE)<-[demandegroupe:DEMANDEGROUPE]-(user2:USER) "
                . "WHERE user1.login = {monLogin} "
                . "AND membre.admin = 'oui' "
                . "AND demandegroupe.etatDemande = 'en attente' "
                . "RETURN {label:groupe.label, login:user2.login, prenom:user2.prenom, nom:user2.nom}";

        return $this->neo->execute_query($cypher, $data);
    }

    public function getGroupesAdmin($data) {
        //Récupération des groupes dans lesquels l'user est admin
        $cypher = "MATCH (user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} "
                . "AND membre.admin = 'oui' "
                . "RETURN groupe.label "
                . "ORDER BY groupe.label";
        return $this->neo->execute_query($cypher, $data);
    }

    public function getGroupesMembre($data) {
        //Récupération des groupes auxquels appartient l'user
        $cypher = "MATCH (user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} "
                . "AND membre.admin = 'non' "
                . "RETURN groupe.label "
                . "ORDER BY groupe.label";
        return $this->neo->execute_query($cypher, $data);
    }

    public function getResultatRecherche($data) {
        $cypher = "MATCH (groupe:GROUPE) "
                . "WHERE NOT EXISTS ((:USER{login:{monLogin}})-[:MEMBRE]->(groupe)) "
                . "AND NOT EXISTS ((:USER{login:{monLogin}})-[:DEMANDEGROUPE]->(groupe)) "
                . "AND groupe.label STARTS WITH {recherche} "
                . "RETURN {label:groupe.label, configuration:groupe.configuration}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function rejoindreGroupe($data) {
        //Création du lien qui représente le fait que l'utilisateur est membre du groupe
        $cypher = "MATCH (user:USER),(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} AND groupe.label = {label} "
                . "CREATE (user)-[membre:MEMBRE{admin: 'non'}]->(groupe)";
        $this->neo->execute_query($cypher, $data);
    }

    public function demanderRejoindreGroupe($data) {
        $cypher = "MATCH (user:USER),(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} AND groupe.label = {label} "
                . "CREATE (user)-[demandegroupe:DEMANDEGROUPE{etatDemande: 'en attente'}]->(groupe)";
        $this->neo->execute_query($cypher, $data);
    }

    public function accepterDemande($data) {
        //Création du lien d'appartenance au groupe
        //Je vérifie que l'admin est bien l'utilisateur actuel pour éviter qu'une personne ne rejoigne le groupe en trafiquant l'url
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE)<-[membre:MEMBRE]-(admin:USER) "
                . "WHERE user.login = {loginUser} AND groupe.label = {label} "
                . "AND admin.login = {monLogin} AND membre.admin = 'oui' "
                . "CREATE (user)-[nouveaumembre:MEMBRE{admin: 'non'}]->(groupe)";
        $this->neo->execute_query($cypher, $data);

        //Passage de l'état de la demande de groupe à acceptée
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE)<-[membre:MEMBRE]-(admin:USER) "
                . "WHERE user.login = {loginUser} AND groupe.label = {label} "
                . "AND admin.login = {monLogin} AND membre.admin = 'oui' "
                . "SET demandegroupe.etatDemande = 'acceptée'";
        $this->neo->execute_query($cypher, $data);
    }

    public function refuserDemande($data) {
        //Passage de l'état de la demande de groupe à refusée
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE)<-[membre:MEMBRE]-(admin:USER) "
                . "WHERE user.login = {loginUser} AND groupe.label = {label} "
                . "AND admin.login = {monLogin} AND membre.admin = 'oui' "
                . "SET demandegroupe.etatDemande = 'rejetée'";
        $this->neo->execute_query($cypher, $data);
    }

    public function getGroupe($data) {
        $cypher = "MATCH (user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} AND groupe.label = {label} "
                . "RETURN {label:groupe.label, configuration:groupe.configuration, admin:membre.admin}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function quitterGroupe($data) {
        //$loginUser pour vérifier que c'est bien l'user membre qui souhaite quitter le groupe
        $cypher = "MATCH (user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE groupe.label = {label} AND user.login = {monLogin} "
                . "DELETE membre";
        $this->neo->execute_query($cypher, $data);
    }

    public function supprimerGroupe($data) {
        //$loginadmin pour vérifier que c'est bien l'admin qui souhaite supprimer le groupe
        $cypher = "MATCH (admin:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE groupe.label = {label} AND admin.login = {monLogin} AND membre.admin = 'oui' "
                . "DETACH DELETE groupe";
        $this->neo->execute_query($cypher, $data);
    }

}

?>
