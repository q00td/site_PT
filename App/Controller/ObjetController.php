<?php
namespace App\Controller;

use App\Model\CommandeModel;
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
        if(isset($app["session"])&&$app ["session"]->get("droit")!=1){
            return "Vous n'avez pas les droits";
        }
        $this->typeObjetModel = new TypeObjetModel($app);
        $typeObjets = $this->typeObjetModel->getAllTypeObjets();
        $this->ObjetModel = new ObjetModel($app);
        $donnees = $this->ObjetModel->getObjet($id);
        return $app["twig"]->render('backOff/Objet/delete.html.twig',['typeObjets'=>$typeObjets,'donnees'=>$donnees]);
        return "add Objet";
    }

    public function validFormDelete(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!=1){
            return "Vous n'avez pas les droits";
        }
        $id=$app->escape($req->get('id'));
        if (is_numeric($id)) {
            $this->ObjetModel = new ObjetModel($app);
            $this->ObjetModel->deleteObjet($id);
            return $app->redirect($app["url_generator"]->generate("Objet.index"));
        }
        else
            return $app->abort(404, 'error Pb id form Delete');
    }


    public function edit(Application $app, $id) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!=1){
            return "Vous n'avez pas les droits";
        }
        $this->typeObjetModel = new TypeObjetModel($app);
        $typeObjets = $this->typeObjetModel->getAllTypeObjets();
        $this->ObjetModel = new ObjetModel($app);
        $donnees = $this->ObjetModel->getObjet($id);
        return $app["twig"]->render('backOff/Objet/edit.html.twig',['typeObjets'=>$typeObjets,'donnees'=>$donnees]);
        return "add Objet";
    }

    public function validFormEdit(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!=1){
            return "Vous n'avez pas les droits";
        }
        // var_dump($app['request']->attributes);
        if (isset($_POST['nom']) && isset($_POST['typeObjet_id']) and isset($_POST['nom']) and isset($_POST['photo']) and isset($_POST['id'])) {
            $donnees = [
                'nom' => htmlspecialchars($_POST['nom']),                    // echaper les entrées
                'typeObjet_id' => htmlspecialchars($app['request']->get('typeObjet_id')),
                'prix' => htmlspecialchars($req->get('prix')),
                'photo' => $app->escape($req->get('photo')),
                'id' => $app->escape($req->get('id'))//$req->query->get('photo')
            ];
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom'])) $erreurs['nom']='nom composé de 2 lettres minimum';
            if(! is_numeric($donnees['typeObjet_id']))$erreurs['typeObjet_id']='veuillez saisir une valeur';
            if(! is_numeric($donnees['prix']))$erreurs['prix']='saisir un prix valide';
            if(! preg_match("/[A-Za-z0-9]{2,}.(jpeg|jpg|png)/",$donnees['photo'])) $erreurs['photo']='nom de fichier incorrect (extension jpeg , jpg ou png)';
            if(! is_numeric($donnees['id']))$erreurs['id']='saisir une valeur numérique';
            $contraintes = new Assert\Collection(
                [
                    'id' => [new Assert\NotBlank(),new Assert\Type('digit')],
                    'typeObjet_id' => [new Assert\NotBlank(),new Assert\Type('digit')],
                    'nom' => [
                        new Assert\NotBlank(['message'=>'saisir une valeur']),
                        new Assert\Length(['min'=>2, 'minMessage'=>"Le nom doit faire au moins {{ limit }} caractères."])
                    ],
                    //http://symfony.com/doc/master/reference/constraints/Regex.html
                    'photo' => [
                        new Assert\Length(array('min' => 5)),
                        new Assert\Regex([ 'pattern' => '/[A-Za-z0-9]{2,}.(jpeg|jpg|png)/',
                            'match'   => true,
                            'message' => 'nom de fichier incorrect (extension jpeg , jpg ou png)' ]),
                    ],
                    'prix' => new Assert\Type(array(
                        'type'    => 'numeric',
                        'message' => 'La valeur {{ value }} n\'est pas valide, le type est {{ type }}.',
                    ))
                ]);
            $errors = $app['validator']->validate($donnees,$contraintes);  // ce n'est pas validateValue

            //    $violationList = $this->get('validator')->validateValue($req->request->all(), $contraintes);
//var_dump($violationList);

            //   die();
            if (count($errors) > 0) {
                // foreach ($errors as $error) {
                //     echo $error->getPropertyPath().' '.$error->getMessage()."\n";
                // }
                // //die();
                //var_dump($erreurs);

                // if(! empty($erreurs))
                // {
                $this->typeObjetModel = new TypeObjetModel($app);
                $typeObjets = $this->typeObjetModel->getAllTypeObjets();
                return $app["twig"]->render('backOff/Objet/edit.html.twig',['donnees'=>$donnees,'errors'=>$errors,'erreurs'=>$erreurs,'typeObjets'=>$typeObjets]);
            }
            else
            {
                $this->ObjetModel = new ObjetModel($app);
                $this->ObjetModel->updateObjet($donnees);
                return $app->redirect($app["url_generator"]->generate("Objet.index"));
            }

        }
        else
            return $app->abort(404, 'error Pb id form edit');

    }

    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\ObjetController::index')->bind('Objet.index');
        $controllers->get('/show', 'App\Controller\ObjetController::show')->bind('Objet.show');

        $controllers->get('/add', 'App\Controller\ObjetController::add')->bind('Objet.add');
        $controllers->post('/add', 'App\Controller\ObjetController::validFormAdd')->bind('Objet.validFormAdd');

        $controllers->get('/delete/{id}', 'App\Controller\ObjetController::delete')->bind('Objet.delete')->assert('id', '\d+');;
        $controllers->delete('/delete', 'App\Controller\ObjetController::validFormDelete')->bind('Objet.validFormDelete');

        $controllers->get('/edit/{id}', 'App\Controller\ObjetController::edit')->bind('Objet.edit')->assert('id', '\d+');;
        $controllers->put('/edit', 'App\Controller\ObjetController::validFormEdit')->bind('Objet.validFormEdit');


        return $controllers;
    }



}
