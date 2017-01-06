<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class ObjetModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html#join-clauses
    public function getAllObjets()
    {
//        $sql = "SELECT p.id, t.libelle, p.nom, p.prix, p.photo
//            FROM produits as p,typeProduits as t
//            WHERE p.typeProduit_id=t.id ORDER BY p.nom;";
//        $req = $this->db->query($sql);
//        return $req->fetchAll();
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.id_objet','e.nom_objet','e.description_objet','e.lieu_objet','e.id_user','e.prix_objet')
            ->from('Objet', 'e')
            ->addOrderBy('e.nom_objet','ASC');
        return $queryBuilder->execute()->fetchAll();
    }
    public function getAllPObjets($id)
    {
//        $sql = "SELECT p.id, t.libelle, p.nom, p.prix, p.photo
//            FROM produits as p,typeProduits as t
//            WHERE p.typeProduit_id=t.id ORDER BY p.nom;";
//        $req = $this->db->query($sql);
//        return $req->fetchAll();
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Objet')
            ->where('id_user= :id_user')
            ->setParameter('id_user', $id)
            ->addOrderBy('nom_objet','ASC');
        return $queryBuilder->execute()->fetchAll();
    }
    public function search($text)
    {
//        $sql = "SELECT p.id, t.libelle, p.nom, p.prix, p.photo
//            FROM produits as p,typeProduits as t
//            WHERE p.typeProduit_id=t.id ORDER BY p.nom;";
//        $req = $this->db->query($sql);
//        return $req->fetchAll();
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Objet')
            ->where('description_objet LIKE :word')
            ->setParameter('word', '%'.$text.'%')
            ->addOrderBy('nom_objet','ASC');

        return $queryBuilder->execute()->fetchAll();
    }

    public function insertObjet($donnees,$id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('Objet')
            ->values([
                'nom_objet' => '?',
                'description_objet' => '?',
                'lieu_objet' => '?',
                'prix_objet' => '?',
                'id_user' => '?'
            ])
            ->setParameter(0, $donnees['nom_objet'])
            ->setParameter(1, $donnees['description_objet'])
            ->setParameter(2, $donnees['lieu_objet'])
            ->setParameter(3, $donnees['prix_objet'])

            ->setParameter(4, $id)

        ;
        return $queryBuilder->execute();
    }

    function readObjet($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Objet')
            ->where('id_objet= :id_objet')
            ->setParameter('id_objet', $id);
        return $queryBuilder->execute()->fetch();
    }

    public function editObjet($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('Objet')
            ->set('nom_objet', '?')
            ->set('description_objet','?')
            ->set('lieu_objet','?')
            ->set('prix_objet','?')
            ->where('id_objet= ?')
            ->setParameter(0, $donnees['nom_objet'])
            ->setParameter(1, $donnees['description_objet'])
            ->setParameter(2, $donnees['lieu_objet'])
            ->setParameter(3, $donnees['prix_objet'])
            ->setParameter(4, $donnees['id_objet']);
        return $queryBuilder->execute();
    }

    public function deleteObjet($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('Objet')
            ->where('id_objet = :id_objet')
            ->setParameter('id_objet',(int)$id)
        ;
        return $queryBuilder->execute();
    }



}