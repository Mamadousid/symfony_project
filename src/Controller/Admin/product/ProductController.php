<?php

namespace App\Controller\Admin\product;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/admin/product/list', name: 'admin_product_index', methods:['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('pages/admin/product/index.html.twig', [
            "products" => $products
        ]);
    }

    #[Route('/admin/product/create', name: 'admin_product_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $admin = $this->getUser();

            $product->setUser($admin);

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Le produit a bien été ajoutée et sauvegardée.');

            return $this->redirectToRoute('admin_product_index');

        }

        return $this->render('pages/admin/product/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/product/{id}/edit', name: 'admin_product_edit', methods:['GET', 'PUT'])]
    public function edit(Product $product, Request $request, EntityManagerInterface $em): Response 
    {

        $form = $this->createForm(ProductFormType::class, $product, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $admin = $this->getUser();

            $product->setUser($admin);

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Le produit a bien été modifiée et sauvegardée.');

            return $this->redirectToRoute('admin_product_index');

        }


        return $this->render('pages/admin/product/edit.html.twig', [
            "product" => $product,
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/product/{id}/delete', name: 'admin_product_delete', methods:['DELETE'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete_product_'.$product->getId(), $request->request->get('csrf_token'))) 
        {
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', "Le produit a bien été supprimée.");

        }
        
        return $this->redirectToRoute('admin_product_index');
    }
}
