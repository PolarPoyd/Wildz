<?php

namespace App\Controller;

use App\Entity\CatRace;
use App\Form\CatRaceType;
use App\Repository\CatRaceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('/admin/cat/race')]
class CatRaceController extends AbstractController
{

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/', name: 'app_cat_race_index', methods: ['GET'])]
    public function index(CatRaceRepository $catRaceRepository,  PaginatorInterface $paginator, Request $request): Response
    {
        $cat_races = $paginator->paginate(
            $catRaceRepository->findAll(),
            $request->query->getInt('page', '1'), 4
        );

        return $this->render('pages/cat_race/index.html.twig', [
            'cat_races' => $catRaceRepository->findAll(),
            'cat_races' => $cat_races
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
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

        return $this->render('pages/cat_race/new.html.twig', [
            'cat_race' => $catRace,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}', name: 'app_cat_race_show', methods: ['GET'])]
    public function show(CatRace $catRace): Response
    {
        return $this->render('pages/cat_race/show.html.twig', [
            'cat_race' => $catRace,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}/edit', name: 'app_cat_race_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CatRace $catRace, CatRaceRepository $catRaceRepository): Response
    {
        $form = $this->createForm(CatRaceType::class, $catRace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catRaceRepository->save($catRace, true);

            return $this->redirectToRoute('app_cat_race_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/cat_race/edit.html.twig', [
            'cat_race' => $catRace,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */

#[Route('/{id}', name: 'app_cat_race_delete', methods: ['POST'])]
public function delete(Request $request, CatRace $catRace, CatRaceRepository $catRaceRepository): Response
{
    if ($this->isCsrfTokenValid('delete'.$catRace->getId(), $request->request->get('_token'))) {
        foreach ($catRace->getCats() as $cat) {
            $catRace->removeCat($cat);
        }
        $catRaceRepository->remove($catRace, true);
    }

    return $this->redirectToRoute('app_cat_race_index', [], Response::HTTP_SEE_OTHER);
}

}
