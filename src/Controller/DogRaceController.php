<?php

namespace App\Controller;

use App\Entity\DogRace;
use App\Form\DogRaceType;
use App\Repository\DogRaceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('/dog/race')]
class DogRaceController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/', name: 'app_dog_race_index', methods: ['GET'])]
    public function index(DogRaceRepository $dogRaceRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $dog_races = $paginator->paginate(
            $dogRaceRepository->findAll(),
            $request->query->getInt('page', "1"), 4
        );

        return $this->render('pages/dog_race/index.html.twig', [
            'dog_races' => $dogRaceRepository->findAll(),
            'dog_races' => $dog_races
        ]);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/new', name: 'app_dog_race_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DogRaceRepository $dogRaceRepository): Response
    {
        $dogRace = new DogRace();
        $form = $this->createForm(DogRaceType::class, $dogRace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dogRaceRepository->save($dogRace, true);

            return $this->redirectToRoute('app_dog_race_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/dog_race/new.html.twig', [
            'dog_race' => $dogRace,
            'form' => $form,
        ]);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}', name: 'app_dog_race_show', methods: ['GET'])]
    public function show(DogRace $dogRace): Response
    {
        return $this->render('pages/dog_race/show.html.twig', [
            'dog_race' => $dogRace,
        ]);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}/edit', name: 'app_dog_race_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DogRace $dogRace, DogRaceRepository $dogRaceRepository): Response
    {
        $form = $this->createForm(DogRaceType::class, $dogRace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dogRaceRepository->save($dogRace, true);

            return $this->redirectToRoute('app_dog_race_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/dog_race/edit.html.twig', [
            'dog_race' => $dogRace,
            'form' => $form,
        ]);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}', name: 'app_dog_race_delete', methods: ['POST'])]
    public function delete(Request $request, DogRace $dogRace, DogRaceRepository $dogRaceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dogRace->getId(), $request->request->get('_token'))) {
            $dogRaceRepository->remove($dogRace, true);
        }

        return $this->redirectToRoute('app_dog_race_index', [], Response::HTTP_SEE_OTHER);
    }
}
