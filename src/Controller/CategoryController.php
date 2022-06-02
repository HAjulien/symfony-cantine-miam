<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\SearchUserType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {
        $limit= 5;

        $page = (int)$request->query->get("page", 1);

        $category = $categoryRepository->getPaginatedCategory($page, $limit);


        $total = $categoryRepository->getTotalCategory();

        return $this->render('category/index.html.twig', [
            'categories' => $category,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'titre' => 'Les catégories'
        ]);
    }

    #[Route('/recherche', name: 'recherche')]
    public function recherche( CategoryRepository $categoryRepository, Request $request ): Response
    {
        $category = array();

        $rechercheForm = $this->createForm(SearchUserType::class);

        $search = $rechercheForm->handleRequest($request);

        if($rechercheForm->isSubmitted() && $rechercheForm->isValid()){
            //on recherche les articles correspondant aux mots clés
            $category = $categoryRepository->search($search->get('mots')->getData());
        }

        return $this->render('category/recherche.html.twig', [
            'categories' => $category,
            'rechercheForm' => $rechercheForm->createView(),
            'titre' => 'Faire une recherche d\'une catégorie '
        ]);
    }

    #[Route("/add", name:"add")]
    public function addCategory(Request $request, ManagerRegistry $doctrine): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager(); ancienne depreciée
            $em = $doctrine->getManager();
            //quand l'instance est vide on persist
            $em->persist($category);
            //on insert avec flush
            $em->flush();
            $this->addFlash('success', 'Catégorie ajouté !');
            return $this->redirectToRoute('category_index');
        }
        
        
        return $this->render('category/add.html.twig', [
            'title' => 'Ajout d\'une catégorie',
            'form' => $form->createView(),
        ]);
    }

    #[Route("/update/{id}", name:"update")]
    public function updateCategory(Category $category, Request $request, ManagerRegistry $doctrine): Response
    {

        $form = $this->createForm(CategoryType::class, $category );
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie modifié !');
            return $this->redirectToRoute('category_index');
        }
        
        
        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification d\'une catégorie',
        ]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function delete(Category $category, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'Catégorie supprimé !');
        return $this->redirectToRoute('category_index');
    }
}
