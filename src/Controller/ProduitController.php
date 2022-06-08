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
        $page = (int)$request->query->get("page", 1);

        $produit = $ProduitRepository->getPaginatedProduit($page);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }
    #[Route('/prixMax', name: 'prixMax')]
    public function PrixMax(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $page = (int)$request->query->get("page", 1);
        $value = 'p.prixAchat';
        $produit = $ProduitRepository->getPaginatedMax($page, $value);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }

    #[Route('/prixMin', name: 'prixMin')]
    public function PrixMin(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $page = (int)$request->query->get("page", 1);
        $value = 'p.prixAchat';
        $produit = $ProduitRepository->getPaginatedMin($page, $value);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }

    #[Route('/noteMin', name: 'noteMin')]
    public function noteMin( ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $order = 'avg(c.note)';
        $by = 'ASC';
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsNoteClasser($page, $order, $by);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }

    #[Route('/noteMax', name: 'noteMax')]
    public function noteMax( ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $order = 'avg(c.note)';
        $by= 'DESC';
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsNoteClasser($page, $order, $by);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }

    #[Route('/nbNoteMin', name: 'nbNoteMin')]
    public function nbNoteMin( ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $order = 'COUNT(c.note)';
        $by = 'ASC';
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsNoteClasser($page, $order, $by);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }

    #[Route('/nbNoteMax', name: 'nbNoteMax')]
    public function nbNoteMax( ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $order = 'COUNT(c.note)';
        $by = 'DESC';
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsNoteClasser($page, $order, $by);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }

    #[Route('/nomMax', name: 'nomMax')]
    public function nomMax(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $page = (int)$request->query->get("page", 1);
        $value = 'p.nom';
        $produit = $ProduitRepository->getPaginatedMax($page, $value);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }
    #[Route('/nomMin', name: 'nomMin')]
    public function nomMin(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $page = (int)$request->query->get("page", 1);
        $value = 'p.nom';
        $produit = $ProduitRepository->getPaginatedMin($page, $value);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduit();

        return $this->render('produit/index.html.twig', [
            'categories' => $category,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les produits'
        ]);
    }



    #[Route('/semaine', name: 'semaine')]
    public function produitsSemaine(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request ): Response
    {
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsSemaine($page);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduitsSemaine();

        return $this->render('produit/semaine.html.twig', [
            'categories' => $category,
            'produits' => $produit ,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des Produits de la semaine'
        ]);
    }

    #[Route('/semaine/prixMax', name: 'semainePrixMax')]
    public function produitsSemainePrixMax(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request ): Response
    {
        $order= "p.prixAchat";
        $by='DESC';
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsSemaineClasser($page, $order, $by);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduitsSemaine();

        return $this->render('produit/semaine.html.twig', [
            'categories' => $category,
            'produits' => $produit ,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des Produits de la semaine'
        ]);
    }
    #[Route('/semaine/prixMin', name: 'semainePrixMin')]
    public function produitsSemainePrixMin(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request ): Response
    {
        $order= "p.prixAchat";
        $by='ASC';
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsSemaineClasser($page, $order, $by);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduitsSemaine();

        return $this->render('produit/semaine.html.twig', [
            'categories' => $category,
            'produits' => $produit ,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des Produits de la semaine'
        ]);
    }
    #[Route('/semaine/nomMax', name: 'semaineNomMax')]
    public function produitsSemainenomMax(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request ): Response
    {
        $order= "p.nom";
        $by='DESC';
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsSemaineClasser($page, $order, $by);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduitsSemaine();

        return $this->render('produit/semaine.html.twig', [
            'categories' => $category,
            'produits' => $produit ,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des Produits de la semaine'
        ]);
    }
    #[Route('/semaine/nomMin', name: 'semaineNomMin')]
    public function produitsSemainenomMin(ProduitRepository $ProduitRepository, CategoryRepository $categoryRepository, Request $request ): Response
    {
        $order= "p.nom";
        $by='ASC';
        $page = (int)$request->query->get("page", 1);
        $produit = $ProduitRepository->getPaginatedProduitsSemaineClasser($page, $order, $by);
        $category = $categoryRepository->findAll();
        $total = $ProduitRepository->getTotalProduitsSemaine();

        return $this->render('produit/semaine.html.twig', [
            'categories' => $category,
            'produits' => $produit ,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des Produits de la semaine'
        ]);
    }

    #[Route('/recherche', name: 'recherche')]
    public function recherche( ProduitRepository $ProduitRepository, Request $request ): Response
    {
        $produit = array();
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

    #[Route('/filtre/{id}', name: 'filtre')]
    public function filtreCategory( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $categorieFiltrer =$category;
        $order= "p.createAt";
        $by='DESC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltre($page, $categorieFiltrer, $order, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
        ]);
    }

    #[Route('/filtre/prixMax/{id}', name: 'filtrePrixMax')]
    public function filtreCategoryPrixMax( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $categorieFiltrer =$category;
        $order= "p.prixAchat";
        $by='DESC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltre($page, $categorieFiltrer, $order, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
        ]);
    }
    #[Route('/filtre/prixMin/{id}', name: 'filtrePrixMin')]
    public function filtreCategoryPrixMin( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $categorieFiltrer =$category;
        $order= "p.prixAchat";
        $by='ASC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltre($page, $categorieFiltrer, $order, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
        ]);
    }
    #[Route('/filtre/nomMax/{id}', name: 'filtreNomMax')]
    public function filtreCategoryNomMax( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $categorieFiltrer =$category;
        $order= "p.nom";
        $by='DESC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltre($page, $categorieFiltrer, $order, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
        ]);
    }
    #[Route('/filtre/nomMin/{id}', name: 'filtreNomMin')]
    public function filtreCategoryNomMin( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $categorieFiltrer =$category;
        $order= "p.nom";
        $by='ASC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltre($page, $categorieFiltrer, $order, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
        ]);
    }

    #[Route('/filtre/noteMin/{id}', name: 'filtreNoteMin')]
    public function filtreCategoryNoteMin( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $orderBy= 'avg(cr.note)';
        $categorieFiltrer =$category;
        $by='ASC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltreNote($page, $categorieFiltrer,$orderBy, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
        ]);
    }

    #[Route('/filtre/noteMax/{id}', name: 'filtreNoteMax')]
    public function filtreCategoryNoteMax( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $orderBy= 'avg(cr.note)';
        $categorieFiltrer =$category;
        $by='DESC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltreNote($page, $categorieFiltrer,$orderBy, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
        ]);
    }

    #[Route('/filtre/nbNoteMin/{id}', name: 'filtreNbNoteMin')]
    public function filtreCategoryNbNoteMin( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $orderBy= 'COUNT(cr.note)';
        $categorieFiltrer =$category;
        $by='ASC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltreNote($page, $categorieFiltrer,$orderBy, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
        ]);
    }

    #[Route('/filtre/nbNoteMax/{id}', name: 'filtreNbNoteMax')]
    public function filtreCategoryNbNoteMax( ProduitRepository $ProduitRepository,CategoryRepository $categoryRepository ,Category $category, Request $request, ): Response
    {
        $orderBy= 'COUNT(cr.note)';
        $categorieFiltrer =$category;
        $by='DESC';
        $page = (int)$request->query->get("page", 1);
        $category = $categoryRepository->findAll();
        $produit = $ProduitRepository->getPaginatedProduitFiltreNote($page, $categorieFiltrer,$orderBy, $by);
        $total = $ProduitRepository->getTotalProduitFiltre($categorieFiltrer);

        return $this->render('produit/filtreCategory.html.twig', [
            'categories' => $category,
            'categorieFiltrer' => $categorieFiltrer,
            'produits' => $produit,
            'total' => $total,
            'page' => $page,
            'titre' => $categorieFiltrer->getNom(),
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
