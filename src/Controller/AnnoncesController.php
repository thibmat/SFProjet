<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Image;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/annonces")
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="annonces_index", methods={"GET"})
     * @param Request $request
     * @param AnnoncesRepository $annoncesRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, AnnoncesRepository $annoncesRepository, PaginatorInterface $paginator): Response
    {
        $allAnnoncesQuery = $annoncesRepository->createQueryBuilder('a')
            ->where('a.isPublished = 1')
            ->getQuery();
        $annonces = $paginator->paginate(
            $allAnnoncesQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/new", name="annonces_new", methods={"GET","POST"})
     * @param Request $request
     * @param ObjectManager $entityManager
     * @return Response
     */
    public function new(Request $request, ObjectManager $entityManager): Response
    {
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user  = $this->getUser();
            $image = $form->get("imageFile")->getData();
            $entityManager->persist($image);
            $entityManager->flush();
            $annonce->setAuthor($user);
            $annonce->setImage($image);
            $entityManager->persist($annonce);
            $entityManager->flush();
            return $this->redirectToRoute('annonces_index');
        }
        return $this->render('annonces/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="annonces_show", methods={"GET"})
     */
    public function show(int $id, AnnoncesRepository $repository): Response
    {
        $annonce = $repository->findOneBy([
            'id' => $id,
            'isPublished' => true
        ]);
        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }
        $annonce->setNbViews($annonce->getNbViews() + 1);
        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="annonces_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonces $annonce): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonces_index', [
                'id' => $annonce->getId(),
            ]);
        }

        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="annonces_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Annonces $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }
        return $this->redirectToRoute('annonces_index');
    }
}
