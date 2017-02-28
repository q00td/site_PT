<?php
namespace App\Controller;

use App\Model\GenreModel;
use App\Model\TypeUserModel;
use App\Model\UserModel;
use Silex\Application;
use Silex\ControllerProviderInterface;

class UserController implements ControllerProviderInterface {

	private $userModel;
    private $typeUserModel;

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
			$app['session']->set('user_id',$data['id_user']);
            $app['session']->set('e_mail',$data['e_mail']);
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



    //*********************************************************************  ELEVE  ************************************************************************
    public function addEleve(Application $app) {

        $this->genreModel = new GenreModel($app);
        $genre = $this->genreModel->getAllGenre();

        return $app["twig"]->render('add_user.html.twig',['genreUser'=>$genre]);
    }



    public function validFormAddEleve(Application $app) {


        if (isset($_POST['login']) && isset($_POST['nom_user']) && isset($_POST['prenom_user'])
            && isset($_POST['N_INE']) && isset($_POST['e_mail']) && isset($_POST['password'])
            && isset($_POST['date_naissance'])
            && isset($_POST['filiere']) && isset($_POST['id_sexe']) ){
            $donnees = [
                'login' => htmlspecialchars($_POST['login']),
                'nom_user' => htmlspecialchars($_POST['nom_user']),
                'prenom_user' => htmlspecialchars($_POST['prenom_user']),
                'N_INE' => htmlspecialchars($_POST['N_INE']),
                'e_mail' => htmlspecialchars($_POST['e_mail']),
                'password' => htmlspecialchars($_POST['password']),
                'date_naissance' => htmlspecialchars($_POST['date_naissance']),
                'filiere' => htmlspecialchars($_POST['filiere']),
                'id_sexe' => htmlspecialchars($_POST['id_sexe'])
            ];


        // Controle
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['login']))) $erreurs['login']='login composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom_user']))) $erreurs['nom_user']='nom_user composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['prenom_user']))) $erreurs['prenom_user']='prenom_user composé de 2 lettres minimum';
            if(! is_numeric($donnees['N_INE']))$erreurs['N_INE']='veuillez saisir une valeur';
            if ((! preg_match("/^[A-Za-z ]{2,}@edu.univ-fcomte.fr/",$donnees['e_mail']))) $erreurs['e_mail']='entrer une adresse universitaire';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['password']))) $erreurs['password']='password composé de 2 lettres minimum';
            if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_naissance']))) $erreurs['date_naissance']='entrer une date valide format aaaa-mm-jj';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['filiere']))) $erreurs['filiere']='filiere composé de 2 lettres minimum';
            if(! is_numeric($donnees['id_sexe']))$erreurs['id_sexe']='saisir une valeur numérique';

            if(! empty($erreurs)) {
                $this->genreModel = new GenreModel($app);
                $genre = $this->genreModel->getAllGenre();
                return $app["twig"]->render('add_user.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'genreUser' => $genre]);
            }else{
                $this->userModel = new UserModel($app);
                $this->userModel->insertUserEleve($donnees);
                return $app->redirect($app["url_generator"]->generate("user.choice"));
            }

        } else
            return "error ????? PB data form";
    }


    //*********************************************************************  PROFESSEUR  ************************************************************************
    public function addProf(Application $app) {

        $this->genreModel = new GenreModel($app);
        $genre = $this->genreModel->getAllGenre();

        return $app["twig"]->render('add_prof.html.twig',['genreUser'=>$genre]);
    }



    public function validFormAddProf(Application $app) {


        if (isset($_POST['login']) && isset($_POST['nom_user']) && isset($_POST['prenom_user'])
            && isset($_POST['e_mail']) && isset($_POST['password'])
            && isset($_POST['date_naissance'])
            && isset($_POST['id_sexe']) ){
            $donnees = [
                'login' => htmlspecialchars($_POST['login']),
                'nom_user' => htmlspecialchars($_POST['nom_user']),
                'prenom_user' => htmlspecialchars($_POST['prenom_user']),
                'e_mail' => htmlspecialchars($_POST['e_mail']),
                'password' => htmlspecialchars($_POST['password']),
                'date_naissance' => htmlspecialchars($_POST['date_naissance']),
                'id_sexe' => htmlspecialchars($_POST['id_sexe'])
            ];


            // Controle
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['login']))) $erreurs['login']='login composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom_user']))) $erreurs['nom_user']='nom_user composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['prenom_user']))) $erreurs['prenom_user']='prenom_user composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['e_mail']))) $erreurs['e_mail']='e_mail composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['password']))) $erreurs['password']='password composé de 2 lettres minimum';
            if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_naissance']))) $erreurs['date_naissance']='entrer une date valide format aaaa-mm-jj';
            if(! is_numeric($donnees['id_sexe']))$erreurs['id_sexe']='saisir une valeur numérique';

            if(! empty($erreurs)) {
                $this->genreModel = new GenreModel($app);
                $genre = $this->genreModel->getAllGenre();
                return $app["twig"]->render('add_prof.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'genreUser' => $genre]);
            }else{
                $this->userModel = new UserModel($app);
                $this->userModel->insertUserProf($donnees);
                return $app->redirect($app["url_generator"]->generate("user.choice"));
            }

        } else
            return "error ????? PB data form";
    }

//*********************************************************************  PROFESSIONNEL  ************************************************************************
    public function addPro(Application $app) {

        $this->genreModel = new GenreModel($app);
        $genre = $this->genreModel->getAllGenre();

        return $app["twig"]->render('add_pro.html.twig',['genreUser'=>$genre]);
    }



    public function validFormAddPro(Application $app) {


        if (isset($_POST['login']) && isset($_POST['nom_user'])
            && isset($_POST['e_mail']) && isset($_POST['password'])){
            $donnees = [
                'login' => htmlspecialchars($_POST['login']),
                'nom_user' => htmlspecialchars($_POST['nom_user']),
                'e_mail' => htmlspecialchars($_POST['e_mail']),
                'password' => htmlspecialchars($_POST['password'])
            ];


            // Controle
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['login']))) $erreurs['login']='login composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom_user']))) $erreurs['nom_user']='nom_user composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['e_mail']))) $erreurs['e_mail']='e_mail composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['password']))) $erreurs['password']='password composé de 2 lettres minimum';



            if(! empty($erreurs)) {
                $this->genreModel = new GenreModel($app);
                $genre = $this->genreModel->getAllGenre();
                return $app["twig"]->render('add_pro.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'genreUser' => $genre]);
            }else{
                $this->userModel = new UserModel($app);
                $this->userModel->insertUserPro($donnees);
                return $app->redirect($app["url_generator"]->generate("user.choice"));
            }

        } else
            return "error ????? PB data form";
    }

	public function deconnexionSession(Application $app)
	{
		$app['session']->clear();
		$app['session']->getFlashBag()->add('msg', 'vous êtes déconnecté');
        return $app["twig"]->render('v_session_connexion.html.twig');
	}


    public function choice(Application $app) {
//        $this->typeUserModel = new TypeUserModel($app);
//        $typeUser = $this->typeUserModel->getAllTypeUser();

        $this->genreModel = new GenreModel($app);
        $genre = $this->genreModel->getAllGenre();

        return $app["twig"]->render('choix.html.twig',['genreUser'=>$genre]);
    }

	public function connect(Application $app) {
		$controllers = $app['controllers_factory'];
		$controllers->match('/', 'App\Controller\UserController::index')->bind('user.index');
		$controllers->get('/login', 'App\Controller\UserController::connexionUser')->bind('user.login');
        $controllers->post('/login', 'App\Controller\UserController::validFormConnexionUser')->bind('user.validFormlogin');

        $controllers->get('/createEleve', 'App\Controller\UserController::addEleve')->bind('user.createEleve');
        $controllers->post('/createEleve', 'App\Controller\UserController::validFormAddEleve')->bind('user.validFormAddEleve');

        $controllers->get('/choice', 'App\Controller\UserController::choice')->bind('user.choice');

        $controllers->get('/createProf', 'App\Controller\UserController::addProf')->bind('user.createProf');
        $controllers->post('/createProf', 'App\Controller\UserController::validFormAddProf')->bind('user.validFormAddProf');

        $controllers->get('/createPro', 'App\Controller\UserController::addPro')->bind('user.createPro');
        $controllers->post('/createPro', 'App\Controller\UserController::validFormAddPro')->bind('user.validFormAddPro');


        $controllers->get('/logout', 'App\Controller\UserController::deconnexionSession')->bind('user.logout');
        $controllers->get('/editUser', 'App\Controller\UserController::editUser')->bind('user.editUser');
        $controllers->put('/validFormEditUser', 'App\Controller\UserController::validFormEditUser')->bind('user.validFormEditUser');




        return $controllers;
	}
}