<?php

namespace App\Controller;

use App\Entity\Cats;
use App\Form\CatsType;
use App\Repository\CatsRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cats')]
class CatsController extends AbstractController
{
    #[Route('/', name: 'app_cats_index', methods: ['GET'])]
    public function index(CatsRepository $catsRepository): Response
    {
        return $this->render('pages/cats/index.html.twig', [
            'cats' => $catsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CatsRepository $catsRepository): Response
    {
        $cat = new Cats();
        $cat->setUpdatedAt(new DateTimeImmutable());
        $form = $this->createForm(CatsType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catsRepository->save($cat, true);

            return $this->redirectToRoute('app_cats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/cats/new.html.twig', [
            'cat' => $cat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cats_show', methods: ['GET'])]
    public function show(Cats $cat): Response
    {
        return $this->render('pages/cats/show.html.twig', [
            'cat' => $cat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cats $cat, CatsRepository $catsRepository): Response
    {
        $form = $this->createForm(CatsType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catsRepository->save($cat, true);

            return $this->redirectToRoute('app_cats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/cats/edit.html.twig', [
            'cat' => $cat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cats_delete', methods: ['POST'])]
    public function delete(Request $request, Cats $cat, CatsRepository $catsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cat->getId(), $request->request->get('_token'))) {
            $catsRepository->remove($cat, true);
        }

        return $this->redirectToRoute('app_cats_index', [], Response::HTTP_SEE_OTHER);
    }
}
