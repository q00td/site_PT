<?php
namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
class CovoitModel {
    private $db;
    public function __construct(Application $app) {
        $this->db = $app['db'];
    }
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html#join-clauses
    public function getAllCovoit()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('c.id_Covoiturage', 'c.depart', 'c.arrive', 'c.id_user', 'c.prix_covoiturage', 'c.date_covoiturage')
            ->from('Covoit', 'c')
            ->addOrderBy('c.depart', ',c.date', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
    public function insertCovoit($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('Covoit')
            ->values([
                'depart' => '?',
                'arrive' => '?',
                'id_user' => '?',
                'prix_covoiturage' => '?',
                'date_covoiturage' => '?'
            ])
            ->setParameter(0, $donnees['depart'])
            ->setParameter(1, $donnees['arrive'])
            ->setParameter(2, $donnees['id_user'])
            ->setParameter(3, $donnees['prix_covoiturage'])
            ->setParameter(4, $donnees['date_covoiturage'])
        ;
        return $queryBuilder->execute();
    }
    function getCovoit($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_covoiturage', 'depart', 'arrive', 'id_user', 'prix_covoiturage','date_covoiturage')
            ->from('covoiturage')
            ->where('id_covoiturage= :id_covoiturage')
            ->setParameter('id_covoiturage', $id_covoiturage);
        return $queryBuilder->execute()->fetch();
    }
    public function updateCovoit($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('covoiturage')
            ->set('depart', '?')
            ->set('arrive','?')
            ->set('id_user','?')
            ->set('prix_covoiturage','?')
            ->set('date_covoiturage','?')
            ->where('id_covoiturage= ?')
            ->setParameter(0, $donnees['depart'])
            ->setParameter(1, $donnees['arrive'])
            ->setParameter(2, $donnees['prix_covoiturage'])
            ->setParameter(3, $donnees['date_covoiturage']);
        return $queryBuilder->execute();
    }
    public function deleteCovoit($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('covoiturage')
            ->where('id_covoiturage = :id_covoiturage')
            ->setParameter('id_covoiturage',(int)$id_covoiturage)
        ;
        return $queryBuilder->execute();
    }
}