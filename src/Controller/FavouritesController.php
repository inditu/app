<?php

namespace App\Controller;

use App\Entity\Favourites;
use App\Entity\Medicine;
use App\Entity\User;
use App\Form\FavouritesType;
use App\Repository\FavouritesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/favourites")
 */
class FavouritesController extends AbstractController
{
    /**
     * @Route("/", name="favourites_index", methods={"GET"})
     */
    public function index(FavouritesRepository $favouritesRepository, Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('favourites/index.html.twig', [
            'favourites' => $favouritesRepository->findBy(['user'=>$user]),
        ]);
    }

    /**
     * @Route("/new/{id}", name="favourites_new", methods={"GET","POST"})
     */
    public function new(Request $request, Medicine $medicine, Security $security, FavouritesRepository $repository): Response
    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $security->getUser();
        if(!$this->isLoggedUser($user)){
            $this->addFlash('danger', 'log_in_to_add_favourite');

            return $this->redirect($this->generateUrl($request->headers->get('referer')));
        }

        $favourite = $repository->findOneBy(['user'=>$user, 'medicine'=>$medicine]) ?? new Favourites();
        $form = $this->createForm(FavouritesType::class, $favourite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isLoggedUser($user)) {
            $favourite->setUser($user);
            $favourite->setMedicine($medicine);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($favourite);
            $entityManager->flush();

            return $this->redirectToRoute('favourites_index');
        }

        return $this->render('favourites/new.html.twig', [
            'favourite' => $favourite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="favourites_show", methods={"GET"})
     */
    public function show(Favourites $favourite): Response
    {
        return $this->render('favourites/show.html.twig', [
            'favourite' => $favourite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="favourites_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Favourites $favourite): Response
    {
        $form = $this->createForm(FavouritesType::class, $favourite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('favourites_index');
        }

        return $this->render('favourites/edit.html.twig', [
            'favourite' => $favourite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="favourites_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Favourites $favourite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favourite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($favourite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('favourites_index');
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface|null $user
     * @return bool
     */
    private function isLoggedUser(?\Symfony\Component\Security\Core\User\UserInterface $user): bool
    {
        return $user instanceof User;
    }
}
