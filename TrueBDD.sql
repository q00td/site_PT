#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------

DROP TABLE IF EXISTS Enseigne;
DROP TABLE IF EXISTS Participe;
DROP TABLE IF EXISTS Propose;
DROP TABLE IF EXISTS Emploi;
DROP TABLE IF EXISTS Collocation;
DROP TABLE IF EXISTS Covoiturage;
DROP TABLE IF EXISTS Objet;
DROP TABLE IF EXISTS Evenement;
DROP TABLE IF EXISTS Cours;
DROP TABLE IF EXISTS Matiere;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Etablissement;
DROP TABLE IF EXISTS Type_user;
DROP TABLE IF EXISTS Sexe;

#------------------------------------------------------------
# Table: User
#------------------------------------------------------------

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

INSERT INTO User VALUES(NULL,'Admin','Coco','Chanel','000000001','cocochanel@gmail.com','coco','1980-01-01','Informatique',1,1,1);
INSERT INTO User VALUES(NULL,'Hugo','Hugo','Vicaire','199999999','vicairehugo@gmail.com','aaa','1996-06-25','STMG',2,1,1);
INSERT INTO User VALUES(NULL,'Jean','Jean','Michel','0000000002','jeanmichel@gmail.com','jeanmichou','1990-02-25','Informatique',2,2,1);
INSERT INTO User VALUES(NULL,'Martine','Martine','limo','0000000003','martinelimo@gmail.com','martine','1995-08-15','Carrières sociales',2,1,2);
INSERT INTO User VALUES(NULL,'Pedro','Pedro','Miguel','0000000004','pedromiguel@gmail.com','pedro','1997-11-15','Génie Thermique',2,1,3);
INSERT INTO User(id_user,login,e_mail,password,id_type_user) VALUES(NULL,'Hopital','hopital@gmail.com','hopital',5);
INSERT INTO User(id_user,login,e_mail,password,id_type_user) VALUES(NULL,'General Electric','ge@gmail.com','ge',5);
INSERT INTO User(id_user,login,nom_user,prenom_user,e_mail,password,date_naissance,id_type_user,id_etablissement,id_sexe)
VALUES(NULL,'Prof1','Prof1nom','Prof1prenom','prof1@gmail.com','prof1pw','1970-01-01',3,1,1);
INSERT INTO User(id_user,login,nom_user,prenom_user,e_mail,password,date_naissance,id_type_user,id_etablissement,id_sexe)
VALUES(NULL,'Prof2','Prof2nom','Prof2prenom','prof2@gmail.com','prof2pw','1971-01-01',3,2,2);
INSERT INTO User(id_user,login,nom_user,prenom_user,e_mail,password,date_naissance,id_type_user,id_etablissement,id_sexe)
VALUES(NULL,'Prof3','Prof3nom','Prof3prenom','prof3@gmail.com','prof3pw','1973-03-01',3,6,1);

#------------------------------------------------------------
# Table: Type_user   
#------------------------------------------------------------

CREATE TABLE Type_user(
        id_type_user int Auto_increment NOT NULL,
        libelle_type_user Varchar(25) NOT NULL,
        PRIMARY KEY (id_type_user)
)ENGINE=InnoDB;

INSERT INTO Type_user VALUES(NULL,'Admin');
INSERT INTO Type_user VALUES(NULL,'Elève');
INSERT INTO Type_user VALUES(NULL,'Professeur');
INSERT INTO Type_user VALUES(NULL,'Personnel');
INSERT INTO Type_user VALUES(NULL,'Entreprise');

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
INSERT INTO Etablissement VALUES(NULL,'IUT Belfort Montbéliard','Montbéliard');
INSERT INTO Etablissement VALUES(NULL,'Université','Montbéliard');
INSERT INTO Etablissement VALUES(NULL,'Université','Belfort');
INSERT INTO Etablissement VALUES(NULL,'UTBM','Sevenans');
INSERT INTO Etablissement VALUES(NULL,'UTBM','Belfort');
INSERT INTO Etablissement VALUES(NULL,'UTBM','Montbéliard');
INSERT INTO Etablissement VALUES(NULL,'Lycée Condorcet','Belfort');
INSERT INTO Etablissement VALUES(NULL,'Lycée Follereau','Belfort');
INSERT INTO Etablissement VALUES(NULL,'CFA','Belfort');
INSERT INTO Etablissement VALUES(NULL,'IFSI','Belfort');

#------------------------------------------------------------
# Table: Matiere
#------------------------------------------------------------

CREATE TABLE Matiere(
        id_matiere  int (11) Auto_increment  NOT NULL ,
        nom_matiere Varchar (25) ,
        PRIMARY KEY (id_matiere )
)ENGINE=InnoDB;

INSERT INTO Matiere VALUES(NULL,'Anglais');
INSERT INTO Matiere VALUES(NULL,'Francais');
INSERT INTO Matiere VALUES(NULL,'Italien');
INSERT INTO Matiere VALUES(NULL,'Allemand');
INSERT INTO Matiere VALUES(NULL,'Espagnol');
INSERT INTO Matiere VALUES(NULL,'Informatique');
INSERT INTO Matiere VALUES(NULL,'Mathématiques');
INSERT INTO Matiere VALUES(NULL,'Musique');
INSERT INTO Matiere VALUES(NULL,'Management');
INSERT INTO Matiere VALUES(NULL,'Droit');
INSERT INTO Matiere VALUES(NULL,'Comptabilité');
INSERT INTO Matiere VALUES(NULL,'Expression-Communication');
INSERT INTO Matiere VALUES(NULL,'Marketing');
INSERT INTO Matiere VALUES(NULL,'Politique');
INSERT INTO Matiere VALUES(NULL,'Gestion');
INSERT INTO Matiere VALUES(NULL,'Production audiovisuelle');
INSERT INTO Matiere VALUES(NULL,'Physique');
INSERT INTO Matiere VALUES(NULL,'Chimie');

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

INSERT INTO Cours VALUES(NULL,'Anglais Future of machin','Présentation Future of machin',1,1);
INSERT INTO Cours VALUES(NULL,'Expression communication','Présentation des techniques de communication',1,12);
INSERT INTO Cours VALUES(NULL,'Politique','Présentation politique, comment entrer dans la politique locale',1,14);

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

INSERT INTO Evenement VALUES(NULL,'2017-02-16','Belfort','Soirée déguisée');
INSERT INTO Evenement VALUES(NULL,'2017-01-15','Belfort','Hackaton');
INSERT INTO Evenement VALUES(NULL,'2017-01-20','Montbéliard','Sochaux-Valenciennes');
INSERT INTO Evenement VALUES(NULL,'2017-01-22','Belfort','Soirée Post Partiels Inf');

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

INSERT INTO Objet VALUES(NULL,'Micro-Ondes','Micro-ondes de qualité,
rechauffe toutes sortes de plats. Meilleur amis de l Etudiant,
même s il ne permet pas forcement de faire cuire des pâtes','Belfort',69.69,2);

INSERT INTO Objet VALUES(NULL,'Baskets Nike','Baskets Nike Air max one très bon état','Belfort',50,2);
INSERT INTO Objet VALUES(NULL,'Baskets Adidas','Baskets Adidas Stan Smith très bon état','Montbéliard',38,3);
INSERT INTO Objet VALUES(NULL,'Pc Asus','Pc asus rog très bon état','Belfort',400,2);

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

INSERT INTO Covoiturage VALUES(NULL,'Belfort','Besançon',13.42,'2017-01-22',2);
INSERT INTO Covoiturage VALUES(NULL,'Montbéliard','Belfort',4,'2017-02-27',3);
INSERT INTO Covoiturage VALUES(NULL,'Belfort','Montbéliard',3.5,'2017-02-01',2);
INSERT INTO Covoiturage VALUES(NULL,'Belfort','Dijon',14,'2017-03-16',2);
INSERT INTO Covoiturage VALUES(NULL,'Dole','Montbéliard',10,'2017-01-25',3);

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

INSERT INTO Collocation VALUES(NULL,'Bonjour, Etudiant en DUT info cherche collocataire',100,'Belfort',2);
INSERT INTO Collocation VALUES(NULL,'Bonjour, Etudiant de montbéliard cherche collocataire pas chiant et qui fait le ménage',150,'Montbéliard',3);
INSERT INTO Collocation VALUES(NULL,'Bonjour, Etudiant de Belfort cherche collocataire sociable',128,'Belfort',4);
INSERT INTO Collocation VALUES(NULL,'Bonjour, Etudiant de Belfort cherche collocataire tolérant les animaux',80,'Belfort',5);

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

INSERT INTO Emploi VALUES(NULL,'Infirmier','2016-08-18',NULL,'Recherche Infirmier en sortie d école d infirmier, spécialisé avec les personnes de grand âge','CDI');
INSERT INTO Emploi VALUES(NULL,'Stagiaire','2017-05-09','2017-07-15','Recherche Stagiaire compétent en informatique plus particulièrement en C, Java ','Stage');

#------------------------------------------------------------
# Table: propose
#------------------------------------------------------------

CREATE TABLE Propose(
        id_user   Int NOT NULL ,
        id_emploi Int NOT NULL ,
        PRIMARY KEY (id_user ,id_emploi )
)ENGINE=InnoDB;

INSERT INTO Propose VALUES(6,1);
INSERT INTO Propose VALUES(7,2);

#------------------------------------------------------------
# Table: participe
#------------------------------------------------------------

CREATE TABLE Participe(
        id_user      Int NOT NULL ,
        id_evenement Int NOT NULL ,        
        PRIMARY KEY (id_evenement ,id_user )
)ENGINE=InnoDB;

INSERT INTO Participe VALUES(2,1);
INSERT INTO Participe VALUES(2,2);
INSERT INTO Participe VALUES(3,1);
INSERT INTO Participe VALUES(3,2);
INSERT INTO Participe VALUES(3,3);
INSERT INTO Participe VALUES(4,4);
INSERT INTO Participe VALUES(4,1);
INSERT INTO Participe VALUES(4,2);
INSERT INTO Participe VALUES(4,3);
INSERT INTO Participe VALUES(5,2);
INSERT INTO Participe VALUES(5,4);

#------------------------------------------------------------
# Table: enseigne
#------------------------------------------------------------

CREATE TABLE Enseigne(
        id_user    Int NOT NULL ,
        id_matiere Int NOT NULL ,
        PRIMARY KEY (id_user ,id_matiere )
)ENGINE=InnoDB;

INSERT INTO Enseigne VALUES(8,1);
INSERT INTO Enseigne VALUES(9,4);
INSERT INTO Enseigne VALUES(10,7);

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
