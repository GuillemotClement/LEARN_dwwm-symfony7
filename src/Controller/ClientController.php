<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/search', name: 'app_client_search')]
    public function chercher(Request $request, EntityManagerInterface $em)
    {
        $mot = $request->get('mot');
        $clients = $em->getRepository(Client::class)->chercherMot($mot);
        $rows = [];
        foreach ($clients as $client) {
            $rows[] = [
                'id' => $client->getId(),
                'numClient' => $client->getNumClient(),
                'nomClient' => $client->getNomClient(),
                'adresseClient' => $client->getAdresseClient(),
            ];
        }
        $response = [
            'rows' => $rows,
            'nbre' => count($clients),
        ];
        echo json_encode($response);
        exit;
    }






    #[Route('/', name: 'app_client')]
    //ClientRepository est la classe qui est importé
    public function index(ClientRepository $cr): Response
    {
        //permet l'affichage des clients
        $clients = $cr->findAll();

        //chercher les clients
        return $this->render('/client/index.html.twig', [
            'clients' => $clients,
            'nbre' => count($clients),
        ]);
    }

    #[Route('/export/excel', name: 'app_client_export_excel')]
    public function exportExcel(EntityManagerInterface $em): Response
    {
        $file = "../public/modele-document/modele-fichier-client.xlsx";
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $clients = $em->getRepository(Client::class)->findAll();
        $row = 4;
        foreach ($clients as $client) {
            $sheet->insertNewRowBefore($row);
            $sheet->setCellValue("A$row", $client->getNumClient());
            $sheet->setCellValue("B$row", $client->getNomClient());
            $sheet->setCellValue("C$row", $client->getAdresseClient());
            $row++;
        }

        $nbre = count($clients);
        $sheet->setCellValue("A$row", "Nombre de clients : $nbre");
        $target = "../public/share/list-clients.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save($target);
        echo "Exportation réaliser";
        exit;
        //return $this->redirectToRoute('app_client');
    }

    #[Route('/show/{id}', name: "app_client_show")]
    public function show(ClientRepository $cr, $id)
    {
        $client = $cr->find($id);
        return $this->render("client/show.html.twig", [
            'client' => $client,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_client_delete')]
    public function delete(EntityManagerInterface $em, $id)
    {
        $client = $em->getRepository(Client::class)->find($id);
        $em->remove($client);
        $em->flush();
        return $this->redirectToRoute("app_client");
    }
    //création d'une route pour la fonction
    //chemin qui correspond à l'élément à modifier
    #[Route('/edit/{id}', name: "app_client_edit", methods: ["POST", "GET"])]
    public function edit(EntityManagerInterface $em, ClientRepository $cr, $id, Request $request)
    {
        $id = (int)$id;
        if ($id) {
            $client = $cr->find($id);
        } else {
            $client = new Client();
        }
        //création du form à partir de ClientType sur l'entity Client = $client
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client); //fournit les données $client 
            $em->flush(); //enregistrer les données saisie dans la bdd
            return $this->redirectToRoute("app_client");
        }
        return $this->render("client/form.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
