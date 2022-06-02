<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Form\FeatureType;
use App\Form\SearchUserType;
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
            'titre' => 'Liste des Articles de la Page Accueil'
        ]);
    }

    #[Route('/recherche', name: 'recherche')]
    public function recherche( FeatureRepository $featureRepository, Request $request ): Response
    {
        $feature = array();

        $rechercheForm = $this->createForm(SearchUserType::class);

        $search = $rechercheForm->handleRequest($request);

        if($rechercheForm->isSubmitted() && $rechercheForm->isValid()){
            //on recherche les articles correspondant aux mots clés
            $feature = $featureRepository->search($search->get('mots')->getData());
        }

        return $this->render('feature/recherche.html.twig', [
            'features' => $feature,
            'rechercheForm' => $rechercheForm->createView(),
            'titre' => 'Faire une recherche d\'article'
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
            $this->addFlash('success', 'Article modifié !');
            return $this->redirectToRoute('feature_index');
        }
        
        
        return $this->render('feature/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification d\'un article',

        ]);
    }

    #[Route("/apercu/{id}", name: "apercu")]
    public function apercu(Feature $feature): Response
    {
        return $this->render('feature/apercu.html.twig', [
            'feature' => $feature,
            'title' => 'aperçu d\' article',

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
