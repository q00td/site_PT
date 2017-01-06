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
        $this->EvenementModel = new EvenementModel($app);
        $Evenements = $this->EvenementModel->getAllEvenements();
        return $app["twig"]->render('backOff/Evenement/v_form_create_evenement.html.twig',['typeEvenements'=>$Evenements]);
    }



    public function validFormAdd(Application $app) {
//        if (isset($_POST['nom']) && isset($_POST['id_categorie']) and isset($_POST['nom']) and isset($_POST['description']) and isset($_POST['date_evenement'])) {
//            $donnees = [
//                'nom' => htmlspecialchars($_POST['nom']),
//                'id_categorie' => htmlspecialchars($_POST['id_categorie']),
//                'lieu_evenement' => htmlspecialchars($_POST['lieu_evenement']),
//                'description' => htmlspecialchars($_POST['description']),
//                'date_evenement' => htmlspecialchars($_POST['date_evenement'])
//            ];
//            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom']))) $erreurs['nom']='nom composé de 2 lettres minimum';
//            if(! is_numeric($donnees['id_categorie']))$erreurs['id_categorie']='veuillez saisir une valeur';
//            if(! is_numeric($donnees['lieu_evenement']))$erreurs['lieu_evenement']='saisir une valeur numérique';
//            if(! is_numeric($donnees['description']))$erreurs['description']='saisir une valeur numérique';
//            if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_evenement']))) $erreurs['date_evenement']='entrer une date valide format aaaa-mm-jj';
//
//            if(! empty($erreurs))
//            {
//                $this->EvenementModel = new EvenementModel($app);
//                $typeEvenements = $this->EvenementModel->getAllTypeEvenements();
//                return $app["twig"]->render('Evenement/v_form_create_Evenement.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'typeEvenements'=>$typeEvenements]);
//            }
//            else
//            {
//                $this->EvenementModel = new EvenementModel($app);
//                $this->EvenementModel->insertEvenement($donnees);
//                return $app->redirect($app["url_generator"]->generate("Evenement.index"));
//            }
//
//        }
//        else
//            return "error ????? PB data form";


        if (isset($_POST['lieu_evenement']) && isset($_POST['description']) and isset($_POST['date_evenement']) ) {
            $donnees = [
                'lieu_evenement' => htmlspecialchars($_POST['lieu_evenement']),
                'description' => htmlspecialchars($_POST['description']),
                'date_evenement' => htmlspecialchars($_POST['date_evenement'])
            ];
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['lieu_evenement']))) $erreurs['lieu_evenement']='nom composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['description']))) $erreurs['description']='nom composé de 2 lettres minimum';
            if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_evenement']))) $erreurs['date_evenement']='entrer une date valide format aaaa-mm-jj';
            if(! empty($erreurs))
            {
                $this->EvenementModel = new EvenementModel($app);
                $Evenements = $this->EvenementModel->getAllEvenements();
                return $app["twig"]->render('backOff/Evenement/v_form_create_evenement.html.twig',['donnees'=>$donnees, 'erreurs'=>$erreurs,'Evenement'=>$Evenements]);
            }
            else{
                $this->EvenementModel = new EvenementModel($app);
                $this->EvenementModel->insertEvenement($donnees);
                var_dump($donnees);
                return $app->redirect($app["url_generator"]->generate("Evenement.show"));
            }

        }else
            return "error ????? PB data form";


    }

    public function delete(Application $app, $id) {
        $id = htmlentities($id);
        return $app["twig"]->render('backOff/Evenement/v_form_delete_evenement.html.twig',['id'=>$id]);
    }
    public function validFormDelete(Application $app) {
        $id = htmlentities($_POST["id"]);

        $this->EvenementModel = new EvenementModel($app);
        $this->EvenementModel->deleteEvenement($id);
        return $app->redirect($app["url_generator"]->generate("Evenement.show"));
    }

    public function edit(Application $app, $id) {
        $id = htmlentities($id);
        $this->EvenementModel = new EvenementModel($app);
        $donnees = $this->EvenementModel->readEvenement($id);
//        var_dump($donnees);
        return $app["twig"]->render('backOff/Evenement/v_form_update_evenement.html.twig',['evenement'=>$donnees]);
    }
    public function validFormEdit(Application $app) {
        $donnees['id_evenement'] = htmlentities($_POST['id_evenement']);
        $donnees['lieu_evenement'] = htmlentities($_POST['lieu_evenement']);
        $donnees['description'] = htmlentities($_POST['description']);
        $donnees['date_evenement'] = htmlentities($_POST['date_evenement']);
        $this->EvenementModel = new EvenementModel($app);


//        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['lieu_evenement']))) $erreurs['lieu_evenement']='nom composé de 2 lettres minimum';
//        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['description']))) $erreurs['description']='nom composé de 2 lettres minimum';
//        if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_evenement']))) $erreurs['date_evenement']='entrer une date valide format aaaa-mm-jj';


        //var_dump($erreurs);

        if(! empty($erreurs)) {
            $typeEvenements=$this->EvenementModel->getAllTypeEvenements();

            return $app["twig"]->render('backOff/Evenement/v_form_update_evenement.html.twig',['Evenement'=>$donnees,'erreurs'=>$erreurs,
                'typeEvenements' => $typeEvenements]);
        }
        else
        {
            $this->EvenementModel = new EvenementModel($app);
            $this->EvenementModel->editEvenement($donnees);
            return $app->redirect($app["url_generator"]->generate("Evenement.show"));
        }

    }

    public function search(Application $app) {
        $this->EvenementModel = new EvenementModel($app);
        $Evenements = $this->EvenementModel->searchEvenements();
        var_dump($Evenements);
        return $app["twig"]->render('backOff/Evenement/homepage.html.twig',['data'=>$Evenements]);
    }


    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\EvenementController::index')->bind('Evenement.index');
        $controllers->get('/show', 'App\Controller\EvenementController::show')->bind('Evenement.show');

        $controllers->get('/show', 'App\Controller\EvenementController::search')->bind('Evenement.search');

        $controllers->get('/add', 'App\Controller\EvenementController::add')->bind('Evenement.add');
        $controllers->post('/add', 'App\Controller\EvenementController::validFormAdd')->bind('Evenement.validFormAdd');

        $controllers->get('/delete/{id}', 'App\Controller\EvenementController::delete')->bind('Evenement.delete')->assert('id', '\d+');;
        $controllers->delete('/delete', 'App\Controller\EvenementController::validFormDelete')->bind('Evenement.validFormDelete');

        $controllers->get('/edit/{id}', 'App\Controller\EvenementController::edit')->bind('Evenement.edit')->assert('id', '\d+');;
        $controllers->post('/edit', 'App\Controller\EvenementController::validFormEdit')->bind('Evenement.validFormEdit');


        return $controllers;
    }



}
