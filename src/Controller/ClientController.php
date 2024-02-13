<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    //ClientRepository est la classe qui est importé
    public function index(ClientRepository $cr): Response
    {
        //permet l'affichage des clients
        $clients = $cr->findAll();
        
        //chercher les clients
        return $this->render('client/index.html.twig', [
            'clients'=>$clients,
            'nbre'=>count($clients),
        ]);
    }
}
