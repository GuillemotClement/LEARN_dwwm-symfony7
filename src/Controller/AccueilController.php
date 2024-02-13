<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    //si on laisse juste la racine, c'est cette page qui va être afficher à l'accès du site
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        //render -> generatePage(). Entre parenthèse le template
        //entre crochet les variables que l'on veux envoyer
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'nom' => "Projet E-Commerce",
        ]);
    }
}
