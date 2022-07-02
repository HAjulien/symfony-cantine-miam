<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\CritiqueRepository;
use App\Repository\SelectionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProduitRepository $ProduitRepository, UserRepository $userRepository, CommandeRepository $commandeRepository, SelectionRepository $selectionRepository, CritiqueRepository $critiqueRepository): Response
    {
        
        $today = date("d/m/y");
        $today2 = date("y-m-d");
        $mois = date("y-m");
        $date=date('w');
        $commandes = $commandeRepository->getCommandeRecente();
        $marge = $selectionRepository->getMargeJour($today2);
        $margeMois = $selectionRepository->getMargeMois($mois);
        $produit = $ProduitRepository->getWorstProduitNote();
        $menu = $ProduitRepository->getMenuJour($date);
        $user = $userRepository->getTotalUsers();
        $userPersonnel = $userRepository->getTotalUsersPersonnel();
        $maxCritique = $critiqueRepository->getMaxCritique();
        //dd($maxCritique);
        //dd( $produit[0]);
        //dump($marge);
        //dd($margeMois);

        return $this->render('home/index.html.twig', [
            'maxCritique' => $maxCritique[0],
            'marge' => $marge,
            'margeMois' => $margeMois,
            'today' => $today,
            'today2' => $today2,
            'menus' => $menu,
            'produit' => $produit[0],
            'commandes' => $commandes,
            'user' => $user,
            'userPersonnel' => $userPersonnel,
        ]);
    }
}
