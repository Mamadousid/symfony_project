<?php

namespace App\Controller\Admin\Table;

use App\Form\TableFormType;
use App\Repository\TableRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TableController extends AbstractController
{
    #[Route('admin/table/list', name: 'admin_table_index', methods:['GET'])]
    public function index(TableRepository $tableRepository): Response
    {
        $tables = $tableRepository->findAll();
        return $this->render('pages/admin/table/index.html.twig', [
            "tables" => $tables
        ]);
    }

    #[Route('/admin/table/create', name: 'admin_table_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response 
    {
        $table = new Table();

        $form = $this->createForm(TableFormType::class, $table);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($table);
            $em->flush();

            $this->addFlash("success", "La table a bien été ajoutée.");

            return $this->redirectToRoute('admin_table_index');
        }

        return $this->render('pages/admin/table/create.html.twig', [
            "form" => $form->createView()
        ]);
    }    

    #[Route('/admin/table/{id}/edit', name: 'admin_table_edit', methods:['GET', 'PUT'])]
    public function edit(Table $table, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TableFormType::class, $table, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($table);
            $em->flush();

            $this->addFlash('success', 'La table a bien été modifiée');

            return $this->redirectToRoute("admin_table_index");
        }

        return $this->render("pages/admin/table/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/table/{id}/delete', name: 'admin_table_delete', methods:['DELETE'])]
    public function delete(Table $table, Request $request, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete_table_'.$table->getId(), $request->request->get('csrf_token'))) 
        {
            $em->remove($table);
            $em->flush();

            $this->addFlash('success', "La table a bien été supprimée.");

        }
        
        return $this->redirectToRoute('admin_table_index');
    }
}
