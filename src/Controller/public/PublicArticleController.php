<?php

namespace App\Controller\public;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicArticleController extends AbstractController
{
    #[Route('/public/list-articles', name: 'public_list_articles')]
    public function publicListArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('public/articles/list.html.twig', ['articles' => $articles]);
    }
#[Route('/public/show-article/{id}', 'public_show_article', methods: ['GET'])]
    public function publicShowArticle(ArticleRepository $articleRepository, int $id): Response
{
    $article = $articleRepository->find($id);

    if(!$article) {
    $this->addFlash('error', 'Article not found');
    return $this->redirectToRoute('public_list_articles');
    }
    return $this->render('public/articles/show.html.twig', ['article' => $article]);
    }
}
