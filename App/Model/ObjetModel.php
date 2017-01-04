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

    function getObjet($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id', 'typeProduit_id', 'nom', 'prix', 'photo')
            ->from('produits')
            ->where('id= :id')
            ->setParameter('id', $id);
        return $queryBuilder->execute()->fetch();
    }

    public function updateObjet($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('produits')
            ->set('nom', '?')
            ->set('typeProduit_id','?')
            ->set('prix','?')
            ->set('photo','?')
            ->where('id= ?')
            ->setParameter(0, $donnees['nom'])
            ->setParameter(1, $donnees['typeProduit_id'])
            ->setParameter(2, $donnees['prix'])
            ->setParameter(3, $donnees['photo'])
            ->setParameter(4, $donnees['id']);
        return $queryBuilder->execute();
    }

    public function deleteObjet($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('produits')
            ->where('id = :id')
            ->setParameter('id',(int)$id)
        ;
        return $queryBuilder->execute();
    }



}