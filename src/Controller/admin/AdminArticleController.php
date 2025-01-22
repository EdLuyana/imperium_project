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

    #[Route('/admin/create-article', 'create_articles', methods: ['POST', 'GET'])]
    public function createArticle(EntityManagerInterface $entityManager, Request $request)
    {

        $article = new Article();

        $formCreateArticle = $this->createForm(ArticleType::class, $article);
        $formCreateArticle->handleRequest($request);

        if ($formCreateArticle->isSubmitted() && $formCreateArticle->isValid()) {
            $imageFile = $formCreateArticle->get('image')->getData();

            if ($imageFile) {

                $newFileName = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('images_directory'), $newFileName);
                $article->setImage($newFileName);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_articles');
        }
        $formView = $formCreateArticle->createView();
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


        if ($formEditArticle->isSubmitted() && $formEditArticle->isValid()) {
            $imageFile = $formEditArticle->get('image')->getData();

            if ($imageFile) {

                if($articleEdited->getImageFilename()){
                    unlink($this->getParameter('images_directory').'/'.$articleEdited->getImage());
                }
                $newFileName = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('images_directory'), $newFileName);
                $articleEdited->setImageFilename($newFileName);
            }

            $entityManager->persist($articleEdited);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_articles');
        }
        $formView = $formEditArticle->createView();
        return $this->render('admin/articles/edit.html.twig', [
            'formView' => $formView,
        ]);
    }

    #[Route('/admin/delete-article/{id}', 'delete_article', methods: ['POST'])]
    public function deleteArticle(int $id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {

        $articleToDelete = $articleRepository->find($id);
        if (!$articleToDelete) {
            $this->addFlash('error', 'Article introuvable.');
            return $this->redirectToRoute('admin_list_articles');
        }
        $entityManager->remove($articleToDelete);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_articles');
    }

}