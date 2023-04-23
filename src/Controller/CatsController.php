<?php

namespace App\Controller;

use App\Entity\Cats;
use App\Form\CatsType;
use DateTimeImmutable;
use App\Repository\CatsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('/admin/cats')]
class CatsController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/', name: 'app_cats_index', methods: ['GET'])]
    public function index(CatsRepository $catsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $cats = $paginator->paginate(
            $catsRepository->findAll(),
            $request->query->getInt('page', 1), 5
        );

        return $this->render('pages/cats/index.html.twig', [
            'cats' => $cats
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
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

        return $this->render('pages/cats/new.html.twig', [
            'cat' => $cat,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}', name: 'app_cats_show', methods: ['GET'])]
    public function show(Cats $cat): Response
    {
        return $this->render('pages/cats/show.html.twig', [
            'cat' => $cat,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}/edit', name: 'app_cats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cats $cat, CatsRepository $catsRepository): Response
    {
        $form = $this->createForm(CatsType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catsRepository->save($cat, true);

            return $this->redirectToRoute('app_cats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/cats/edit.html.twig', [
            'cat' => $cat,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}', name: 'app_cats_delete', methods: ['POST'])]
    public function delete(Request $request, Cats $cat, CatsRepository $catsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cat->getId(), $request->request->get('_token'))) {
            $catsRepository->remove($cat, true);
        }

        return $this->redirectToRoute('app_cats_index', [], Response::HTTP_SEE_OTHER);
    }
}
