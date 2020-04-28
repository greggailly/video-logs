<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(UserRepository $repo)
    {
        return $this->render('user/index.html.twig', [
            'users' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/user/create", name="user_create")
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $manager, Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword('test');
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete user
     * 
     * @Route("/user/delete/{id}", name="user_delete")
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager)
    {
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('user');
    }
}
