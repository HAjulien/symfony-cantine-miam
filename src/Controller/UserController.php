<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user',name:'user_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( UserRepository $userRepository ): Response
    {
        $user = $userRepository->findAll();
        //dd($user);

        return $this->render('user/index.html.twig', [
            'users' => $user    
        ]);
    }
    
}
