<?php

namespace App\Controller\public;

use App\Form\ProfileEmailType;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;  // Utiliser cette interface
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PublicProfileController extends AbstractController
{
    #[Route('/public/profile', name: 'profile')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,  // Utilisation de UserPasswordHasherInterface
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('home'); // Redirection si l'utilisateur n'est pas connecté
        }

        // Formulaire pour modifier l'email
        $formEmail = $this->createForm(ProfileEmailType::class, $user);
        $formEmail->handleRequest($request);

        if ($formEmail->isSubmitted() && $formEmail->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre email a été mis à jour.');
            return $this->redirectToRoute('profile');
        }

        // Formulaire pour modifier le mot de passe
        $formPassword = $this->createForm(ChangePasswordType::class);
        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $data = $formPassword->getData();
            if ($passwordHasher->isPasswordValid($user, $data['currentPassword'])) {  // Utilisation de la méthode isPasswordValid
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $data['newPassword'])  // Utilisation de hashPassword
                );
                $entityManager->flush();
                $this->addFlash('success', 'Votre mot de passe a été mis à jour.');
                return $this->redirectToRoute('profile');
            } else {
                $formPassword->addError(new \Symfony\Component\Form\FormError('Le mot de passe actuel est incorrect.'));
            }
        }

        return $this->render('public/profile/profile.html.twig', [
            'user' => $user,
            'formEmail' => $formEmail->createView(),
            'formPassword' => $formPassword->createView(),
        ]);
    }
}