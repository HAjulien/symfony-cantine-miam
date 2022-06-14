<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\SelectionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProduitRepository $ProduitRepository, UserRepository $userRepository, CommandeRepository $commandeRepository, SelectionRepository $selectionRepository): Response
    {
        $today = date("m.d.y");
        $today2 = date("y-m-d");
        $date=date('w');
        $commande = $commandeRepository->getCommandeRecente();
        $marge = $selectionRepository->getMarge($today2);
        $produit = $ProduitRepository->getWorstProduitNote();
        $menu = $ProduitRepository->getMenuJour($date);
        $user = $userRepository->getTotalUsers();
        //dd( $produit[0]);
        //dump($marge);
        //dd($today2);

        return $this->render('home/index.html.twig', [
            'marge' => $marge,
            'today' => $today,
            'today2' => $today2,
            'menus' => $menu,
            'produit' => $produit[0],
            'commandes' => $commande,
            'user' => $user,
        ]);
    }
}
