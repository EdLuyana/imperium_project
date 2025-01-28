<?php

namespace App\Controller\public;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicArticleController extends AbstractController
{
    #[Route('/list-articles/imperium', name: 'imperium')]
    public function publicListArticlesImperium(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('public/articles/list_imperium.html.twig', ['articles' => $articles]);
    }
#[Route('/list-articles/together', 'together', methods: ['GET'])]
    public function publicListArticleTogether(ArticleRepository $articleRepository): Response
{

    $articles = $articleRepository->findByCategory('Ennemis');

    return $this->render('public/articles/list_together.html.twig', ['articles' => $articles]);
    }
}
