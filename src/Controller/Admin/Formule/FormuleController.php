<?php

namespace App\Controller\Admin\Formule;

use App\Entity\Formule;
use App\Form\FormuleFormType;
use App\Repository\FormuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormuleController extends AbstractController
{
    #[Route('admin/formule/list', name: 'admin_formule_index', methods:['GET'])]
    public function index(FormuleRepository $formuleRepository): Response
    {
        $formules = $formuleRepository->findAll();
        return $this->render('pages/admin/formule/index.html.twig', [
            "formules" => $formules
        ]);
    }


    #[Route('/admin/formule/create', name: 'admin_formule_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $formule = new Formule();

        $form = $this->createForm(FormuleFormType::class, $formule);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($formule);
            $em->flush();

            $this->addFlash("success", "La formule a été ajoutée.");

            return $this->redirectToRoute('admin_formule_index');
        }

        return $this->render('pages/admin/formule/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/formule/{id}/edit', name: 'admin_formule_edit', methods:['GET', 'PUT'])]
    public function edit(Formule $formule, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FormuleFormType::class, $formule, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($formule);
            $em->flush();

            $this->addFlash('success', 'La formule a bien été modifiée');

            return $this->redirectToRoute("admin_formule_index");
        }

        return $this->render("pages/admin/formule/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/formule/{id}/delete', name: 'admin_formule_delete', methods:['DELETE'])]
    public function delete(Formule $formule, Request $request, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete_formule_'.$formule->getId(), $request->request->get('csrf_token'))) 
        {
            $em->remove($formule);
            $em->flush();

            $this->addFlash('success', "La formule a bien été supprimée.");

        }
        
        return $this->redirectToRoute('admin_formule_index');
    }    
}
