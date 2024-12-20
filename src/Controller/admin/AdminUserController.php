<?php

namespace App\Controller\admin;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/users/list', name: 'admin_list_users', methods: ['GET'])]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/users/create', name: 'admin_create_user', methods: ['GET'])]
    public function createUser(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $password = $userForm->get('password')->getData();

            $hashedPassword = $password->hashPassword(
                $password,
                $user
            );
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_users');
        }
        return $this->render('admin/users/create.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'admin_delete_user', methods: ['GET'])]
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $userToDelete = $userRepository->find($id);

        $entityManager->remove($userToDelete);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur envoyé dans le warp');
        return $this->redirectToRoute('admin_list_users');
    }

    #[Route('/admin/users/update/{id}', name: 'admin_update_user', methods: ['GET'])]
    public function updateUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager, PasswordHasherInterface $passwordHasher, Request $request): Response
    {
        $userToUpdate = $userRepository->find($id);

        $userForm = $this->createForm(UserType::class, $userToUpdate);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $clearNewPassword = $userForm->get('password')->getData();

            if ($clearNewPassword) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $userToUpdate,
                    $clearNewPassword
                );
                $userToUpdate->setPassword($hashedPassword);
            }
            $entityManager->persist($userToUpdate);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_users');
        }
        return $this->render('admin/users/update.html.twig', [
            'userForm' => $userForm,
            'userToUpdate' => $userToUpdate,
        ]);
    }
}
