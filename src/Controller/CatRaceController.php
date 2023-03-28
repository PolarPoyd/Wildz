<?php

namespace App\Controller;

use App\Entity\CatRace;
use App\Form\CatRaceType;
use App\Repository\CatRaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cat/race')]
class CatRaceController extends AbstractController
{
    #[Route('/', name: 'app_cat_race_index', methods: ['GET'])]
    public function index(CatRaceRepository $catRaceRepository): Response
    {
        return $this->render('pages/cat_race/index.html.twig', [
            'cat_races' => $catRaceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cat_race_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CatRaceRepository $catRaceRepository): Response
    {
        $catRace = new CatRace();
        $form = $this->createForm(CatRaceType::class, $catRace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catRaceRepository->save($catRace, true);

            return $this->redirectToRoute('app_cat_race_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/cat_race/new.html.twig', [
            'cat_race' => $catRace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cat_race_show', methods: ['GET'])]
    public function show(CatRace $catRace): Response
    {
        return $this->render('pages/cat_race/show.html.twig', [
            'cat_race' => $catRace,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cat_race_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CatRace $catRace, CatRaceRepository $catRaceRepository): Response
    {
        $form = $this->createForm(CatRaceType::class, $catRace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catRaceRepository->save($catRace, true);

            return $this->redirectToRoute('app_cat_race_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/cat_race/edit.html.twig', [
            'cat_race' => $catRace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cat_race_delete', methods: ['POST'])]
    public function delete(Request $request, CatRace $catRace, CatRaceRepository $catRaceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catRace->getId(), $request->request->get('_token'))) {
            $catRaceRepository->remove($catRace, true);
        }

        return $this->redirectToRoute('app_cat_race_index', [], Response::HTTP_SEE_OTHER);
    }
}
