<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/ligne_commande/search', name: "app_commande_search_code", methods:['POST'])]
    public function searchCode(Request $request)
    {
        $numArticle = $request->get('numArticle'); //$numArticle = $_POST['numArticle']
        echo "Que souhaitez vous faire de $numArticle ?";
        exit;
    }

    #[Route('/ligne_commande/{id}', name: 'app_commande_content')]
    public function ligneCommande($id, EntityManagerInterface $em)
    {
        $commande = $em->getRepository(Commande::class)->find($id);
        $ligneCommandes = $commande->getLigneCommandes();
        $rows = [];
        $total = 0;
        foreach($ligneCommandes as $ligne){
            $article = $ligne->getArticle();
            $quantite = $ligne->getQuantite();
            $prixUnitaire = $ligne->getPrixUnitaire();
            $montant = $quantite * $prixUnitaire;
            $total += $montant;
            $rows[] = [
                'id' => $ligne->getId(),
                'numArticle' => $article->getNumArticle(),
                'designation' => $article->getDesignation(),
                'prixUnitaire' => number_format($prixUnitaire, 2, '.', ' '),
                'quantite' => number_format($quantite, 2, '.', ' '),
                'montant' => number_format($montant, 2, '.', ' '),
            ];
        }
        return $this->render('commande/ligne_Commande.html.twig', [
            'commande'=>$commande,
            'rows'=>$rows,
            'total'=> number_format($total, 2, '.', ' '),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
