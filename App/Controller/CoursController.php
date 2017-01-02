<?php
namespace App\Controller;
use App\Model\CommandeModel;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;   // pour utiliser request
use App\Model\CoursModel;
use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;
class CoursController implements ControllerProviderInterface
{
    public function __construct()
    {
    }
    public function index(Application $app) {
        return $this->show($app);
    }
    public function show(Application $app) {
        $this->CoursModel = new CoursModel($app);
        $Cours = $this->CoursModel->getAllCours();
        return $app["twig"]->render('backOff/Cours/Cours.html.twig',['data'=>$Cours]);
    }
    public function add(Application $app) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->CoursModel = new CoursModel($app);
        $Cours = $this->CoursModel->getAllCours();
        return $app["twig"]->render('backOff/Cours/add.html.twig',['Cours'=>$Cours,'path'=>BASE_URL]);
        return "add Cours";
    }
    public function validFormAdd(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        // var_dump($app['request']->attributes);
        if (isset($_POST['nom_cours']) && isset($_POST['description_cours']) and isset($app["session"]) and isset($_POST['id_matiere'])) {
            $donnees = [
                'nom_cours' => htmlspecialchars($_POST['nom_cours']),                    // echapper les entrées
                'description_cours' => htmlspecialchars($_POST['description_cours']),
                'id_matiere' => htmlspecialchars($_POST['id_matiere'])
            ];
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom_cours'])) $erreurs['nom_cours']='Doit être composé de 2 lettres minimum';
            if(! preg_match("/^[A-Za-z ]{10,}/",$donnees['description_cours'])) $erreurs['description_cours']='Doit être composé de 10 lettres minimum';
            if(! is_numeric($donnees['id_matiere']))$erreurs['id_matiere']='Veuillez saisir une matière'; //Liste déroulante
            if(! empty($erreurs))
            {
                $this->CoursModel = new CoursModel($app);
                $Cours = $this->CoursModel->getAllCours();
                return $app["twig"]->render('backOff/Cours/add.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'Cours'=>$Cours]);
            }
            else
            {
                $this->CoursModel = new CoursModel($app);
                $this->CoursModel->insertCours($donnees);
                return $app->redirect($app["url_generator"]->generate("Cours.index"));
            }
        }
        else
            return $app->abort(404, 'error Pb data form Add');
    }
    public function delete(Application $app, $id) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->CoursModel = new CoursModel($app);
        $Cours = $this->CoursModel->getAllCours();
        $this->CoursModel = new CoursModel($app);
        $donnees = $this->CoursModel->getCours($id);
        return $app["twig"]->render('backOff/Cours/delete.html.twig',['Cours'=>$Cours,'donnees'=>$donnees]);
        return "add Cours";
    }
    public function validFormDelete(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $id=$app->escape($req->get('id_cours'));
        if (is_numeric($id)) {
            $this->CoursModel = new CoursModel($app);
            $this->CoursModel->deleteCours($id);
            return $app->redirect($app["url_generator"]->generate("Cours.index"));
        }
        else
            return $app->abort(404, 'error Pb id form Delete');
    }
    public function edit(Application $app, $id) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->CoursModel = new CoursModel($app);
        $Cours = $this->CoursModel->getAllCours();
        $this->CoursModel = new CoursModel($app);
        $donnees = $this->CoursModel->getCours($id);
        return $app["twig"]->render('backOff/Cours/edit.html.twig',['Cours'=>$Cours,'donnees'=>$donnees]);
        return "add Cours";
    }
    public function validFormEdit(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        // var_dump($app['request']->attributes);
        if (isset($_POST['nom_cours']) && isset($_POST['description_cours']) and isset($app["session"]) and isset($_POST['id_matiere'])) {
            $donnees = [
                'nom_cours' => htmlspecialchars($_POST['nom_cours']),                    // echapper les entrées
                'description_cours' => htmlspecialchars($req->get('description_cours')),
                'id_matiere' => $app->escape($req->get('id_matiere'))
            ];
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom_cours'])) $erreurs['nom_cours']='Doit être composé de 2 lettres minimum';
            if(! preg_match("/^[A-Za-z ]{10,}/",$donnees['description_cours'])) $erreurs['description_cours']='Doit être composé de 10 lettres minimum';
            if(! is_numeric($donnees['id_matiere']))$erreurs['id_matiere']='Veuillez saisir une matière'; //Liste déroulante
            $contraintes = new Assert\Collection(
                [
                    'id_cours' => [new Assert\NotBlank(),new Assert\Type('digit')],
                    'nom_cours' => [
                        new Assert\NotBlank(['message'=>'Entrer le nom du cours']),
                        new Assert\Length(['min'=>2, 'minMessage'=>"Le nom doit faire au moins {{ limit }} caractères."])
                    ],
                    'id_cours' => [new Assert\NotBlank(),new Assert\Type('digit')],
                    'description_cours' => [
                        new Assert\NotBlank(['message'=>'Entrer la description du cours']),
                        new Assert\Length(['min'=>10, 'minMessage'=>"La description doit faire au moins {{ limit }} caractères."])
                    ],
                    'id_matiere' => new Assert\Type(array(
                        ''    => 'numeric',
                        'message' => 'La valeur {{ value }} n\'est pas valide.',
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
                $this->CoursModel = new CoursModel($app);
                $Cours = $this->CoursModel->getAllCours();
                return $app["twig"]->render('backOff/Cours/edit.html.twig',['donnees'=>$donnees,'errors'=>$errors,'erreurs'=>$erreurs,'Cours'=>$Cours]);
            }
            else
            {
                $this->CoursModel = new CoursModel($app);
                $this->CoursModel->updateCours($donnees);
                return $app->redirect($app["url_generator"]->generate("Cours.index"));
            }
        }
        else
            return $app->abort(404, 'error Pb id form edit');
    }
    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];
        $controllers->get('/', 'App\Controller\CoursController::index')->bind('Cours.index');
        $controllers->get('/show', 'App\Controller\CoursController::show')->bind('Cours.show');
        $controllers->get('/add', 'App\Controller\CoursController::add')->bind('Cours.add');
        $controllers->post('/add', 'App\Controller\CoursController::validFormAdd')->bind('Cours.validFormAdd');
        $controllers->get('/delete/{id}', 'App\Controller\CoursController::delete')->bind('Cours.delete')->assert('id', '\d+');;
        $controllers->delete('/delete', 'App\Controller\CoursController::validFormDelete')->bind('Cours.validFormDelete');
        $controllers->get('/edit/{id}', 'App\Controller\CoursController::edit')->bind('Cours.edit')->assert('id', '\d+');;
        $controllers->put('/edit', 'App\Controller\CoursController::validFormEdit')->bind('Cours.validFormEdit');
        return $controllers;
    }
}