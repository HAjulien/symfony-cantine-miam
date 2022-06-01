<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Category;
use App\Form\ProduitType;
use App\Form\SearchProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/produit', name: 'produit_')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $limit= 5;
        $page = (int)$request->query->get("page", 1);

        $produit = $ProduitRepository->getPaginatedProduit($page, $limit);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }


    #[Route('/semaine', name: 'semaine')]
    public function produitsSemaine(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request ): Response
    {
        $semaine = 'semaine';
        $limit= 5;
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsSemaine($page, $limit);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduitsSemaine();

        return $this->render('produit/index.html.twig', [
            'semaine' => $semaine,
            'categories' => $category,
            'produits' => $produit ,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'titre' => 'Liste des Produits de la semaine'
        ]);
    }

    #[Route('/recherche', name: 'recherche')]
    public function recherche( ProduitRepository $ProduitRepository, Request $request ): Response
    {
        $produit = $ProduitRepository;

        $rechercheForm = $this->createForm(SearchProduitType::class);

        $search = $rechercheForm->handleRequest($request);

        if($rechercheForm->isSubmitted() && $rechercheForm->isValid()){
            //on recherche les articles correspondant aux mots clés
            $produit = $ProduitRepository->search(
                $search->get('mots')->getData(),
                $search->get('category')->getData()
            );
        }

        return $this->render('produit/recherche.html.twig', [
            'produits' => $produit,
            'rechercheForm' => $rechercheForm->createView(),
            'titre' => 'Faire une recherche d\'un produit'
        ]);
    }

    #[Route("/add", name:"add")]
    public function addProduit(Request $request, ManagerRegistry $doctrine): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($produit);
            $em->flush();
            $this->addFlash('success', 'Produit ajouté !');
            return $this->redirectToRoute('produit_index');
        }
        
        return $this->render('produit/add.html.twig', [
            'title' => 'Ajout d\'un produit',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/fitre/{id}', name: 'filtre')]
    public function filtre( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $limit= 2;
        $categorieFiltrer =$category;
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltre($page, $limit, $categorieFiltrer);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'titre' => 'Les produits filtré'
        ]);
    }

    #[Route("/update/{id}", name:"update")]
    public function updateProduit(Produit $produit, Request $request, ManagerRegistry $doctrine): Response
    {

        $form = $this->createForm(ProduitType::class, $produit );


        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            $this->addFlash('success', 'Produit modifié !');
            return $this->redirectToRoute('produit_index');
        }
        
        
        return $this->render('produit/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification d\'un Produit',

        ]);
    }

    #[Route("/apercu/{id}", name: "apercu")]
    public function apercu(Produit $produit): Response
    {
        return $this->render('produit/apercu.html.twig', [
            'produit' => $produit,
            'title' => 'fiche produit'

        ]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function delete(Produit $produit, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($produit);
        $em->flush();
        $this->addFlash('success', 'Produit supprimé !');
        return $this->redirectToRoute('produit_index');
    }

    #[Route("/activate/{id}", name: "activate")]
    public function active(Produit $produit, ManagerRegistry $doctrine): Response
    {

        $produit->setSelectionner(($produit->isSelectionner()) ? false : true);
        $em = $doctrine->getManager();
        $em->flush();
        return New Response("true");

    }
}
