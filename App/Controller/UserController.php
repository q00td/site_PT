<?php
namespace App\Controller;

use App\Model\UserModel;
use Silex\Application;
use Silex\ControllerProviderInterface;

class UserController implements ControllerProviderInterface {

	private $userModel;

	public function index(Application $app) {
		return $this->connexionUser($app);
	}

	public function connexionUser(Application $app)
	{
		return $app["twig"]->render('v_session_connexion.html.twig');
	}

	public function validFormConnexionUser(Application $app)
	{

		$app['session']->clear();
		$donnees['login']=$app['request']->request->get('login');
		$donnees['password']=$app['request']->request->get('password');

		$this->userModel = new UserModel($app);
		$data=$this->userModel->verif_login_mdp_Utilisateur($donnees['login'],$donnees['password']);
		var_dump($data);

		if($data != NULL)
		{
			$app['session']->set('id_type_user', $data['id_type_user']);  //dans twig {{ app.session.get('droit') }}
			$app['session']->set('login', $data['login']);
			$app['session']->set('logged', 1);
			$app['session']->set('user_id',$data['id']);
			return $app->redirect($app["url_generator"]->generate("produit.index"));
		}
		else
		{
			$app['session']->set('erreur','mot de passe ou login incorrect');
			return $app["twig"]->render('v_session_connexion.html.twig');
		}
	}

	public function editUser(Application $app){
	    $this->userModel=new UserModel($app);
	    $donnees=$this->userModel->getUser($app['session']->get('user_id'));
        return $app["twig"]->render('backOff/User/edit.html.twig',['donnees'=>$donnees,'path'=>BASE_URL]);

    }

    public function validFormEditUser(Application $app) {

        $donnees = [
            'id' => htmlspecialchars($_POST['id']),                    // echapper les entrées
            'login' => htmlspecialchars($_POST['login']),
            'password' => htmlspecialchars($_POST['password']),
            'email' => htmlspecialchars($_POST['email']),
            'valide' =>htmlspecialchars($_POST['valide']),
            'droit' => htmlspecialchars($_POST['droit'])  //$req->query->get('photo')
        ];

        $this->userModel = new UserModel($app);
        $this->userModel->editUser($donnees);
        var_dump($donnees);

        return $app->redirect($app["url_generator"]->generate("produit.show"));




    }



	public function deconnexionSession(Application $app)
	{
		$app['session']->clear();
		$app['session']->getFlashBag()->add('msg', 'vous êtes déconnecté');
        return $app["twig"]->render('v_session_connexion.html.twig');
	}

	public function connect(Application $app) {
		$controllers = $app['controllers_factory'];
		$controllers->match('/', 'App\Controller\UserController::index')->bind('user.index');
		$controllers->get('/login', 'App\Controller\UserController::connexionUser')->bind('user.login');
		$controllers->post('/login', 'App\Controller\UserController::validFormConnexionUser')->bind('user.validFormlogin');
		$controllers->get('/logout', 'App\Controller\UserController::deconnexionSession')->bind('user.logout');
        $controllers->get('/editUser', 'App\Controller\UserController::editUser')->bind('user.editUser');
        $controllers->put('/validFormEditUser', 'App\Controller\UserController::validFormEditUser')->bind('user.validFormEditUser');



        return $controllers;
	}
}