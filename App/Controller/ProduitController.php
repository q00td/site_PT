<?php
namespace App\Controller;

use App\Model\CommandeModel;
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

    public function show(Application $app) {

        return $app["twig"]->render('backOff/Accueil/homepage.html.twig');
    }



    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\produitController::index')->bind('produit.index');
        $controllers->get('/show', 'App\Controller\produitController::show')->bind('produit.show');


        return $controllers;
    }


}
