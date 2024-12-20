<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCategoryController extends AbstractController
{
    #[Route('/admin/list-categories', name: 'admin_list_categories')]
    public function adminListCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/categories/list.html.twig', ['categories' => $categories]);
    }

    #[Route('/admin/create-category', name: 'admin_create_category', methods: ['GET', 'POST'])]
    public function createCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $formCreateCategory = $this->createForm(CategoryType::class, $category);
        $formCreateCategory->handleRequest($request);
        $formView = $formCreateCategory->createView();

        if ($formCreateCategory->isSubmitted() && $formCreateCategory->isValid()) {

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_categories');
        }
        return $this->render('admin/categories/create.html.twig', [
            'formView' => $formView
        ]);
    }

    #[Route('admin/category{id}-edit', name: 'edit_category', methods: ['GET', 'POST'])]
    public function editCategory(int $id, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {

        $categoryEdited = $categoryRepository->find($id);

        $formEditCategory = $this->createForm(CategoryType::class, $categoryEdited);
        $formEditCategory->handleRequest($request);
        $formView = $formEditCategory->createView();

        if ($formEditCategory->isSubmitted() && $formEditCategory->isValid()) {

            $entityManager->persist($categoryEdited);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_categories');
        }
        return $this->render('admin/categories/edit.html.twig', [
            'formView' => $formView
        ]);
    }

    #[Route('/admin/delete-category', name: 'admin_delete_category', methods: ['POST'])]
    public function deleteCategory(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {

        $categoryDeleted = $categoryRepository->find($id);

        $entityManager->remove($categoryDeleted);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_categories');
    }

}
