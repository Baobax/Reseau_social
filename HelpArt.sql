Create SCHEMA helpart;

SET search_path = helpart;

CREATE TABLE _personne(
       idpersonne	SERIAL PRIMARY KEY,
       civilite		varchar(50),
       nom		varchar(50),
       prenom		varchar(50),
       mail		varchar(50),
       telephone	varchar(10),
       adresse		varchar(100),
       codePostal	INTEGER,
       ville		varchar(50),
       pays		varchar(20)
);

CREATE TABLE _compte(
       log		varchar(50) PRIMARY KEY,
       mdp		varchar(50),
       valide		boolean,
       idpersonne 	INTEGER,
       CONSTRAINT compte_fk FOREIGN KEY (idpersonne) REFERENCES _personne(idpersonne)
);

CREATE TABLE _organisation(
       idOrganisation	SERIAL PRIMARY KEY,
       nom 		varchar(50),
       numLicense	INTEGER,
       statutJuridique	varchar(50),
       mail		varchar(100),
       telephoneFixe	varchar(10),
       adresse		varchar(100),
       codePostal	INTEGER,
       ville		varchar(50),
       pays		varchar(20)
);

CREATE TABLE _contact(
       idContact	SERIAL PRIMARY KEY,
       fonction		varchar(50),
       idpersonne	INTEGER,
       CONSTRAINT contact_fk FOREIGN KEY (idpersonne) REFERENCES _personne(idpersonne)
);

CREATE TABLE _demande(
       idDemande	SERIAL PRIMARY KEY,
       statut 		varchar(50),
       nomProjet	varchar(200),
       ligneArtistique	varchar(50),
       anneProjet	INTEGER,
       programme 	varchar(200),
       budgetTotal	NUMERIC,
       budgetDemande	NUMERIC,
       dateDepot	date,
       dateReport	date,
       idContact 	INTEGER,
       CONSTRAINT demande_fk FOREIGN KEY (idContact) REFERENCES _contact(idContact)
);

CREATE TABLE _budget(
       idBudget		SERIAL PRIMARY KEY,
       montant		NUMERIC,
       mecene		varchar(50),
       idDemande	INTEGER,
       CONSTRAINT budget_fk FOREIGN KEY(idDemande)REFERENCES _demande(idDemande)
);

CREATE TABLE _structure(
       idStructure	SERIAL PRIMARY KEY,
       idOrganisation	INTEGER,
       CONSTRAINT structure_fk FOREIGN KEY(idOrganisation)REFERENCES _organisation(idOrganisation)
);

CREATE TABLE _organisme(
       idOrganisme	SERIAL PRIMARY KEY,
       idOrganisation	INTEGER,
       CONSTRAINT organisme_fk FOREIGN KEY(idOrganisation)REFERENCES _organisation(idOrganisation)
);

CREATE TABLE _membreHelpArt(
       idMembreHelpArt	SERIAL PRIMARY KEY,
       idpersonne	INTEGER,
       administrateur	boolean,
       CONSTRAINT membrehelpart_fk FOREIGN KEY (idpersonne)REFERENCES _personne(idpersonne)
);

CREATE TABLE _commission(
       idCommission	SERIAL PRIMARY KEY,
       dateCommission	date,
       heureCommission	NUMERIC,
       libelle		varchar(50)
);

CREATE TABLE _membreCommission(
       idMembreCommission	SERIAL PRIMARY KEY,
       idpersonne		INTEGER,
       CONSTRAINT membrecommision_fk FOREIGN KEY (idpersonne) REFERENCES _personne(idpersonne)
);

CREATE TABLE _participe(
       idParticipe SERIAL PRIMARY KEY,
       idMembreCommission INTEGER,
       idCommission INTEGER,
       CONSTRAINT participe_fk1 FOREIGN KEY (idMembreCommission)REFERENCES _membreCommission(idMembreCommission),
       CONSTRAINT participe_fk2 FOREIGN KEY (idCommission)REFERENCES _commission(idCommission)
);
       
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Le Poulpe', 'Bernard', 'm@life.fr', '0615487562', '2 rue de la mer', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Coquillage', 'Jean', 'j@life.fr', '0616488562', '10 rue de la plage', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mme', 'La Salade', 'Yvette', 'y@life.fr', '0452487562', '4 rue du jardin', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'La Sadade', 'Yvon', 'l@hot.fr', '0713582562', '4 rue du jardin', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mme', 'L admin', 'Juliette', 'juju@life.fr', '0602343689', '25 rue de lartoir',22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mme', 'L articho', 'Monique', 'mm@life.fr', '0615487562', '6 impasse de la villette', 22600, 'Perros', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Le grand frere', 'Pascal', 'p@life.fr', '0615452541', '42 rue du thug', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Jppdt', 'Jean-Marc', 'jm@life.fr', '0685487562', '80 rue de l ennuis', 22800, 'Treburdin', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mme', 'Henri', 'Freville', 'hf@life.fr', '0615897562', '5 rue du metro', 35000, 'Rennes', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'La faim', 'Maxime', 'max@life.fr', '0615489662', '12 rue chateauGiron', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Le Pilon', 'Rene', 'r@life.fr', '0615458562', '7 rue du pot', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'La valise', 'Yan', 'y@life.fr', '0786488578', '4 rue de laeroport', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mme', 'La plante', 'Sabine', 's@life.fr', '0452456562', '8 rue du potager', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Le moulin', 'Patrick', 'pat@hot.fr', '0713545789', '5 rue du pre', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mme', 'La sbire', 'Melanie', 'mel@life.fr', '0604569689', '87 rue de la passoire',22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mme', 'La paix', 'Paulette', 'pl@life.fr', '0614587562', '13 impasse de la fierte', 22600, 'Perros', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Smith', 'John', 'js@life.fr', '0714452541', '75 rue de lamerique', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Rabi', 'Jacob', 'rj@life.fr', '0685484321', '90 rue du film', 22800, 'Treburdin', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mme', 'De la coquillette', 'Henriette', 'oi@life.fr', '0715897578', '5 rue du metro', 35000, 'Rennes', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Le rino ', 'Rino', 'rino@life.fr', '0715484562', '54 rue de l avoine', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'Le escargot ', 'Lucie', 'luc@life.fr', '0715484862', '26 rue de l avoine', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('Mm', 'Lolo ', 'Tifene', 'tf@life.fr', '0712484562', '62 rue de lentille', 22300, 'Lannion', 'France');
INSERT INTO _personne(civilite, nom, prenom, mail, telephone, adresse, codepostal, ville, pays) VALUES('M', 'L artisant ', 'thierry', 'rino@life.fr', '0765484562', '59 rue de l artisant', 22300, 'Lannion', 'France');

INSERT INTO _membrehelpart(idpersonne,administrateur) VALUES(1,FALSE);
INSERT INTO _membrehelpart(idpersonne,administrateur) VALUES(2,FALSE);
INSERT INTO _membrehelpart(idpersonne,administrateur) VALUES(3,FALSE);
INSERT INTO _membrehelpart(idpersonne,administrateur) VALUES(4,FALSE);
INSERT INTO _membrehelpart(idpersonne,administrateur) VALUES(5,TRUE);

INSERT INTO _membreCommission (idpersonne) VALUES(6);
INSERT INTO _membreCommission (idpersonne) VALUES(7);
INSERT INTO _membreCommission (idpersonne) VALUES(8);
INSERT INTO _membreCommission (idpersonne) VALUES(9);
INSERT INTO _membreCommission (idpersonne) VALUES(10);

INSERT INTO _commission(dateCommission,heureCommission,libelle) VALUES('01-01-2017',8,'commission du 1 janvier 2017');
INSERT INTO _commission(dateCommission,heureCommission,libelle) VALUES('02-02-2017',17,'commission du 2 fevrier 2017');
INSERT INTO _commission(dateCommission,heureCommission,libelle) VALUES('10-11-2016',9,'commission du 10 novembre 2016');
INSERT INTO _commission(dateCommission,heureCommission,libelle) VALUES('3-5-2016',18,'commission du 3 mai 2016');
INSERT INTO _commission(dateCommission,heureCommission,libelle) VALUES('6-3-2016',17,'commission du 6 mars 2016');

INSERT INTO _participe(idMembreCommission,idCommission) VALUES(1,1);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(2,1);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(4,1);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(2,2);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(3,2);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(4,3);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(5,3);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(1,4);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(3,5);
INSERT INTO _participe(idMembreCommission,idCommission) VALUES(5,5);

INSERT INTO _contact(fonction,idpersonne) VALUES('secretaire',10);
INSERT INTO _contact(fonction,idpersonne) VALUES('tresorier',11);
INSERT INTO _contact(fonction,idpersonne) VALUES('secretaire',12);
INSERT INTO _contact(fonction,idpersonne) VALUES('pdg',13);
INSERT INTO _contact(fonction,idpersonne) VALUES('ingenieur qualite',14);
INSERT INTO _contact(fonction,idpersonne) VALUES('chef de projet',15);
INSERT INTO _contact(fonction,idpersonne) VALUES('assistant direction',16);
INSERT INTO _contact(fonction,idpersonne) VALUES('pdg',17);
INSERT INTO _contact(fonction,idpersonne) VALUES('secretaire',18);
INSERT INTO _contact(fonction,idpersonne) VALUES('tresorier',19);
INSERT INTO _contact(fonction,idpersonne) VALUES('ingenieur',20);

INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('a','1',TRUE,1);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('b','2',TRUE,2);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('c','3',TRUE,3);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('d','4',TRUE,4);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('e','5',TRUE,5);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('f','6',TRUE,6);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('g','7',TRUE,7);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('h','8',TRUE,8);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('i','9',TRUE,9);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('j','10',TRUE,10);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('k','11',TRUE,11);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('l','12',TRUE,12);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('m','13',TRUE,13);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('n','14',TRUE,14);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('o','15',TRUE,15);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('p','16',TRUE,16);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('q','17',TRUE,17);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('r','18',TRUE,18);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('s','19',TRUE,19);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('t','20',TRUE,20);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('u','21',FALSE,21);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('v','22',FALSE,22);
INSERT INTO _compte(log,mdp,valide,idpersonne) VALUES('w','23',FALSE,23);

INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('attente','KIOSQUORAMA ECO FESTIVAL MUSICAL','Musique',2018,'',10000,5000,'05-12-2016','01-01-2017',1);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('attente','CENTRE EUROPEEN POUR L ECHANGE MUSICAL  AIDE FESTIVAL MUSIQUES ACTUELLES "AULNAY ALL BLUES"','Musique',2017,'',24000,3500,'08-12-2016','01-01-2017',1);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('attente','SASKWASH','Festival',2017,'',6000,2000,'16-10-2016','01-01-2017',1);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('attente','LES MOLIERES EVENEMENTS','Litterature',2018,'',60000,10000,'08-12-2016','02-02-2017',2);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('valide','VILLE DE MAGNY LES HAMEAUX','Musique',2017,'',12000,2500,'02-06-2016',NULL,2);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('valide','TAYASA','Musique',2017,'',6500,2300,'02-06-2016',NULL,3);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('refuse','QUARTZ 8','Musique',2017,'',1000,500,'08-12-2016',NULL,3);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('refuse','LA NUIT DES MUSICIENS ','Musique',2016,'',9000,9000,'02-06-2016',NULL,4);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('reporte','TOUCHES DE JAZZ','Musique',2018,'',6000,4500,'02-06-2016',NULL,5);
INSERT INTO _demande(statut,nomProjet,ligneArtistique,anneProjet,programme,budgetTotal,budgetDemande,dateDepot,dateReport,idContact) VALUES('reporte','BLUES SUR SEINE','Musique',2018,'',6350,2516,'08-12-2016',NULL,6);


DROP SCHEMA helpart CASCADE;
