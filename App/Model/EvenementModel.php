<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class EvenementModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html#join-clauses

    public function getAllEvenements()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.id_evenement','e.date_evenement','e.lieu_evenement','e.description_evenement')
            ->from('Evenement', 'e')
            ->addOrderBy('e.date_evenement','ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    public function insertEvenement($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        var_dump($donnees);
        $queryBuilder->insert('Evenement')
            ->values([
                'date_evenement' => '?',
                'lieu_evenement' => '?',
                'description_evenement' => '?'
            ])
            ->setParameter(0, $donnees['date_evenement'])
            ->setParameter(1, $donnees['lieu_evenement'])
            ->setParameter(2, $donnees['description'])
        ;
        return $queryBuilder->execute();
    }

    public function readEvenement($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Evenement')
            ->where ('id_evenement = ?')
            ->setParameter (0, $id)
        ;
        return $queryBuilder->execute()->fetch();
    }

    public function deleteEvenement($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('Evenement')
            ->where ('id_evenement   = ?')
            ->setParameter (0, $id)
        ;

        return $queryBuilder->execute();
    }

    public function editEvenement($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        var_dump($donnees);
        $queryBuilder->update('Evenement')
            ->set('lieu_evenement ', '?')
            ->set('description_evenement ', '?')
            ->set('date_evenement', '?')
            -> where('id_evenement = ?')
            ->setParameter (0, $donnees["lieu_evenement"])
            ->setParameter (1, $donnees["description"])
            ->setParameter (2,$donnees["date_evenement"])
            ->setParameter (3, $donnees["id_evenement"])
        ;
        echo  $queryBuilder;
        return $queryBuilder->execute();
    }

    public function  searchEvenements($text){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.id_evenement','e.date_evenement','e.lieu_evenement','e.description_evenement')
            ->from('Evenement', 'e')
            ->where ('description_evenement LIKE :word')
            ->setParameter('word', '%'.$text.'%')
            ->addOrderBy('e.date_evenement','ASC');
        return $queryBuilder->execute()->fetchAll();
    }
}