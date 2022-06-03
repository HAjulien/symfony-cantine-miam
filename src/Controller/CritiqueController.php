<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Critique;
use App\Repository\CritiqueRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/critique', name: 'critique_')]
class CritiqueController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CritiqueRepository $critiqueRepository, Request $request): Response
    {

        $page = (int)$request->query->get("page", 1);

        $critique = $critiqueRepository->getPaginatedCritique($page);

        $total = $critiqueRepository->getTotalCritique();

        return $this->render('critique/index.html.twig', [
            'critiques' => $critique,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des avis'
        ]);
    }

    #[Route('/filtreUser/{id}', name: 'filtreUser')]
    public function filtreUserID( CritiqueRepository $critiqueRepository, User $user,  Request $request): Response
    {

        $filtrer =$user;
        $tableJoint = 'c.utilisateur';

        $page = (int)$request->query->get("page", 1);

        $critique = $critiqueRepository->getPaginatedCritiqueFiltre($page, $filtrer, $tableJoint);

        $total = $critiqueRepository->getTotalCritiqueFiltre($filtrer, $tableJoint);

        return $this->render('critique/all_user_critique.html.twig', [
            'critiques' => $critique,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des avis de : ' . $user->getPseudo(),
        ]);
    }
    
    #[Route('/filtreProduit/{id}', name: 'filtreProduit')]
    public function filtreProduitID( CritiqueRepository $critiqueRepository, Produit $produit,  Request $request): Response
    {

        $filtrer =$produit;
        $tableJoint = 'c.produit';

        $page = (int)$request->query->get("page", 1);

        $critique = $critiqueRepository->getPaginatedCritiqueFiltre($page, $filtrer, $tableJoint);

        $total = $critiqueRepository->getTotalCritiqueFiltre($filtrer, $tableJoint);

        return $this->render('critique/plat_all_critiques.html.twig', [
            'critiques' => $critique,
            'total' => $total,
            'page' => $page,
            'titre' => 'Avis pour ' . $produit->getNom()
        ]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function delete(Critique $critique, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($critique);
        $em->flush();
        $this->addFlash('success', 'Avis supprimé !');
        return $this->redirectToRoute('critique_index');
    }
}
