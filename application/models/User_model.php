<?php

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function connexion($data) {
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login = {monLogin} "
                . "AND user.password = {password} "
                . "RETURN {login:user.login, couleurSite:user.couleurSite, couleurTexte:user.couleurTexte, fondSite:user.fondSite}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function setEtat($data) {
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login = {monLogin} "
                . "SET user.etat = {etat}";
        $this->neo->execute_query($cypher, $data);
    }

    public function inscription($data) {
        //Vérification de l'existence du login
        $cypherTest = "MATCH(user:USER) "
                . "WHERE user.login = {login} "
                . "RETURN user.login";
        $existeDejaLogin = $this->neo->execute_query($cypherTest, $data);

        if (isset($existeDejaLogin[0])) {
            return false;
        }

        //Vérification de l'existence de l'email
        $cypherTest = "MATCH(user:USER) "
                . "WHERE user.email = {email} "
                . "RETURN user.email";
        $existeDejaMail = $this->neo->execute_query($cypherTest, $data);

        if (isset($existeDejaMail[0])) {
            return false;
        }

        //Création de l'utilisateur
        $cypher = "CREATE(user:USER{login: {login}, password: {password}, "
                . "prenom: {prenom}, nom: {nom}, "
                . "email: {email}, dateNaissance: {dateNaissance}, "
                . "genre: {genre}, annee: {annee}, "
                . "discipline: {discipline}, couleurSite: {couleurSite}, "
                . "couleurTexte: {couleurTexte}, fondSite: {fondSite}, "
                . "etat: {etat}})";
        $this->neo->execute_query($cypher, $data);

        return true;
    }

    public function getMesInfos($data) {
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login = {monLogin} "
                . "RETURN {login:user.login, prenom:user.prenom, nom:user.nom, email:user.email, genre:user.genre, "
                . "dateNaissance:user.dateNaissance, annee:user.annee, discipline:user.discipline}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function updateParametresCosmetiques($data) {
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login = {monLogin} "
                . "SET user.couleurSite = {couleurSite}, user.couleurTexte = {couleurTexte}, user.fondSite = {fondSite}";
        $this->neo->execute_query($cypher, $data);
    }

    //On crée une publication qui sera liée à son créateur à l'aide de la relation :PUBLIE
    public function publierTexte($data) {
        $cypher = "MATCH (user:USER) "
                . "WHERE user.login = {monLogin} "
                . "CREATE (user)-[:PUBLIE]->(:PUBLICATION{content:{texte}, type:{typePublication}, dateAjout:{dateAjout}})";
        $this->neo->execute_query($cypher, $data);
    }

    public function publierMedia($data) {
        $cypher = "MATCH (user:USER) "
                . "WHERE user.login = {monLogin} "
                . "CREATE (user)-[:PUBLIE]->(:PUBLICATION{content:{lienMedia}, legende:{legende}, type:{typePublication}, dateAjout:{dateAjout}})";
        $this->neo->execute_query($cypher, $data);
    }

    public function getUser($data) {
        $cypher = "MATCH (user:USER) "
                . "WHERE user.login = {loginUser} "
                . "RETURN {login:user.login, prenom:user.prenom, nom:user.nom, etat:user.etat}";
        return $this->neo->execute_query($cypher, $data);
    }

    //nbjaimes:SIZE((publication)<-[:AIME]-(:USER)) : permet de compter le nombre de j'aimes sur la publication
    //retourne 0 s'il n'y en a pas
    //Même principe pour nbcommentaires
    public function getPublications($data) {
        $cypher = "MATCH (publication:PUBLICATION), (user:USER) "
                . "WHERE (user{login:{loginUser}})-[:PUBLIE]->(publication) "
                . "RETURN {nbjaimes:SIZE((publication)<-[:AIME]-(:USER)), nbcommentaires:SIZE((publication)<-[:COMMENTAIRE]-(:USER)), id:ID(publication), content:publication.content, type:publication.type, legende:publication.legende, login:user.login, prenom:user.prenom, nom:user.nom} "
                . "ORDER BY publication.dateAjout DESC";
        return $this->neo->execute_query($cypher, $data);
    }

    public function getPublication($data, $idPublication) {
        $cypher = "MATCH (publication:PUBLICATION), (user:USER) "
                . "WHERE (user{login:{loginUser}})-[:PUBLIE]->(publication) AND ID(publication) = $idPublication "
                . "RETURN {nbjaimes:SIZE((publication)<-[:AIME]-(:USER)), nbcommentaires:SIZE((publication)<-[:COMMENTAIRE]-(:USER)), id:ID(publication), content:publication.content, type:publication.type, legende:publication.legende, login:user.login, prenom:user.prenom, nom:user.nom}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function getCommentairesPublication($idPublication) {
        $cypher = "MATCH (user:USER)-[commentaire:COMMENTAIRE]->(publication:PUBLICATION) "
                . "WHERE ID(publication) = $idPublication "
                . "RETURN {id:ID(commentaire), commentaire:commentaire.commentaire, prenom:user.prenom, nom:user.nom}";
        return $this->neo->execute_query($cypher);
    }

    public function getCheminMedias($data) {
        $cypher = "MATCH (user:USER)-[:PUBLIE]->(publication:PUBLICATION) "
                . "WHERE user.login = {monLogin} AND (publication.type = 'image' OR publication.type = 'vidéo') "
                . "RETURN {content:publication.content}";
        return $this->neo->execute_query($cypher, $data);
    }

    public function supprimerUser($data) {
        //Si l'user est l'admin d'un groupe, cela supprime le groupe
        $cypher = "MATCH(user:USER)-[membre:MEMBRE]->(groupe:GROUPE) "
                . "WHERE user.login = {monLogin} AND membre.admin = 'oui' "
                . "DETACH DELETE groupe";
        $this->neo->execute_query($cypher, $data);

        //Suppression des publications
        $cypher = "MATCH(user:USER)-[publie:PUBLIE]->(publication:PUBLICATION) "
                . "WHERE user.login = {monLogin} "
                . "DETACH DELETE publication";
        $this->neo->execute_query($cypher, $data);

        //Suppression de l'user
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login = {monLogin} "
                . "DETACH DELETE user";
        $this->neo->execute_query($cypher, $data);
    }

}

?>
