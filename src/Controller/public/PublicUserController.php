<?php

namespace App\Controller\public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicUserController extends AbstractController
{
    #[Route('/public/user', name: 'app_public_user')]
    public function index(): Response
    {
        return $this->render('public_user/index.html.twig', [
            'controller_name' => 'PublicUserController',
        ]);
    }
}
