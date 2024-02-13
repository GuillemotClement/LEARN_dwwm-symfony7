<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/client/delete/{id}', name: 'app_client_delete')]
    public function delete(EntityManagerInterface $em, $id){
        $client = $em->getRepository(Client::class)->find($id);
        $em->remove($client);
        $em->flush();
        return $this->redirectToRoute("app_client");
    }
    //création d'une route pour la fonction
    //chemin qui correspond à l'élément à modifier
    #[Route('/client/edit/{id}', name:"app_client_edit", methods:["POST", "GET"])]
    public function edit(EntityManagerInterface $em, ClientRepository $cr, $id, Request $request){
        $id = (int)$id;
        if($id){
            $client = $cr->find($id);
        }else{
            $client = new Client();
        }
        //création du form à partir de ClientType sur l'entity Client = $client
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($client); //fournit les données $client 
            $em->flush(); //enregistrer les données saisie dans la bdd
            return $this->redirectToRoute("app_client");
        }
        return $this->render("client/form.html.twig",[
            'form'=>$form->createView(),
        ]);
    }
}
