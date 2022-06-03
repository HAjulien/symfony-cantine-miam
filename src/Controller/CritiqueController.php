<?php

namespace App\Controller;

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
