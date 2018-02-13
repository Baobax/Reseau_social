<?php

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('neo');
    }

    public function connexion($login, $password) {
        $cypher = "MATCH(user:USER) WHERE user.login = '$login' AND user.password = '$password' RETURN {login:user.login, couleurSite:user.couleurSite, couleurTexte:user.couleurTexte, fondSite:user.fondSite}";
        return $this->neo->execute_query($cypher);
    }

    public function inscription($postdata) {
        $cypherTest = "MATCH(user:USER) "
                . "WHERE user.login = '" . $postdata['login'] . "' "
                . "RETURN user.login";
        $existeDeja = $this->neo->execute_query($cypherTest);

        if (isset($existeDeja[0])) {
            return false;
        }


        $cypher = "CREATE(user:USER{login: '" . $postdata['login'] . "', password: '" . $postdata['password'] . "', "
                . "prenom: '" . $postdata['prenom'] . "', nom: '" . $postdata['nom'] . "', "
                . "email: '" . $postdata['email'] . "', dateNaissance: '" . $postdata['dateNaissance'] . "', "
                . "genre: '" . $postdata['genre'] . "', annee: '" . $postdata['annee'] . "', "
                . "discipline: '" . $postdata['discipline'] . "', couleurSite: '" . $postdata['couleurSite'] . "', "
                . "couleurTexte: '" . $postdata['couleurTexte'] . "', fondSite: '" . $postdata['fondSite'] . "'})";
        $this->neo->execute_query($cypher);

        return true;
    }

    public function check_si_email_existe($email) {
        $cypher = "MATCH(user:USER) WHERE user.email = '$email' RETURN user.email";
        $existeDeja = $this->neo->execute_query($cypher);

        if (isset($existeDeja[0])) {
            return false;
        } else {
            return true;
        }
    }

    public function getMesInfos($monLogin) {
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login = '$monLogin' "
                . "RETURN {login:user.login, prenom:user.prenom, nom:user.nom}";
        return $this->neo->execute_query($cypher);
    }

    public function updateParametresCosmetiques($login, $couleurSite, $couleurTexte, $fondSite) {
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login = '$login' "
                . "SET user.couleurSite = '$couleurSite', user.couleurTexte = '$couleurTexte', user.fondSite = '$fondSite'";
        $this->neo->execute_query($cypher);
    }

    public function publierTexte($monLogin, $texte, $typePublication, $dateAjout) {
        $cypher = "MATCH (user:USER) "
                . "WHERE user.login = '$monLogin' "
                . "CREATE (user)-[:PUBLIE]->(:PUBLICATION{content:'$texte', type:'$typePublication', dateAjout:'$dateAjout'})";
        $this->neo->execute_query($cypher);
    }

    public function publierMedia($monLogin, $lienMedia, $legende, $typePublication, $dateAjout) {
        $cypher = "MATCH (user:USER) "
                . "WHERE user.login = '$monLogin' "
                . "CREATE (user)-[:PUBLIE]->(:PUBLICATION{content:'$lienMedia', legende:'$legende', type:'$typePublication', dateAjout:'$dateAjout'})";
        $this->neo->execute_query($cypher);
    }

    public function getPublications($loginUser) {
        $cypher = "MATCH (publication:PUBLICATION), (user:USER) "
                . "WHERE (user{login:'$loginUser'})-[:PUBLIE]->(publication) "
                . "RETURN {nbjaimes:SIZE((publication)<-[:AIME]-(:USER)), nbcommentaires:SIZE((publication)<-[:COMMENTAIRE]-(:USER)), id:ID(publication), content:publication.content, type:publication.type, legende:publication.legende, login:user.login, prenom:user.prenom, nom:user.nom} "
                . "ORDER BY publication.dateAjout DESC";
        return $this->neo->execute_query($cypher);
    }

    public function getPublication($loginUser, $idPublication) {
        $cypher = "MATCH (publication:PUBLICATION), (user:USER) "
                . "WHERE (user{login:'$loginUser'})-[:PUBLIE]->(publication) AND ID(publication) = $idPublication "
                . "RETURN {nbjaimes:SIZE((publication)<-[:AIME]-(:USER)), nbcommentaires:SIZE((publication)<-[:COMMENTAIRE]-(:USER)), id:ID(publication), content:publication.content, type:publication.type, legende:publication.legende, login:user.login, prenom:user.prenom, nom:user.nom}";
        return $this->neo->execute_query($cypher);
    }

    public function getCommentairesPublication($idPublication) {
        $cypher = "MATCH (user:USER)-[commentaire:COMMENTAIRE]->(publication:PUBLICATION) "
                . "WHERE ID(publication) = $idPublication "
                . "RETURN {id:ID(commentaire), commentaire:commentaire.commentaire, prenom:user.prenom, nom:user.nom}";
        return $this->neo->execute_query($cypher);
    }

    public function supprimerUser($login) {
        $cypher = "MATCH(user:USER) "
                . "WHERE user.login = '$login' "
                . "DETACH DELETE user";
        $this->neo->execute_query($cypher);
    }

}

?>
