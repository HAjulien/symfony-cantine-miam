<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Category;
use App\Entity\Critique;
use App\Repository\CategoryRepository;
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
    public function index(CritiqueRepository $critiqueRepository,CategoryRepository $categoryRepository, Request $request): Response
    {

        $page = (int)$request->query->get("page", 1);

        $category = $categoryRepository->findAll();

        $critique = $critiqueRepository->getPaginatedCritique($page);

        $total = $critiqueRepository->getTotalCritique();

        return $this->render('critique/index.html.twig', [
            'categories' => $category,
            'critiques' => $critique,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des avis'
        ]);
    }


    #[Route('/filtreCategory/{id}', name: 'filtreCategory')]
    public function filtreCategory( CritiqueRepository $critiqueRepository,CategoryRepository $categoryRepository, Category $category,  Request $request): Response
    {
        $categorieFiltrer = $category;
        $category = $categoryRepository->findAll();
        $filtrer =$categorieFiltrer;
        $tableJoint = 'c.produit';

        $page = (int)$request->query->get("page", 1);

        $critique = $critiqueRepository->getPaginatedCritiqueFiltre($page, $filtrer, $tableJoint);

        $total = $critiqueRepository->getTotalCritiqueFiltre($filtrer, $tableJoint);

        return $this->render('critique/index.html.twig', [
            'categories' => $category,
            'critiques' => $critique,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des avis de : ' . $categorieFiltrer->getNom(),
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

        return $this->render('critique/produit_all_critiques.html.twig', [
            'critiques' => $critique,
            'total' => $total,
            'page' => $page,
            'titre' => 'Avis pour ' . $produit->getNom(),
            'image' =>$produit->getImage(),
            'imageAlt' =>$produit->getAltImage(),
        ]);
    }

    #[Route("/apercu/{id}", name:"apercu")]
    public function apercu(Critique $critique, ): Response
    {

        return $this->render('critique/apercu.html.twig', [
            'title' => 'aperçu critique',
            'critique' => $critique

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
