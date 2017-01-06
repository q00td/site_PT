<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\ObjetModel;


use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class ObjetController implements ControllerProviderInterface
{

    public function __construct()
    {
    }

    public function index(Application $app) {
        return $this->show($app);
    }

    public function show(Application $app) {
        $this->ObjetModel = new ObjetModel($app);
        $Objets = $this->ObjetModel->getAllObjets();
        return $app["twig"]->render('backOff/Objet/Objet.html.twig',['data'=>$Objets]);
    }
    public function pShow(Application $app) {
        $this->ObjetModel = new ObjetModel($app);
        $Objets = $this->ObjetModel->getAllPObjets($app['session']->get('user_id'));
        return $app["twig"]->render('backOff/Objet/Objet.html.twig',['data'=>$Objets]);
    }
    public function search(Application $app) {
        $user=$_POST['user'];
        $this->ObjetModel = new ObjetModel($app);
        $Objets = $this->ObjetModel->getUser($user);
        return $app["twig"]->render('backOff/Objet/user.html.twig',['data'=>$Objets]);
    }
    public function getUser(Application $app) {
        $chaine=$_POST['user'];
        $this->ObjetModel = new ObjetModel($app);
        //
        $Objets = $this->ObjetModel->getUser($chaine);
        return $app["twig"]->render('backOff/Objet/user.html.twig',['data'=>$Objets]);
    }


    public function add(Application $app) {
        if(isset($app["session"])&&$app ["session"]->get("user_id")!=1){
            return "Vous n'avez pas les droits";
        }
        //$this->typeObjetModel = new TypeObjetModel($app);
        //$typeObjets = $this->typeObjetModel->getAllTypeObjets();
        return $app["twig"]->render('backOff/Objet/add.html.twig',['path'=>BASE_URL]);
        return "add Objet";
    }

    public function validFormAdd(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("user_id")!=1){
            return "Vous n'avez pas les droits";
        }
        // var_dump($app['request']->attributes);
        if (isset($_POST['nom_objet']) && isset($_POST['description_objet']) and isset($_POST['lieu_objet']) and isset($_POST['prix_objet'])) {
            $donnees = [
                'nom_objet' => htmlspecialchars($_POST['nom_objet']),                    // echapper les entrées
                'description_objet' =>htmlspecialchars($_POST['description_objet']),
                'lieu_objet' => htmlspecialchars($_POST['lieu_objet']),
                'prix_objet' => htmlspecialchars($_POST['prix_objet'])  //$req->query->get('photo')
//$req->query->get('photo')
            ];
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom_objet'])) $erreurs['nom_objet']='nom composé de 2 lettres minimum';
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['description_objet'])) $erreurs['description_objet']='description composé de 2 lettres minimum';
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['lieu_objet'])) $erreurs['lieu_objet']='lieu composé de 2 lettres minimum';
            if(! is_numeric($donnees['prix_objet'])) $erreurs['prix_objet']='prix non valide';

            if(! empty($erreurs))
            {
                var_dump($erreurs);
                return $app["twig"]->render('backOff/Objet/add.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs]);
            }
            else
            {
                $this->ObjetModel = new ObjetModel($app);
                $id=$app['session']->get("user_id");
                $this->ObjetModel->insertObjet($donnees,$id);
                return $app->redirect($app["url_generator"]->generate("Objet.index"));
            }

        }
        else
            return $app->abort(404, 'error Pb data form Add');

    }

    public function delete(Application $app, $id) {
        $id = htmlentities($id);
        return $app["twig"]->render('backOff/Objet/v_form_delete_objet.html.twig',['id'=>$id]);
    }
    public function validFormDelete(Application $app) {
        $id = htmlentities($_POST["id"]);

        $this->ObjetModel = new ObjetModel($app);
        $this->ObjetModel->deleteObjet($id);
        return $app->redirect($app["url_generator"]->generate("Objet.show"));
    }

    public function edit(Application $app, $id) {
        $id = htmlentities($id);
        $this->ObjetModel = new ObjetModel($app);
        $donnees = $this->ObjetModel->readObjet($id);
//        var_dump($donnees);
        return $app["twig"]->render('backOff/Objet/v_form_update_objet.html.twig',['objet'=>$donnees]);
    }
    public function validFormEdit(Application $app) {
        $donnees['id_objet'] = htmlentities($_POST['id_objet']);
        $donnees['nom_objet'] = htmlentities($_POST['nom_objet']);

        $donnees['lieu_objet'] = htmlentities($_POST['lieu_objet']);
        $donnees['description_objet'] = htmlentities($_POST['description_objet']);
        $donnees['prix_objet'] = htmlentities($_POST['prix_objet']);
        $this->objetModel = new ObjetModel($app);


//        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['lieu_objet']))) $erreurs['lieu_objet']='nom composé de 2 lettres minimum';
//        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['description']))) $erreurs['description']='nom composé de 2 lettres minimum';
//        if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_objet']))) $erreurs['date_objet']='entrer une date valide format aaaa-mm-jj';


        //var_dump($erreurs);

        if(! empty($erreurs)) {

            return $app["twig"]->render('backOff/Objet/v_form_update_objet.html.twig',['objet'=>$donnees,'erreurs'=>$erreurs]);
        }
        else
        {
            $this->objetModel = new ObjetModel($app);
            $this->objetModel->editObjet($donnees);
            //var_dump($donnees);
            return $app->redirect($app["url_generator"]->generate("Objet.show"));
        }

    }
    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\ObjetController::index')->bind('Objet.index');
        $controllers->get('/show', 'App\Controller\ObjetController::show')->bind('Objet.show');
        $controllers->get('/pShow', 'App\Controller\ObjetController::pShow')->bind('Objet.pShow');


        $controllers->get('/add', 'App\Controller\ObjetController::add')->bind('Objet.add');
        $controllers->post('/add', 'App\Controller\ObjetController::validFormAdd')->bind('Objet.validFormAdd');

        $controllers->get('/search', 'App\Controller\ObjetController::search')->bind('Objet.search');
        $controllers->post('/search', 'App\Controller\ObjetController::search')->bind('Objet.search');

        $controllers->get('/getUser', 'App\Controller\ObjetController::getUser')->bind('Objet.getUser');
        $controllers->post('/getUser', 'App\Controller\ObjetController::getUser')->bind('Objet.getUser');



        $controllers->get('/delete/{id}', 'App\Controller\ObjetController::delete')->bind('Objet.delete')->assert('id', '\d+');;
        $controllers->delete('/delete', 'App\Controller\ObjetController::validFormDelete')->bind('Objet.validFormDelete');

        $controllers->get('/edit/{id}', 'App\Controller\ObjetController::edit')->bind('Objet.edit')->assert('id', '\d+');;
        $controllers->post('/edit', 'App\Controller\ObjetController::validFormEdit')->bind('Objet.validFormEdit');


        return $controllers;
    }



}
