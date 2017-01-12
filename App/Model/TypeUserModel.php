<?php
/**
 * Created by PhpStorm.
 * User: minh
 * Date: 12/01/17
 * Time: 16:55
 */

namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class TypeUserModel
{
    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getAllTypeUser() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('u.id_type_user', 'u.libelle_type_user')
            ->from('Type_user', 'u')
            ->addOrderBy('u.libelle_type_user', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
}