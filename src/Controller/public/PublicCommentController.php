<?php

namespace App\Controller\public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicCommentController extends AbstractController
{
    #[Route('/comment', name: 'app_public_comment')]
    public function index(): Response
    {
        return $this->render('public_comment/index.html.twig', [
            'controller_name' => 'PublicCommentController',
        ]);
    }
}
