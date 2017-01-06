<?php
/**
 * Created by PhpStorm.
 * User: minh
 * Date: 06/01/17
 * Time: 20:35
 */

namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class EmploiModel
{
    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html#join-clauses

    public function getAllEmplois()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.id_emploi','e.poste_emploi','e.date_debut_emploi','e.date_fin_emploi','e.description_emploi','e.type_emploi')
            ->from('Emploi', 'e')
            ->addOrderBy('e.poste_emploi','ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    public function insertEmploi($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        var_dump($donnees);
        $queryBuilder->insert('Emploi')
            ->values([
                'poste_emploi'=> '?',
                'date_debut_emploi'=> '?',
                'date_fin_emploi'=> '?',
                'description_emploi'=> '?',
                'type_emploi'=> '?'
            ])
            ->setParameter(0, $donnees['poste_emploi'])
            ->setParameter(1, $donnees['date_debut_emploi'])
            ->setParameter(2, $donnees['date_fin_emploi'])
            ->setParameter(3, $donnees['description_emploi'])
            ->setParameter(4, $donnees['type_emploi'])
        ;
        return $queryBuilder->execute();
    }

    public function readEmploi($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Emploi')
            ->where ('id_Emploi = ?')
            ->setParameter (0, $id)
        ;
        return $queryBuilder->execute()->fetch();
    }

    public function deleteEmploi($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('Emploi')
            ->where ('id_emploi   = ?')
            ->setParameter (0, $id)
        ;

        return $queryBuilder->execute();
    }

    public function editEmploi($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        var_dump($donnees);
        $queryBuilder->update('Emploi')
            ->set('poste_emploi ', '?')
            ->set('date_debut_emploi ', '?')
            ->set('date_fin_emploi', '?')
            ->set('description_emploi', '?')
            ->set('type_emploi', '?')
            -> where('id_emploi = ?')
            ->setParameter(0, $donnees['poste_emploi'])
            ->setParameter(1, $donnees['date_debut_emploi'])
            ->setParameter(2, $donnees['date_fin_emploi'])
            ->setParameter(3, $donnees['description_emploi'])
            ->setParameter(4, $donnees['type_emploi'])
            ->setParameter(5, $donnees['id_emploi'])
        ;
        echo  $queryBuilder;
        return $queryBuilder->execute();
    }
}