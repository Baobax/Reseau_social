<?php

class Groupes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function verifExistenceNom($label) {
        $cypher = "MATCH(groupe:GROUPE) "
                . "WHERE groupe.label = '$label' "
                . "RETURN groupe.label";
        return $this->neo->execute_query($cypher);
    }

    public function creerGroupe($loginAdmin, $label, $configuration) {
        //Création du groupe
        $cypher = "CREATE(groupe:GROUPE{label: '$label', configuration: '$configuration'}) ";
        $this->neo->execute_query($cypher);

        //Définition de l'admin
        $cypher = "MATCH (user:USER), (groupe:GROUPE) "
                . "WHERE user.login = '$loginAdmin' AND groupe.label = '$label' "
                . "CREATE (user)-[membre:MEMBRE{admin: 'oui'}]->(groupe)";
        $this->neo->execute_query($cypher);
    }

    public function getDemandesAccepteesEtRefusees($monLogin) {
        //Récupération des demandes acceptées et refusées pour les indiquer à l'utilisateur
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE) "
                . "WHERE user.login = '$monLogin' AND (demandegroupe.etatDemande = 'acceptée' OR demandegroupe.etatDemande = 'rejetée') "
                . "RETURN {label:groupe.label, etatDemande:demandegroupe.etatDemande}";

        $query = $this->neo->execute_query($cypher);

        //Suppression du lien demande groupe si demande acceptée ou rejetée
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE) "
                . "WHERE user.login = '$monLogin' AND (demandegroupe.etatDemande = 'acceptée' OR demandegroupe.etatDemande = 'rejetée') "
                . "DELETE demandegroupe";

        $this->neo->execute_query($cypher);

        return $query;
    }

    public function getEtatDemandes($monLogin) {
        //Récupération des demandes acceptées et refusées pour les indiquer à l'utilisateur
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE) "
                . "WHERE user.login = '$monLogin' "
                . "RETURN {label:groupe.label, etatDemande:demandegroupe.etatDemande}";

        return $this->neo->execute_query($cypher);
    }

    public function getDemandesIntegration($monLogin) {
        //Récupération des demandes pour rejoindre un groupe dont l'utilisateur est admin pour les lui indiquer
        $cypher = "MATCH (user1:USER)-[membre:MEMBRE]->(groupe:GROUPE)<-[demandegroupe:DEMANDEGROUPE]-(user2:USER) "
                . "WHERE user1.login = '$monLogin' AND membre.admin = 'oui' AND demandegroupe.etatDemande = 'en attente' "
                . "RETURN {label:groupe.label, login:user2.login, prenom:user2.prenom, nom:user2.nom}";

        return $this->neo->execute_query($cypher);
    }

    public function getGroupesAdmin($login) {
        //Récupération des groupes auxquels appartient l'user
        $cypher = "MATCH (user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE user.login = '$login' AND membre.admin = 'oui' "
                . "RETURN groupe.label";
        return $this->neo->execute_query($cypher);
    }

    public function getGroupesMembre($login) {
        //Récupération des groupes auxquels appartient l'user
        $cypher = "MATCH (user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE user.login = '$login' AND membre.admin = 'non' "
                . "RETURN groupe.label";
        return $this->neo->execute_query($cypher);
    }

    public function getResultatRecherche($monLogin, $recherche) {
        //Recherche pour une personne dont le om ou prénom commence par la recherche
        $cypher = "MATCH (groupe:GROUPE) "
                . "WHERE NOT EXISTS ((:USER{login:'$monLogin'})-[:MEMBRE]->(groupe)) "
                . "AND NOT EXISTS ((:USER{login:'$monLogin'})-[:DEMANDEGROUPE]->(groupe)) "
                . "AND groupe.label =~ '$recherche.*' "
                . "RETURN {label:groupe.label, configuration:groupe.configuration}";
        return $this->neo->execute_query($cypher);
    }

    public function rejoindreGroupe($monLogin, $label) {
        //Création du lien qui représente le fait que l'utilisateur est membre du groupe
        $cypher = "MATCH (user:USER),(groupe:GROUPE) "
                . "WHERE user.login = '$monLogin' AND groupe.label = '$label' "
                . "CREATE (user)-[membre:MEMBRE{admin: 'non'}]->(groupe)";
        $this->neo->execute_query($cypher);
    }

    public function demanderRejoindreGroupe($monLogin, $label) {
        $cypher = "MATCH (user:USER),(groupe:GROUPE) "
                . "WHERE user.login = '$monLogin' AND groupe.label = '$label' "
                . "CREATE (user)-[demandegroupe:DEMANDEGROUPE{etatDemande: 'en attente'}]->(groupe)";
        $this->neo->execute_query($cypher);
    }

    public function accepterDemande($loginAdmin, $loginDemandeur, $label) {
        //Création du lien d'appartenance au groupe
        //Je vérifie que l'admin est bien l'utilisateur actuel pour éviter qu'une personne ne rejoigne le groupe en trafiquant l'url
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE)<-[membre:MEMBRE]-(admin:USER) "
                . "WHERE user.login = '$loginDemandeur' AND groupe.label = '$label' AND admin.login = '$loginAdmin' AND membre.admin = 'oui' "
                . "CREATE (user)-[nouveaumembre:MEMBRE{admin: 'non'}]->(groupe)";
        $this->neo->execute_query($cypher);

        //Passage de l'état de la demande de groupe à acceptée
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE)<-[membre:MEMBRE]-(admin:USER) "
                . "WHERE user.login = '$loginDemandeur' AND groupe.label = '$label' AND admin.login = '$loginAdmin' AND membre.admin = 'oui' "
                . "SET demandegroupe.etatDemande = 'acceptée'";
        $this->neo->execute_query($cypher);
    }

    public function refuserDemande($loginAdmin, $loginDemandeur, $label) {
        //Passage de l'état de la demande de groupe à refusée
        $cypher = "MATCH (user:USER)-[demandegroupe:DEMANDEGROUPE]->(groupe:GROUPE)<-[membre:MEMBRE]-(admin:USER) "
                . "WHERE user.login = '$loginDemandeur' AND groupe.label = '$label' AND admin.login = '$loginAdmin' AND membre.admin = 'oui' "
                . "SET demandegroupe.etatDemande = 'rejetée'";
        $this->neo->execute_query($cypher);
    }

    public function getGroupe($loginUser, $label) {
        $cypher = "MATCH (user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE user.login = '$loginUser' AND groupe.label = '$label' "
                . "RETURN {label:groupe.label, configuration:groupe.configuration, admin:membre.admin}";
        return $this->neo->execute_query($cypher);
    }

    public function quitterGroupe($loginUser, $label) {
        //$loginUser pour vérifier que c'est bien l'user qui souhaite supprimer le groupe
        $cypher = "MATCH (user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE groupe.label = '$label' AND user.login = '$loginUser' "
                . "DELETE membre";
        $this->neo->execute_query($cypher);
    }

    public function supprimerGroupe($loginAdmin, $label) {
        //$loginadmin pour vérifier que c'est bien l'admin qui souhaite supprimer le groupe
        $cypher = "MATCH (admin:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE groupe.label = '$label' AND admin.login = '$loginAdmin' AND membre.admin = 'oui' "
                . "DETACH DELETE groupe";
        $this->neo->execute_query($cypher);
    }

}

?>
