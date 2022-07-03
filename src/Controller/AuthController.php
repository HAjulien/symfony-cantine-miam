<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/api')]
class AuthController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private SerializerInterface $serializer
    )
    {
        
    }

    #[Route('/register', name:'user.register', methods:["POST", "GET"])]
    public function app(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent());

        //on enleve les espace des data reçu pour comparer ensuite 
        $stremail = $data->email;
        $email = preg_replace("/\s+/", "", $stremail);

        $strpseudo = $data->pseudo;
        $pseudo = preg_replace("/\s+/", "", $strpseudo);

        $strAfpa = $data->identifiantAfpa;
        $Afpa = preg_replace("/\s+/", "", $strAfpa);

        // Validate password strength return false si password ne contient pas ...
        $password = $data->password;
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        //$specialChars = preg_match('@[^\w]@', $password);


        if (
            strlen($email) < 7 or strlen($email) > 80 or $email != $data->email or filter_var( $data->email, FILTER_VALIDATE_EMAIL) == false
            or strlen($pseudo) < 4 or strlen($pseudo) > 60 or $pseudo != $data->pseudo
            or ctype_digit($Afpa) == false or strlen($Afpa) != 9
            or !$uppercase or !$lowercase or !$number or strlen($password) < 8
            ){
                return new JsonResponse([
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'password' => $password,
                    'identifiantAfpa' => $Afpa,
                    'emailtest' => filter_var( $data->email, FILTER_VALIDATE_EMAIL),
                    'erreur' => 'erreur'
            ]);
        };

        $user = $this->userRepository->create($data);
        
        return new JsonResponse([
            'user' => $this->serializer->serialize($user, 'json')
        ], 201); 
    }

    #[Route('/profile', name:'user.profile', methods:["POST", "GET"])]
    public function profile(Security $security) : JsonResponse
    {

        $currentUser= $security->getUser();
        $user = $this->serializer->serialize($currentUser, 'json');

        return new JsonResponse([
            'user' => $user,
        ],);
    }
} 