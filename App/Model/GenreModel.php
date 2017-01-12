<?php
/**
 * Created by PhpStorm.
 * User: minh
 * Date: 12/01/17
 * Time: 17:47
 */

namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
class GenreModel
{
    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getAllGenre() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('s.id_sexe', 's.libelle_sexe')
            ->from('Sexe', 's')
            ->addOrderBy('s.libelle_sexe', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
}