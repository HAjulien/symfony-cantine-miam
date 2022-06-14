<?php

namespace App\Controller\Admin;

use App\Entity\ImageCarousel;
use App\Form\ImageCarouselType;
use App\Repository\ImageCarouselRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/imageCarousel', name: 'imageCarousel_')]
class ImageCarouselController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ImageCarouselRepository $imageCarouselRepository, Request $request): Response
    {

        $page = (int)$request->query->get("page", 1);

        $image = $imageCarouselRepository->getPaginatedImage($page);

        $total = $imageCarouselRepository->getTotalImage();

        return $this->render('imageCarousel/index.html.twig', [
            'images' => $image,
            'total' => $total,
            'page' => $page,
            'titre' => 'Liste des images dans le carousel'
        ]);
    }

    #[Route("/add", name:"add")]
    public function addImageCarousel(Request $request, ManagerRegistry $doctrine): Response
    {
        $image = new ImageCarousel();
        $form = $this->createForm(ImageCarouselType::class, $image);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($image);
            $em->flush();
            $this->addFlash('success', 'image carousel ajouté !');
            return $this->redirectToRoute('imageCarousel_index');
        }
        
        
        return $this->render('imageCarousel/add.html.twig', [
            'title' => 'Ajout d\'une image dans le carousel',
            'form' => $form->createView(),
        ]);
    }

    #[Route("/update/{id}", name:"update")]
    public function updateImageCarousel(ImageCarousel $image, Request $request, ManagerRegistry $doctrine): Response
    {

        $form = $this->createForm(ImageCarouselType::class, $image );


        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($image);
            $em->flush();
            $this->addFlash('success', 'image modifié !');
            return $this->redirectToRoute('imageCarousel_index');
        }
        
        
        return $this->render('imageCarousel/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification d\'une image',

        ]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function deleteImageCarousel(ImageCarousel $image, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($image);
        $em->flush();
        $this->addFlash('success', 'image supprimé !');
        return $this->redirectToRoute('imageCarousel_index');
    }
}
