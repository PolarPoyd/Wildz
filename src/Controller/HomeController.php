<?php

namespace App\Controller;

use App\Repository\CatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/home/cats', name: 'user_cats', methods: ['GET'])]
    public function read(CatsRepository $catsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $cats = $paginator->paginate(
            $catsRepository->findAll(),
            $request->query->getInt('page', 1), 5
        );

        return $this->render('front/cats.html.twig', [
            'cats' => $cats,
            'user' => $this->security->getUser()
        ]);
    }

    #[Route('/home/cats/like/{catId}', name: 'like_cat', methods: ['POST'])]
    public function likeCat(int $catId, CatsRepository $catsRepository, EntityManagerInterface $em): RedirectResponse
    {
        $cat = $catsRepository->find($catId);
        if ($cat) {
            $user = $this->security->getUser();
            $user->addCatLike($cat);
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_cats');
    }

    #[Route('/home/cats/unlike/{catId}', name: 'unlike_cat', methods: ['POST'])]
    public function unlikeCat(int $catId, CatsRepository $catsRepository, EntityManagerInterface $em): RedirectResponse
    {
         $cat = $catsRepository->find($catId);
        if ($cat) {
            $user = $this->security->getUser();
            $user->removeCatLike($cat);
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_cats');
    }

}
