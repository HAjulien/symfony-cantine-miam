<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\SearchUserType;
use App\Form\UserType;
use App\Form\UserUpdateFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( UserRepository $userRepository, Request $request ): Response
    {

        // le nombre d'éléments par page est défini par variable env $_ENV['LIMIT_PAGINATION_5']


        //onrécupére le numéro de page
        $page = (int)$request->query->get("page", 1);
        //dd($page);

        //on récupére les users de la page
        $user = $userRepository->getPaginatedUsers($page);
        //$user = $userRepository->findAll();
        //dd($user);


        //on récupére le nombre total de users
        $total = $userRepository->getTotalUsers();
        //dd($total);

        return $this->render('user/index.html.twig', [
            'users' => $user,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des Utilisateurs'
        ]);
    }

    #[Route('/personnel', name: 'personnel')]
    public function personnel( UserRepository $userRepository, Request $request ): Response
    {
        $page = (int)$request->query->get("page", 1);
        //dd($page);
        $user = $userRepository->getPaginatedPersonnel($page);
        //dd($user);

        $total = $userRepository->getTotalUsersPersonnel();
        //dd($total);

        return $this->render('user/index.html.twig', [
            'users' => $user ,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des membre du Personnel'
        ]);
    }

    #[Route('/nonVerifie', name: 'nonVerifie')]
    public function nonVerifie( UserRepository $userRepository, Request $request ): Response
    {
        $page = (int)$request->query->get("page", 1);
        //dd($page);
        $user = $userRepository->getPaginatedNonVerifie($page);
        //dd($user);

        $total = $userRepository->getTotalUsersNonVerifie();
        //dd($total);

        return $this->render('user/index.html.twig', [
            'users' => $user ,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des Utilisateurs Non Vérifiés'
        ]);
    }

    #[Route('/recherche', name: 'recherche')]
    public function recherche( UserRepository $userRepository, Request $request ): Response
    {
        $user = array();

        $rechercheForm = $this->createForm(SearchUserType::class);

        $search = $rechercheForm->handleRequest($request);

        if($rechercheForm->isSubmitted() && $rechercheForm->isValid()){
            //on recherche les users correspondant aux mots clés
            $user = $userRepository->search($search->get('mots')->getData());
        }

        return $this->render('user/recherche.html.twig', [
            'users' => $user,
            'rechercheForm' => $rechercheForm->createView(),
            'titre' => 'Faire une recherche sur les utilisateurs'

        ]);
    }

    #[Route("/add", name:"add")]
    public function addCategory(Request $request,  UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
    
            // $em = $this->getDoctrine()->getManager(); ancienne depreciée
            $em = $doctrine->getManager();
            //quand l'instance est vide on persist
            $em->persist($user);
            //on insert avec flush
            $em->flush();
            $this->addFlash('success', 'Utilisateur ajouté !');
            return $this->redirectToRoute('user_index');
        }
        
        
        return $this->render('user/add.html.twig', [
            'title' => 'Ajout d\'un utilisateur',
            'form' => $form->createView(),
        ]);
    }

    #[Route("/update/{id}", name:"update")]
    public function updateUser(User $user, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(UserUpdateFormType::class, $user );
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur modifié !');
            return $this->redirectToRoute('user_index');
        }
        
        
        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification d\'un utilisateur',

        ]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function delete(User $user, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Utilisateur supprimé !');
        return $this->redirectToRoute('user_index');
    }

}
