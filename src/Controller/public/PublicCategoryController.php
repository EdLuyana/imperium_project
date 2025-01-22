<?php

namespace App\Controller\public;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicCategoryController extends AbstractController
{
    #[Route('/list-categories', name: 'public_list_categories')]
    public function publicListCategories(CategoryRepository $categoryRepository): Response
    {
       $categories = $categoryRepository->findAll();

       return $this->render('public/categories/list.html.twig', [
           'categories' => $categories
       ]);
    }
    #[Route('/show-category/{id}', name: 'public_show_category', methods: ['GET'])]
public function publicShowCategory(CategoryRepository $categoryRepository, int $id): Response
    {
        $category = $categoryRepository->find($id);

        if(!$category) {
            $this->addFlash('error', 'Category not found');
            return $this->redirectToRoute('public_list_categories');
        }
        return $this->render('public/categories/show.html.twig', [
            'category' => $category
        ]);
    }
}
