<?php

namespace App\Controller\Api;

use App\Entity\Critique;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CritiqueCreateController extends AbstractController 
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Critique $data)
    {
        $data->setUtilisateur($this->security->getUser());
        $data->setCreateAt(new \DateTime());
        return $data;
        //dd($data);
    }
}