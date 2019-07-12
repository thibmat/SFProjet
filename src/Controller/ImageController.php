<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/image")
 */
class ImageController extends AbstractController
{
    private $repository;

    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="image_index", methods={"GET"})
     * @param ImageRepository $imageRepository
     * @return Response
     */
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="image_new", methods={"GET","POST"})
     * @param Request $request
     * @param ObjectManager $entityManager
     * @param Annonces $annonces
     * @return Response
     */
    public function new(Request $request, ObjectManager $entityManager): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image->setAnnonces($annonces);
            $entityManager->persist($image);
            $entityManager->flush();
            return $this->redirectToRoute('image_index');
        }
        return $this->render('image/new.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/{id<[a-z0-9\-]+>}", name="image_new_ajax", methods={"GET","POST"})
     * @param Request $request
     * @param ObjectManager $entityManager
     * @param Annonces $annonces
     * @return Image[]
     */
    public function newImageAjax(Request $request, ObjectManager $entityManager, Annonces $annonces)
    {
        $image = new Image();
        $image->setImageFile($request->files->get('file'));
        $image->setAnnonces($annonces);
        $entityManager->persist($image);
        $entityManager->flush();
        return $this->getImageFiles($annonces);
    }

    public function getImageFiles ($annonce)
    {
        return $this->repository->findBy([
            'annonces'=>$annonce
        ]);
    }
    /**
     * @Route("/{id}", name="image_show", methods={"GET"})
     */
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="image_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Image $image): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('image_index', [
                'id' => $image->getId(),
            ]);
        }

        return $this->render('image/edit.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="image_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Image $image): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('image_index');
    }
}
