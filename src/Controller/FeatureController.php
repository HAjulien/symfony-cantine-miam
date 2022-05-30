<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Form\FeatureType;
use App\Repository\FeatureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('feature', name: 'feature_')]
class FeatureController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(FeatureRepository $featureRepository, Request $request): Response
    {
        $limit= 5;

        $page = (int)$request->query->get("page", 1);

        $feature = $featureRepository->getPaginatedfeatures($page, $limit);

        $total = $featureRepository->getTotalfeatures();

        return $this->render('feature/index.html.twig', [
            'features' => $feature,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'titre' => 'Liste des Articles'
        ]);
    }

    #[Route("/add", name:"add")]
    public function addArticle(Request $request, ManagerRegistry $doctrine): Response
    {
        $feature = new Feature();
        $form = $this->createForm(FeatureType::class, $feature);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager(); ancienne depreciée
            $em = $doctrine->getManager();
            //quand l'instance est vide on persist
            $em->persist($feature);
            //on insert avec flush
            $em->flush();
            $this->addFlash('success', 'Article ajouté !');
            return $this->redirectToRoute('feature_index');
        }
        
        
        return $this->render('feature/add.html.twig', [
            'title' => 'Ajout d\'un article',
            'form' => $form->createView(),
        ]);
    }
    
    #[Route("/update/{id}", name:"update")]
    public function updateUser(Feature $feature, Request $request, ManagerRegistry $doctrine): Response
    {

        $form = $this->createForm(FeatureType::class, $feature );


        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($feature);
            $em->flush();
            $this->addFlash('success', 'Utilisateur modifié !');
            return $this->redirectToRoute('feature_index');
        }
        
        
        return $this->render('feature/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification d\'un article',

        ]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function delete(Feature $feature, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($feature);
        $em->flush();
        $this->addFlash('success', 'Article supprimé !');
        return $this->redirectToRoute('feature_index');
    }
}
