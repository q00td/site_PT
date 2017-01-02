<?php
namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
class CoursModel {
    private $db;
    public function __construct(Application $app) {
        $this->db = $app['db'];
    }
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html#join-clauses
    public function getAllCours()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('c.id_cours', 'c.nom_cours', 'c.description_cours', 'c.id_user', 'c.id_matiere', 'm.nom_matiere')
            ->from('Cours', 'c')
            ->innerJoin('c', 'Matiere', 'm', 'c.id_matiere=m.id_matiere')
            ->addOrderBy('c.id_matiere', ',c.nom_cours', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
    public function insertCours($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('cours')
            ->values([
                'nom_cours' => '?',
                'description_cours' => '?',
                'id_user' => '?',
                'id_matiere' => '?',
                'nom_matiere' => '?'
            ])
            ->setParameter(0, $donnees['nom_cours'])
            ->setParameter(1, $donnees['description_cours'])
            ->setParameter(2, $donnees['id_user'])
            ->setParameter(3, $donnees['id_matiere'])
            ->setParameter(4, $donnees['nom_matiere'])
        ;
        return $queryBuilder->execute();
    }
    function getCours($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_cours', 'nom_cours', 'description_cours', 'id_user', 'id_matiere')
            ->from('cours')
            ->where('id_cours= :id_cours')
            ->setParameter('id_cours', $id_cours);
        return $queryBuilder->execute()->fetch();
    }
    public function updateCours($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('cours')
            ->set('nom_cours', '?')
            ->set('description_cours','?')
            ->set('id_user','?')
            ->set('id_matiere','?')
            ->where('id= ?')
            ->setParameter(0, $donnees['nom_cours'])
            ->setParameter(1, $donnees['description_cours'])
            ->setParameter(2, $donnees['id_user'])
            ->setParameter(3, $donnees['id_matiere']);
        return $queryBuilder->execute();
    }
    public function deleteCours($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('cours')
            ->where('id_cours = :id_cours')
            ->setParameter('id_cours',(int)$id_cours)
        ;
        return $queryBuilder->execute();
    }
}