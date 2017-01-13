<?php
/**
 * Created by PhpStorm.
 * User: minh
 * Date: 06/01/17
 * Time: 20:35
 */

namespace App\Controller;
use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\EmploiModel;


use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class EmploiController implements ControllerProviderInterface
{
    public function __construct()
    {
    }

    public function index(Application $app) {
        return $this->show($app);
    }

    public function show(Application $app) {
        $this->EmploiModel = new EmploiModel($app);
        $Emplois = $this->EmploiModel->getAllEmplois();
        return $app["twig"]->render('backOff/Emploi/Emploi.html.twig',['data'=>$Emplois]);
    }
    public function getUser(Application $app,$id) {
        $this->EmploiModel = new EmploiModel($app);
        $Objets = $this->EmploiModel->getUser($id);
        return $app["twig"]->render('backOff/Emploi/user.html.twig',['user'=>$Objets]);
    }


    public function add(Application $app) {
        $this->EmploiModel = new EmploiModel($app);
        $Emplois = $this->EmploiModel->getAllEmplois();
        return $app["twig"]->render('backOff/Emploi/v_form_create_emploi.html.twig',['typeEmplois'=>$Emplois]);
    }



    public function validFormAdd(Application $app) {


        if (isset($_POST['poste_emploi']) && isset($_POST['date_debut_emploi']) and isset($_POST['date_fin_emploi'])  and isset($_POST['description_emploi']) and isset($_POST['type_emploi'])) {
            $donnees = [
                'poste_emploi' => htmlspecialchars($_POST['poste_emploi']),
                'date_debut_emploi' => htmlspecialchars($_POST['date_debut_emploi']),
                'date_fin_emploi' => htmlspecialchars($_POST['date_fin_emploi']),
                'description_emploi' => htmlspecialchars($_POST['description_emploi']),
                'type_emploi' => htmlspecialchars($_POST['type_emploi'])
            ];
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['poste_emploi']))) $erreurs['poste_emploi']='nom composé de 2 lettres minimum';
            if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_debut_emploi']))) $erreurs['date_debut_emploi']='entrer une date valide format aaaa-mm-jj';
            if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_fin_emploi']))) $erreurs['date_fin_emploi']='entrer une date valide format aaaa-mm-jj';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['description_emploi']))) $erreurs['description_emploi']='nom composé de 2 lettres minimum';
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['type_emploi']))) $erreurs['type_emploi']='nom composé de 2 lettres minimum';
            if(! empty($erreurs))
            {
                $this->EmploiModel = new EmploiModel($app);
                $Emplois = $this->EmploiModel->getAllEmplois();
                return $app["twig"]->render('backOff/Emploi/v_form_create_emploi.html.twig',['donnees'=>$donnees, 'erreurs'=>$erreurs,'Emploi'=>$Emplois]);
            }
            else{
                $this->EmploiModel = new EmploiModel($app);
                $this->EmploiModel->insertEmploi($donnees);
                var_dump($donnees);
                return $app->redirect($app["url_generator"]->generate("Emploi.show"));
            }

        }else
            return "error ????? PB data form";




    }

    public function delete(Application $app, $id) {
        $id = htmlentities($id);
        return $app["twig"]->render('backOff/Emploi/v_form_delete_emploi.html.twig',['id'=>$id]);
    }
    public function validFormDelete(Application $app) {
        $id = htmlentities($_POST["id"]);

        $this->EmploiModel = new EmploiModel($app);
        $this->EmploiModel->deleteEmploi($id);
        return $app->redirect($app["url_generator"]->generate("Emploi.show"));
    }

    public function edit(Application $app, $id) {
        $id = htmlentities($id);
        $this->EmploiModel = new EmploiModel($app);
        $donnees = $this->EmploiModel->readEmploi($id);
//        var_dump($donnees);
        return $app["twig"]->render('backOff/Emploi/v_form_update_emploi.html.twig',['Emploi'=>$donnees]);
    }
    public function validFormEdit(Application $app) {
        $donnees['id_emploi'] = htmlentities($_POST['id_emploi']);
        $donnees['poste_emploi'] = htmlentities($_POST['poste_emploi']);
        $donnees['date_debut_emploi'] = htmlentities($_POST['date_debut_emploi']);
        $donnees['date_fin_emploi'] = htmlentities($_POST['date_fin_emploi']);
        $donnees['description_emploi'] = htmlentities($_POST['description_emploi']);
        $donnees['type_emploi'] = htmlentities($_POST['type_emploi']);
        $this->EmploiModel = new EmploiModel($app);


        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['poste_emploi']))) $erreurs['poste_emploi']='nom composé de 2 lettres minimum';
        if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_debut_emploi']))) $erreurs['date_debut_emploi']='entrer une date valide format aaaa-mm-jj';
        if ((! preg_match("/(\d{4})-(\d{2})-(\d{2})/",$donnees['date_fin_emploi']))) $erreurs['date_fin_emploi']='entrer une date valide format aaaa-mm-jj';
        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['description_emploi']))) $erreurs['description_emploi']='nom composé de 2 lettres minimum';
        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['type_emploi']))) $erreurs['type_emploi']='nom composé de 2 lettres minimum';


        if(! empty($erreurs)) {
            $Emplois=$this->EmploiModel->getAllEmplois();

            return $app["twig"]->render('backOff/Emploi/v_form_update_emploi.html.twig',['Emploi'=>$donnees,'erreurs'=>$erreurs,
                'typeEmplois' => $Emplois]);
        }
        else
        {
            $this->EmploiModel = new EmploiModel($app);
            $this->EmploiModel->editEmploi($donnees);
            return $app->redirect($app["url_generator"]->generate("Emploi.show"));
        }

    }

    public function search(Application $app) {
        $user=$_POST['text'];
        $this->EmploiModel = new EmploiModel($app);
        $Emplois = $this->EmploiModel->searchEmploi($user);
        //var_dump($Emplois);
        return $app["twig"]->render('backOff/Emploi/Emploi.html.twig',['data'=>$Emplois]);
    }


    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\EmploiController::index')->bind('Emploi.index');
        $controllers->get('/show', 'App\Controller\EmploiController::show')->bind('Emploi.show');



        $controllers->get('/add', 'App\Controller\EmploiController::add')->bind('Emploi.add');
        $controllers->post('/add', 'App\Controller\EmploiController::validFormAdd')->bind('Emploi.validFormAdd');

        $controllers->get('/getUser/{id}', 'App\Controller\EmploiController::getUser')->bind('Emploi.getUser')->assert('id', '\d+');;



        $controllers->get('/delete/{id}', 'App\Controller\EmploiController::delete')->bind('Emploi.delete')->assert('id', '\d+');;
        $controllers->delete('/delete', 'App\Controller\EmploiController::validFormDelete')->bind('Emploi.validFormDelete');

        $controllers->get('/edit/{id}', 'App\Controller\EmploiController::edit')->bind('Emploi.edit')->assert('id', '\d+');;
        $controllers->post('/edit', 'App\Controller\EmploiController::validFormEdit')->bind('Emploi.validFormEdit');

        $controllers->get('/search', 'App\Controller\EmploiController::search')->bind('Emploi.search');
        $controllers->post('/search', 'App\Controller\EmploiController::search')->bind('Emploi.search');

        return $controllers;
    }

}