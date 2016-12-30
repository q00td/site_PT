<?php
namespace App\Controller;

use App\Model\CommandeModel;
use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\EvenementModel;


use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class EvenementController implements ControllerProviderInterface
{

    public function __construct()
    {
    }

    public function index(Application $app) {
        return $this->show($app);
    }

    public function show(Application $app) {
        $this->EvenementModel = new EvenementModel($app);
        $Evenements = $this->EvenementModel->getAllEvenements();
        return $app["twig"]->render('backOff/Evenement/homepage.html.twig',['data'=>$Evenements]);
    }


    public function add(Application $app) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->typeEvenementModel = new TypeEvenementModel($app);
        $typeEvenements = $this->typeEvenementModel->getAllTypeEvenements();
        return $app["twig"]->render('backOff/Evenement/add.html.twig',['typeEvenements'=>$typeEvenements,'path'=>BASE_URL]);
        return "add Evenement";
    }

    public function validFormAdd(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        // var_dump($app['request']->attributes);
        if (isset($_POST['nom']) && isset($_POST['typeEvenement_id']) and isset($_POST['nom']) and isset($_POST['photo'])) {
            $donnees = [
                'nom' => htmlspecialchars($_POST['nom']),                    // echapper les entrées
                'typeEvenement_id' => htmlspecialchars($app['request']->get('typeEvenement_id')),
                'prix' => htmlspecialchars($req->get('prix')),
                'photo' => $app->escape($req->get('photo')),
                'stock' => htmlspecialchars($_POST['stock'])    //$req->query->get('photo')
            ];
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom'])) $erreurs['nom']='nom composé de 2 lettres minimum';
            if(! is_numeric($donnees['typeEvenement_id']))$erreurs['typeEvenement_id']='veuillez saisir une valeur';
            if(! is_numeric($donnees['prix']))$erreurs['prix']='saisir un prix valide';
            if(! is_numeric($donnees['stock']))$erreurs['stock']='saisir une valeur numérique entière';
            if(! preg_match("/[A-Za-z0-9]{2,}.(jpeg|jpg|png)/",$donnees['photo'])) $erreurs['photo']='nom de fichier incorrect (extension jpeg , jpg ou png)';

            if(! empty($erreurs))
            {
                $this->typeEvenementModel = new TypeEvenementModel($app);
                $typeEvenements = $this->typeEvenementModel->getAllTypeEvenements();
                return $app["twig"]->render('backOff/Evenement/add.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'typeEvenements'=>$typeEvenements]);
            }
            else
            {
                $this->EvenementModel = new EvenementModel($app);
                $this->EvenementModel->insertEvenement($donnees);
                return $app->redirect($app["url_generator"]->generate("Evenement.index"));
            }

        }
        else
            return $app->abort(404, 'error Pb data form Add');

    }

    public function delete(Application $app, $id) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->typeEvenementModel = new TypeEvenementModel($app);
        $typeEvenements = $this->typeEvenementModel->getAllTypeEvenements();
        $this->EvenementModel = new EvenementModel($app);
        $donnees = $this->EvenementModel->getEvenement($id);
        return $app["twig"]->render('backOff/Evenement/delete.html.twig',['typeEvenements'=>$typeEvenements,'donnees'=>$donnees]);
        return "add Evenement";
    }

    public function validFormDelete(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $id=$app->escape($req->get('id'));
        if (is_numeric($id)) {
            $this->EvenementModel = new EvenementModel($app);
            $this->EvenementModel->deleteEvenement($id);
            return $app->redirect($app["url_generator"]->generate("Evenement.index"));
        }
        else
            return $app->abort(404, 'error Pb id form Delete');
    }


    public function edit(Application $app, $id) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->typeEvenementModel = new TypeEvenementModel($app);
        $typeEvenements = $this->typeEvenementModel->getAllTypeEvenements();
        $this->EvenementModel = new EvenementModel($app);
        $donnees = $this->EvenementModel->getEvenement($id);
        return $app["twig"]->render('backOff/Evenement/edit.html.twig',['typeEvenements'=>$typeEvenements,'donnees'=>$donnees]);
        return "add Evenement";
    }

    public function validFormEdit(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        // var_dump($app['request']->attributes);
        if (isset($_POST['nom']) && isset($_POST['typeEvenement_id']) and isset($_POST['nom']) and isset($_POST['photo']) and isset($_POST['id'])) {
            $donnees = [
                'nom' => htmlspecialchars($_POST['nom']),                    // echaper les entrées
                'typeEvenement_id' => htmlspecialchars($app['request']->get('typeEvenement_id')),
                'prix' => htmlspecialchars($req->get('prix')),
                'photo' => $app->escape($req->get('photo')),
                'id' => $app->escape($req->get('id'))//$req->query->get('photo')
            ];
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom'])) $erreurs['nom']='nom composé de 2 lettres minimum';
            if(! is_numeric($donnees['typeEvenement_id']))$erreurs['typeEvenement_id']='veuillez saisir une valeur';
            if(! is_numeric($donnees['prix']))$erreurs['prix']='saisir un prix valide';
            if(! preg_match("/[A-Za-z0-9]{2,}.(jpeg|jpg|png)/",$donnees['photo'])) $erreurs['photo']='nom de fichier incorrect (extension jpeg , jpg ou png)';
            if(! is_numeric($donnees['id']))$erreurs['id']='saisir une valeur numérique';
            $contraintes = new Assert\Collection(
                [
                    'id' => [new Assert\NotBlank(),new Assert\Type('digit')],
                    'typeEvenement_id' => [new Assert\NotBlank(),new Assert\Type('digit')],
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
                $this->typeEvenementModel = new TypeEvenementModel($app);
                $typeEvenements = $this->typeEvenementModel->getAllTypeEvenements();
                return $app["twig"]->render('backOff/Evenement/edit.html.twig',['donnees'=>$donnees,'errors'=>$errors,'erreurs'=>$erreurs,'typeEvenements'=>$typeEvenements]);
            }
            else
            {
                $this->EvenementModel = new EvenementModel($app);
                $this->EvenementModel->updateEvenement($donnees);
                return $app->redirect($app["url_generator"]->generate("Evenement.index"));
            }

        }
        else
            return $app->abort(404, 'error Pb id form edit');

    }

    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\EvenementController::index')->bind('Evenement.index');
        $controllers->get('/show', 'App\Controller\EvenementController::show')->bind('Evenement.show');

        $controllers->get('/add', 'App\Controller\EvenementController::add')->bind('Evenement.add');
        $controllers->post('/add', 'App\Controller\EvenementController::validFormAdd')->bind('Evenement.validFormAdd');

        $controllers->get('/delete/{id}', 'App\Controller\EvenementController::delete')->bind('Evenement.delete')->assert('id', '\d+');;
        $controllers->delete('/delete', 'App\Controller\EvenementController::validFormDelete')->bind('Evenement.validFormDelete');

        $controllers->get('/edit/{id}', 'App\Controller\EvenementController::edit')->bind('Evenement.edit')->assert('id', '\d+');;
        $controllers->put('/edit', 'App\Controller\EvenementController::validFormEdit')->bind('Evenement.validFormEdit');


        return $controllers;
    }



}
