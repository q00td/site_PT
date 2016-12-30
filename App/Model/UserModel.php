<?php
namespace App\Model;

use Silex\Application;
use Doctrine\DBAL\Query\QueryBuilder;;

class UserModel {

	private $db;

	public function __construct(Application $app) {
		$this->db = $app['db'];
	}

	public function verif_login_mdp_Utilisateur($login,$mdp){
		$sql = "SELECT login,password,id_type_user,id_user FROM User WHERE login = ? AND password = ?";
		$res=$this->db->executeQuery($sql,[$login,$mdp]);   //md5($mdp);
		if($res->rowCount()==1)
			return $res->fetch();
		else
			return false;
	}

	public function getUser($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_user', 'login', 'password', 'e_mail','id_type_user')
            ->from('User')
            ->where('id= :id')
            ->setParameter('id', $id);
        return $queryBuilder->execute()->fetch();
    }

    public function editUser($donnees) {
	    // a refaire
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('User')
            ->set('login', '?')
            ->set('password','?')
            ->set('email','?')
            ->set('droit','?')

            ->where('id= ?')
            ->setParameter(0, $donnees['login'])
            ->setParameter(1, $donnees['password'])
            ->setParameter(2, $donnees['email'])
            ->setParameter(3, $donnees['valide'])
            ->setParameter(4, $donnees['droit'])

            ->setParameter(5, $donnees['id']);
        return $queryBuilder->execute();
    }

}