<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Form\SearchUserType;
use App\Repository\EquipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/equipe', name: 'equipe_')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EquipeRepository $equipeRepository, Request $request): Response
    {

        $page = (int)$request->query->get("page", 1);

        $equipe = $equipeRepository->getPaginatedEquipe($page);

        $total = $equipeRepository->getTotalEquipe();

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipe,
            'total' => $total,
            'page' => $page,
            'titre' => 'Les membres de l\'équipe'
        ]);
    }

    #[Route('/recherche', name: 'recherche')]
    public function recherche( EquipeRepository $equipeRepository, Request $request ): Response
    {
        $equipe = array();

        $rechercheForm = $this->createForm(SearchUserType::class);

        $search = $rechercheForm->handleRequest($request);

        if($rechercheForm->isSubmitted() && $rechercheForm->isValid()){
            //on recherche les articles correspondant aux mots clés
            $equipe = $equipeRepository->search($search->get('mots')->getData());
        }

        return $this->render('equipe/recherche.html.twig', [
            'equipes' => $equipe,
            'rechercheForm' => $rechercheForm->createView(),
            'titre' => 'Faire une recherche d\'un employé'
        ]);
    }

    #[Route("/add", name:"add")]
    public function addEquipier(Request $request, ManagerRegistry $doctrine): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager(); ancienne depreciée
            $em = $doctrine->getManager();
            //quand l'instance est vide on persist
            $em->persist($equipe);
            //on insert avec flush
            $em->flush();
            $this->addFlash('success', 'Employé ajouté !');
            return $this->redirectToRoute('equipe_index');
        }
        
        
        return $this->render('equipe/add.html.twig', [
            'title' => 'Ajout d\'un employé',
            'form' => $form->createView(),
        ]);
    }
    
    #[Route("/update/{id}", name:"update")]
    public function updateUser(Equipe $equipe, Request $request, ManagerRegistry $doctrine): Response
    {

        $form = $this->createForm(EquipeType::class, $equipe );


        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($equipe);
            $em->flush();
            $this->addFlash('success', 'Employé modifié !');
            return $this->redirectToRoute('equipe_index');
        }
        
        
        return $this->render('equipe/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier les informations d\'un employé',

        ]);
    }

    #[Route("/apercu/{id}", name: "apercu")]
    public function apercu(Equipe $equipe): Response
    {
        return $this->render('equipe/apercu.html.twig', [
            'equipe' => $equipe,
            'title' => 'aperçu de la fiche de l\'employé',

        ]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function delete(Equipe $equipe, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($equipe);
        $em->flush();
        $this->addFlash('success', 'Employé supprimé !');
        return $this->redirectToRoute('equipe_index');
    }
}
