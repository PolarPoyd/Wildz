<?php

namespace App\Controller;

use App\Repository\DogRaceRepository;
use App\Repository\CatRaceRepository;
use App\Repository\CatsRepository;
use App\Repository\DogsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminHomeController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/admin', name: 'home.index', methods: ['GET'])]
    public function index(
        DogRaceRepository $dogRaceRepository,
        CatRaceRepository $catRaceRepository,
        CatsRepository $catsRepository,
        DogsRepository $dogsRepository,
        UserRepository $userRepository
    ): Response {
        // permet de renvoyer un template twig (render)
        return $this->render('pages/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'dog_races' => $dogRaceRepository->findAll(),
            'cat_races' => $catRaceRepository->findAll(),
            'cats' => $catsRepository->findAll(),
            'dogs' => $dogsRepository->findAll(),
            'users' => $userRepository->findAll(),
        ]);
    }
}
