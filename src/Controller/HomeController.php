<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserProfileType;
use App\Form\ChangePasswordType;
use App\Repository\CatsRepository;
use App\Repository\DogsRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/', name: 'app_home')]
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
            $request->query->getInt('page', 1), 10
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

    #[Route('/home/dogs', name: 'user_dogs', methods: ['GET'])]
public function readDogs(DogsRepository $dogsRepository, PaginatorInterface $paginator, Request $request): Response
{
    $dogs = $paginator->paginate(
        $dogsRepository->findAll(),
        $request->query->getInt('page', 1), 10
    );

    return $this->render('front/dogs.html.twig', [
        'dogs' => $dogs,
        'user' => $this->security->getUser()
    ]);
}

#[Route('/home/dogs/like/{dogId}', name: 'like_dog', methods: ['POST'])]
public function likeDog(int $dogId, DogsRepository $dogsRepository, EntityManagerInterface $em): RedirectResponse
{
    $dog = $dogsRepository->find($dogId);
    if ($dog) {
        $user = $this->security->getUser();
        $user->addDog($dog);
        $em->persist($user);
        $em->flush();
    }

    return $this->redirectToRoute('user_dogs');
}

#[Route('/home/dogs/unlike/{dogId}', name: 'unlike_dog', methods: ['POST'])]
public function unlikeDog(int $dogId, DogsRepository $dogsRepository, EntityManagerInterface $em): RedirectResponse
{
    $dog = $dogsRepository->find($dogId);
    if ($dog) {
        $user = $this->security->getUser();
        $user->removeDog($dog);
        $em->persist($user);
        $em->flush();
    }

    return $this->redirectToRoute('user_dogs');
}

#[Route('/service', name: 'app_service')]
public function service(): Response
{
    return $this->render('front/service.html.twig', [
        'controller_name' => 'HomeController',
    ]);
}

#[Route('/profile', name: 'app_user_profile_edit', methods: ['GET', 'POST'])]
public function editProfile(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
{
    // Get the currently authenticated user
    $user = $this->getUser();

    // If there is no user, throw an error (this should be caught by your firewall, but just in case)
    if (!$user) {
        throw new AccessDeniedException('You must be logged in to access this page.');
    }

    $form = $this->createForm(UserProfileType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $plainPassword = $form->get('plainPassword')->getData();
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $userRepository->save($user, true);

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('front/user.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);
}

#[Route('/profile/change-password', name: 'app_user_change_password', methods: ['GET', 'POST'])]
public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    if (!$user) {
        throw new AccessDeniedException('You must be logged in to access this page.');
    }

    $form = $this->createForm(ChangePasswordType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $oldPassword = $form->get('oldPassword')->getData();
        $newPassword = $form->get('newPassword')->getData();

        // Check old password
        if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
            // Add an error message
            $form->addError(new FormError('Le mot de passe actuel est incorrect.'));
        } else {
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
        }
    }

    return $this->render('front/change_password.html.twig', [
        'form' => $form->createView(),
    ]);
}




}
