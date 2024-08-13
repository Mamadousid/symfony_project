<?php

namespace App\Controller\Visitor\Main;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/main', name: 'visitor_main_index', methods:['GET'])]
    public function index(productRepository $productRepository): Response
    {
        $products = $productRepository->findAllByCategorie();
        $productsE = $productRepository->findByCategory(10);
      
       
    

        return $this->render('pages/visitor/main/index.html.twig', [
            'products' => $products,
            'productsE' => $productsE,
        ]);
    }

}
