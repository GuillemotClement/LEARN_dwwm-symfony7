<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Service\MyFct;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{

    #[Route('/menu/delete/{id}', name: 'app_menu_delete')]
    public function delete(EntityManagerInterface $em, $id)
    {
        $menu = $em->getRepository(Menu::class)->find($id);
        $libelle = $menu->getLibelle();
        $enfants = $menu->getMenus();
        $nbreEnfant = count($enfants);
        $ok = 1; //true
        if ($nbreEnfant) { //if $nbreEnfant != 0
            $message = "Impossible de supprimer $libelle car il contient des sous-menu";
            $ok = 0;
        } else {
            $em->remove($menu); //suppression de l'objet menu
            $em->flush(); //suppression dans la table menu
            $message = "Le menu $libelle est bien supprimée dans la base de données";
            $ok = 1;
        }
        $responses = [
            'ok' => $ok,
            'message' => $message,
        ];
        $responses_json = json_encode($responses);
        echo $responses_json;
        exit;
    }

    #[Route('/menu/show/{id}', name: 'app_menu_show')]
    public function show($id, EntityManagerInterface $em, Request $request)
    {
        $menu = $em->getRepository(Menu::class)->find($id);
        return $this->render('menu/show.html.twig', [
            'menu' => $menu,
        ]);
    }


    #[Route('/menu/edit/{id}', name: 'app_menu_edit')]
    public function edit($id, EntityManagerInterface $em, Request $request)
    {
        $id = (int)$id;
        if ($id == 0) {
            $menu = new Menu();
        } else {
            $menu = $em->getRepository(Menu::class)->find($id); //->findOneBy(['id'=>$id])
        }
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute('app_menu');
        }
        return $this->render('menu/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/menu', name: 'app_menu')]
    public function index(EntityManagerInterface $em): Response
    {

        $menus = $em->getRepository(Menu::class)->findBy([], ['parent' => 'asc']);
        $menu = $this->list_menu(null, 0, $menus);

        return $this->render('menu/index.html.twig', [
            'menu' => $menu,
            'nbre' => count($menus),
        ]);
    }
    public function list_menu($parent, $niveau, $menus)
    {
        $html = "";
        foreach ($menus as $menu) {
            $id = $menu->getId();
            $rang = $menu->getRang();
            $libelle = $menu->getLibelle();
            $url = $menu->getUrl();
            $role = $menu->getRole();
            $icone = $menu->getIcone();
            $parentMenu = $menu->getParent();
            $icone = "<i class='$icone'></i>";
            if ($parent == $parentMenu) {
                $point = "";
                for ($i = 1; $i <= $niveau; $i++) {
                    $point .= "......";
                }
                $class = ($niveau == 0) ? "fw-bold fs-4" : "";
                $html .= "<tr>";
                $html .= "<td><input class='center' type='checkbox' id='$id' name='check' value='$id' onClick='onlyOne(this)'></td>";
                $html .= "<td class='$class'>$point $libelle</td><td>$url</td><td>$icone</td><td>$role</td>";
                $html .= "</tr>";
                $html .= $this->list_menu($menu, $niveau + 1, $menus);
            }
        }
        return $html;
    }
}
