 
#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: User
#------------------------------------------------------------

DROP TABLE IF EXISTS Collocation;
DROP TABLE IF EXISTS Cours;
DROP TABLE IF EXISTS Covoiturage;
DROP TABLE IF EXISTS Propose;
DROP TABLE IF EXISTS Participe;
DROP TABLE IF EXISTS Objet;
DROP TABLE IF EXISTS Enseigne;
DROP TABLE IF EXISTS Matiere;
DROP TABLE IF EXISTS Evenement;

DROP TABLE IF EXISTS Emploi;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Type_user;
DROP TABLE IF EXISTS Sexe;
DROP TABLE IF EXISTS Etablissement;



CREATE TABLE User(
        id_user          int (11) Auto_increment  NOT NULL ,
        login         Varchar (25) ,
        nom_user         Varchar (25) ,
        prenom_user      Varchar (25) ,
        N_INE            Varchar (25) ,
        e_mail           Varchar (25) ,
        password     Varchar (25) ,
        date_naissance   Date ,
        filiere          Varchar (25) ,
        id_type_user     Int ,
        id_etablissement Int ,
        id_sexe          Int ,
        PRIMARY KEY (id_user )
)ENGINE=InnoDB;

INSERT INTO User VALUES(NULL,'Hugo','Hugo','Vicaire','199999999','vicairehugo@gmail.com','aaa','1996-06-25','STMG',1,1,1);
INSERT INTO User VALUES(NULL,'Quentin','Quentin','Oternaud','199999999','vicairehugo@gmail.com','aaa','1996-06-25','STMG',2,1,1);


#------------------------------------------------------------
# Table: Type_user   
#------------------------------------------------------------

CREATE TABLE Type_user(
        id_type_user int Auto_increment NOT NULL,
        libelle_type_user Varchar(25) NOT NULL,
        PRIMARY KEY (id_type_user)
)ENGINE=InnoDB;

INSERT INTO Type_user VALUES(NULL,'Admin');
INSERT INTO Type_user VALUES(NULL,'Eleve');


#------------------------------------------------------------
# Table: Etablissement
#------------------------------------------------------------

CREATE TABLE Etablissement(
        id_etablissement   int (11) Auto_increment  NOT NULL ,
        nom_etablissement  Varchar (25) ,
        lieu_etablissement text ,
        PRIMARY KEY (id_etablissement )
)ENGINE=InnoDB;

INSERT INTO Etablissement VALUES(NULL,'IUT Belfort Montbéliard','Belfort');

#------------------------------------------------------------
# Table: Matiere
#------------------------------------------------------------

CREATE TABLE Matiere(
        id_matiere  int (11) Auto_increment  NOT NULL ,
        nom_matiere Varchar (25) ,
        PRIMARY KEY (id_matiere )
)ENGINE=InnoDB;

INSERT INTO Matiere VALUES(NULL,'Anglais');

#------------------------------------------------------------
# Table: Cours
#------------------------------------------------------------

CREATE TABLE Cours(
        id_cours          int (11) Auto_increment  NOT NULL ,
        nom_cours         Varchar (25) ,
        description_cours text ,
        id_user           Int ,
        id_matiere        Int ,
        PRIMARY KEY (id_cours )
)ENGINE=InnoDB;

INSERT INTO Cours VALUES(NULL,'Anglais Future of machin','Présentation Future of machin',NULL,1);

#------------------------------------------------------------
# Table: Evenement
#------------------------------------------------------------

CREATE TABLE Evenement(
        id_evenement          int (11) Auto_increment  NOT NULL ,
        date_evenement        Date ,
        lieu_evenement        Varchar (25) ,
        description_evenement text ,
        PRIMARY KEY (id_evenement )
)ENGINE=InnoDB;

INSERT INTO Evenement VALUES(NULL,'2017-05-12','Belfort','Noël');

#------------------------------------------------------------
# Table: Objet
#------------------------------------------------------------

CREATE TABLE Objet(
        id_objet          int (11) Auto_increment  NOT NULL ,
    	nom_objet Varchar(25),
        description_objet text,
        lieu_objet        Varchar (25) ,
        prix_objet        Double ,
        id_user           Int NOT NULL ,
        PRIMARY KEY (id_objet ,id_user )
)ENGINE=InnoDB;

INSERT INTO Objet VALUES(NULL,'Micro-Ondes','Micro-ondes de qualités, 
rechauffe toutes sortes de plât, Meilleur amis de l Etudiant, 
même s il ne permet pas forcement de faire cuire des pâtes','Belfort',69.69,1);

#------------------------------------------------------------
# Table: Covoiturage
#------------------------------------------------------------

CREATE TABLE Covoiturage(
        id_covoiturage   int (11) Auto_increment  NOT NULL ,
        depart           Varchar (25) ,
        arrive           Varchar (25) ,
        prix_covoiturage Double ,
        date_covoiturage Date ,
        id_user          Int ,
        PRIMARY KEY (id_covoiturage )
)ENGINE=InnoDB;

INSERT INTO Covoiturage VALUES(NULL,'Belfort','Besançon',13.42,'2017-06-06',1);

#------------------------------------------------------------
# Table: Collocation
#------------------------------------------------------------

CREATE TABLE Collocation(
        id_collocation          int (11) Auto_increment  NOT NULL ,
        description_collocation text ,
        prix_collocation        Double ,
        lieu_collocation        Varchar (25) ,
        id_user                 Int ,
        PRIMARY KEY (id_collocation )
)ENGINE=InnoDB;

INSERT INTO Collocation VALUES(NULL,'Bonjour, DUT Info cherche collocation avec 
un Femme dans l espoir de parler a la gente feminie pour la primière fois de mon existance, 
je connais l intégralité des StarWars et LOTR par coeur, so plz contain your orgasms ladies',666.69,'Belfort',1);

#------------------------------------------------------------
# Table: Sexe
#------------------------------------------------------------

CREATE TABLE Sexe(
        id_sexe      int (11) Auto_increment  NOT NULL ,
        libelle_sexe varchar(25) ,
        PRIMARY KEY (id_sexe )
)ENGINE=InnoDB;

INSERT INTO Sexe VALUES(NULL,'Homme');
INSERT INTO Sexe VALUES(NULL,'Femme');
INSERT INTO Sexe VALUES(NULL,'Autre');

#------------------------------------------------------------
# Table: Emploi
#------------------------------------------------------------

CREATE TABLE Emploi(
        id_emploi          int (11) Auto_increment  NOT NULL ,
        poste_emploi       Varchar (25) ,
        date_debut_emploi  Date ,
        date_fin_emploi    Date ,
        description_emploi text,
        type_emploi        Varchar (25) ,
        PRIMARY KEY (id_emploi )
)ENGINE=InnoDB;

INSERT INTO Emploi VALUES(NULL,'PDG','2016-09-09',NULL,'Devenez le PDG de Google, c est un bon plan, j vous jure  !','CDI');

#------------------------------------------------------------
# Table: propose
#------------------------------------------------------------

CREATE TABLE Propose(
        id_user   Int NOT NULL ,
        id_emploi Int NOT NULL ,
        PRIMARY KEY (id_user ,id_emploi )
)ENGINE=InnoDB;

INSERT INTO Propose VALUES(1,1);

#------------------------------------------------------------
# Table: participe
#------------------------------------------------------------

CREATE TABLE Participe(
        id_user      Int NOT NULL ,
        id_evenement Int NOT NULL ,        
        PRIMARY KEY (id_evenement ,id_user )
)ENGINE=InnoDB;

INSERT INTO Participe VALUES(1,1);

#------------------------------------------------------------
# Table: enseigne
#------------------------------------------------------------

CREATE TABLE Enseigne(
        id_user    Int NOT NULL ,
        id_matiere Int NOT NULL ,
        PRIMARY KEY (id_user ,id_matiere )
)ENGINE=InnoDB;

INSERT INTO Enseigne VALUES(1,1);

ALTER TABLE User ADD CONSTRAINT FK_User_id_Etablissement FOREIGN KEY (id_etablissement) REFERENCES Etablissement(id_etablissement);
ALTER TABLE User ADD CONSTRAINT FK_User_id_sexe FOREIGN KEY (id_sexe) REFERENCES Sexe(id_sexe);
ALTER TABLE User ADD CONSTRAINT FK_User_id_type_user FOREIGN KEY (id_type_user) REFERENCES Type_user(id_type_user);
ALTER TABLE Cours ADD CONSTRAINT FK_Cours_id_user FOREIGN KEY (id_user) REFERENCES User(id_user);
ALTER TABLE Cours ADD CONSTRAINT FK_Cours_id_matiere FOREIGN KEY (id_matiere) REFERENCES Matiere(id_matiere);
ALTER TABLE Objet ADD CONSTRAINT FK_Objet_id_user FOREIGN KEY (id_user) REFERENCES User(id_user);
ALTER TABLE Covoiturage ADD CONSTRAINT FK_Covoiturage_id_user FOREIGN KEY (id_user) REFERENCES User(id_user);
ALTER TABLE Collocation ADD CONSTRAINT FK_Collocation_id_user FOREIGN KEY (id_user) REFERENCES User(id_user);
ALTER TABLE Propose ADD CONSTRAINT FK_Propose_id_user FOREIGN KEY (id_user) REFERENCES User(id_user);
ALTER TABLE Propose ADD CONSTRAINT FK_Propose_id_emploi FOREIGN KEY (id_emploi) REFERENCES Emploi(id_emploi);
ALTER TABLE Participe ADD CONSTRAINT FK_Participe_id_evenement FOREIGN KEY (id_evenement) REFERENCES Evenement(id_evenement);
ALTER TABLE Participe ADD CONSTRAINT FK_Participe_id_user FOREIGN KEY (id_user) REFERENCES User(id_user);
ALTER TABLE Enseigne ADD CONSTRAINT FK_Enseigne_id_user FOREIGN KEY (id_user) REFERENCES User(id_user);
ALTER TABLE Enseigne ADD CONSTRAINT FK_Enseigne_id_matiere FOREIGN KEY (id_matiere) REFERENCES Matiere(id_matiere);

