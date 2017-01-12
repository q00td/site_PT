<?php
namespace App\Controller;

use App\Model\CommandeModel;
use App\Model\ObjetModel;

use App\Model\EmploiModel;
use App\Model\EvenementModel;

use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\ProduitModel;


use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class ProduitController implements ControllerProviderInterface
{

    public function __construct()
    {
    }

    public function index(Application $app) {
        return $this->show($app);
    }
    public function test(Application $app){
        return $app["twig"]->render('backOff/Covoit/trajet.html.twig');
    }

    public function show(Application $app) {
        $this->ObjetModel = new ObjetModel($app);
        $Objets = $this->ObjetModel->getLast();
      $this->EvenementModel = new EvenementModel($app);
        $Event = $this->EvenementModel->getLast();
        $this->EmploiModel = new EmploiModel($app);
        $Emploi = $this->EmploiModel->getLast();

        return $app["twig"]->render('backOff/Accueil/homepage.html.twig',['objet'=>$Objets,'event'=>$Event,'emploi'=>$Emploi]);
    }



    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\produitController::index')->bind('produit.index');
        $controllers->get('/show', 'App\Controller\produitController::show')->bind('produit.show');
        $controllers->get('/test', 'App\Controller\produitController::test')->bind('produit.test');



        return $controllers;
    }


}
