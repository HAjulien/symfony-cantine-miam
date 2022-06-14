<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/commande', name: 'commande_')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CommandeRepository $commandeRepository, Request $request): Response
    {

        $page = (int)$request->query->get("page", 1);

        $commande = $commandeRepository->getPaginatedCommande($page);
        $total = $commandeRepository->getTotalCommande();


        return $this->render('commande/index.html.twig', [
            'titre' => 'liste des commandes',
            'commandes' => $commande,
            'total' => $total,
            'page' => $page,
        ]);
    }

    #[Route('/filtreUser/{id}', name: 'filtreUser')]
    public function filtreUserID( CommandeRepository $commandeRepository, User $user,  Request $request): Response
    {
        $filtrer =$user;

        $page = (int)$request->query->get("page", 1);

        $commande = $commandeRepository->getPaginatedCommandeFiltre($page, $filtrer);

        $total = $commandeRepository->getTotalCommandeFiltre($filtrer);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commande,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des commandes de : ' . $user->getPseudo(),
            'Nafpa' => $user->getIdentifiantAfpa(),
        ]);
    }
}
