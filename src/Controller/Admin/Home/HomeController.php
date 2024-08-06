<?php

namespace App\Controller\Admin\Home;

use App\Repository\ContactRepository;
use App\Repository\FormuleRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\TableRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/admin/home', name: 'admin_home_index', methods:['GET'])]
    public function index(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        FormuleRepository $formuleRepository,
        ContactRepository $contactRepository,
        UserRepository $userRepository,
        TableRepository $tableRepository

    ): Response
    {
        return $this->render('pages/admin/home/index.html.twig', [
            "categories"  => $categoryRepository->findAll(),
            "products"    => $productRepository->findAll(),
            "formules"    => $formuleRepository->findAll(),
            "contacts"    => $contactRepository->findAll(),
            "users"       => $userRepository->findAll(),
            "tables"      => $tableRepository->findAll(),
        ]);
    }
}
