<?php

namespace App\Controller\admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminArticleController extends AbstractController
{

    #[Route('/admin/list-articles', name: 'admin_list_articles', methods: ['GET'])]
    public function adminListArticles(ArticleRepository $articleRepository)
    {

        $articles = $articleRepository->findAll();

        return $this->render('admin/articles/list.html.twig', ['articles' => $articles]);
    }

    #[Route('/admin/create-article', 'create_articles', methods: ['POST'])]
    public function createArticle(EntityManagerInterface $entityManager, Request $request)
    {

        $article = new Article();

        $formCreateArticle = $this->createForm(ArticleType::class, $article);
        $formCreateArticle->handleRequest($request);
        $formView = $formCreateArticle->createView();

        if ($formCreateArticle->isSubmitted() && $formCreateArticle->isValid()) {

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_articles');
        }
        return $this->render('admin/articles/create.html.twig', [
            'formView' => $formView,
        ]);
    }

    #[Route('admin/article{id}-edit', 'edit_article', methods: ['POST'])]
    public function editArticle(int $id, EntityManagerInterface $entityManager, Request $request, ArticleRepository $articleRepository)
    {
        $articleEdited = $articleRepository->find($id);

        $formEditArticle = $this->createForm(ArticleType::class, $articleEdited);
        $formEditArticle->handleRequest($request);
        $formView = $formEditArticle->createView();

        if ($formEditArticle->isSubmitted() && $formEditArticle->isValid()) {

            $entityManager->persist($articleEdited);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_articles');
        }
        return $this->render('admin/articles/edit.html.twig', [
            'formView' => $formView,
        ]);
    }

    #[Route('/admin/delete-article', 'delete_article', methods: ['POST'])]
    public function deleteArticle(int $id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {

        $articleToDelete = $articleRepository->find($id);

        $entityManager->remove($articleToDelete);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_articles');
    }

}