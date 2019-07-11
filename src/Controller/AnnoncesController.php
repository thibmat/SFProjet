<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\Image;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\ImageRepository;
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
    public function index(Request $request, AnnoncesRepository $annoncesRepository, CategoriesRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $allAnnoncesQuery = $annoncesRepository->createQueryBuilder('a')
            ->where('a.isPublished = 1')
            ->getQuery();
        $categories = $categoryRepository->findAll();
        $annonces = $paginator->paginate(
            $allAnnoncesQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
            'categories'=>$categories
        ]);
    }

    /**
     * @Route("/my", name="annonces_myIndex", methods={"GET"})
     * @param Request $request
     * @param AnnoncesRepository $annoncesRepository
     * @param CategoriesRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function mesAnnonces(Request $request, AnnoncesRepository $annoncesRepository, CategoriesRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $allAnnoncesQuery = $annoncesRepository->createQueryBuilder('a')
            ->Where('a.author = :user')
            ->setParameter('user', $this->getUser())
            ->getQuery();
        $categories = $categoryRepository->findAll();
        $annonces = $paginator->paginate(
            $allAnnoncesQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );
        return $this->render('annonces/myIndex.html.twig', [
            'annonces' => $annonces,
            'categories'=>$categories
        ]);
    }

    /**
     * @Route("/new", name="annonces_new", methods={"GET","POST"})
     * @param Request $request
     * @param ObjectManager $entityManager
     * @param CategoriesRepository $categoryRepository
     * @return Response
     */
    public function new(Request $request, ObjectManager $entityManager, CategoriesRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([
            'categorieMere' => null
        ]);
        if (empty($categories)){
            $cats = [];
        }
        foreach ($categories as $cat){
            $sousCatCollec = $cat->getCategorieEnfant();
            if (sizeof($sousCatCollec->toArray()) > 0 )
            {
                $sousCat = [];
                foreach ($sousCatCollec as $enfants)
                {
                    $sousCat[$enfants->getCategoryLibelle()] = $enfants;
                    $cats[$cat->getCategoryLibelle()] = $sousCat;
                }
            }
            else
            {
                $cats[$cat->getCategoryLibelle()] = $cat->getId();
            }
        }
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce, [
            'categories' => $cats
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user  = $this->getUser();
            $annonce->setAuthor($user);
            $image = $form->get("imageFile")->getData();
            $annonce->addImage($image);
            $entityManager->persist($annonce);
            $image-> setAnnonces($annonce);
            $entityManager->persist($image);
            $entityManager->flush();
            return $this->redirectToRoute('annonces_index');
        }
        return $this->render('annonces/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
            'categories'=>$categories,
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_show", methods={"GET"})
     * @param int $id
     * @param AnnoncesRepository $repository
     * @param ImageRepository $imageRepository
     * @return Response
     */
    public function show(int $id, AnnoncesRepository $repository, ImageRepository $imageRepository, ObjectManager $entityManager): Response
    {
        $annonce = $repository->findOneBy([
            'id' => $id,
            'isPublished' => true
        ]);
        $images = $imageRepository->findBy([
            'annonces'=>$id
        ]);
        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }
        $annonce->setNbViews($annonce->getNbViews() + 1);
        $entityManager->flush();

        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
            'images' => $images
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
    public function getNumberAnnonces(AnnoncesRepository $annoncesRepository):Response
    {
        $user = $this->getUser();
        $n = $annoncesRepository->createQueryBuilder('ann');
        $n->select('count(ann.author)');
        $nbreAnnonces = $n
            ->where('ann.author = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
        return $this->render(
            'inc/numberAnnonces.html.twig',
            ['nbreAnnonces' => $nbreAnnonces]);
    }

    /**
     * @Route("/category/{slug<[a-z0-9\-]+>}", name="annonces_filtered_category", methods={"GET"})
     * @param Request $request
     * @param AnnoncesRepository $annoncesRepository
     * @param CategoriesRepository $categoryRepository
     * @param Categories $category
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function filterByCatgeorie (
        Request $request,
        AnnoncesRepository $annoncesRepository,
        CategoriesRepository $categoryRepository,
        Categories $category,
        PaginatorInterface $paginator
    ):Response
    {
        $allAnnoncesFilteredByCategory = $annoncesRepository->createQueryBuilder('a')
            ->where('a.category = :category')
            ->setParameter('category', $category)
            ->getQuery();
        $categories = $categoryRepository->findAll();
        $annonces = $paginator->paginate(
            $allAnnoncesFilteredByCategory, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
            'categories'=>$categories,
            'categorySelected'=>$category
        ]);
    }
    /**
     * @Route("/prix/{min}/{max}", name="annonces_filtered_prix", methods={"GET"})
     * @param Request $request
     * @param AnnoncesRepository $annoncesRepository
     * @param CategoriesRepository $categoryRepository
     * @param Categories $category
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function filterByPrix (
        Request $request,
        AnnoncesRepository $annoncesRepository,
        PaginatorInterface $paginator,
        CategoriesRepository $categoryRepository,
        int $min,
        int $max
    ):Response
    {
        $allAnnoncesFilteredByPrix = $annoncesRepository->createQueryBuilder('a')
            ->where('a.annoncePrix > :min AND a.annoncePrix < :max')
            ->setParameter('min', $min)
            ->setParameter('max', $max)
            ->getQuery();
        $categories = $categoryRepository->findAll();
        $annonces = $paginator->paginate(
            $allAnnoncesFilteredByPrix, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
            'min'=>$min,
            'max'=>$max,
            'categories' => $categories
         ]);
    }
}
