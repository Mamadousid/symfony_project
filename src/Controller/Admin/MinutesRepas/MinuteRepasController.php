<?php

namespace App\Controller\Admin\MinutesRepas;

use App\Entity\MinutesRepas;
use App\Form\MinutesRepasFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MinutesRepasRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MinuteRepasController extends AbstractController
{
    #[Route('/admin/minute/repas/list', name: 'admin_minute_index', methods:['GET'])]
    public function index(MinutesRepasRepository $minutesRepasRepository): Response
    {
        $minutes = $minutesRepasRepository->findAll();
        return $this->render('pages/admin/minutes/index.html.twig', [
            'minutes' => $minutes,
        ]);
    }

    #[Route('/admin/minute/create', name: 'admin_minute_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response 
    {
        $minute = new MinutesRepas();

        $form = $this->createForm(MinutesRepasFormType::class, $minute);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($minute);
            $em->flush();

            $this->addFlash("success", "L'horaire de repas a bien été ajoutée.");

            return $this->redirectToRoute('admin_minute_index');
        }

        return $this->render('pages/admin/minutes/create.html.twig', [
            "form" => $form->createView()
        ]);
    }    

    #[Route('/admin/minute/{id}/edit', name: 'admin_minute_edit', methods:['GET', 'PUT'])]
    public function edit(MinutesRepas $minute, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MinutesRepasFormType::class, $minute, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($minute);
            $em->flush();

            $this->addFlash('success', "Les minutes ont bien été modifiée");

            return $this->redirectToRoute("admin_minute_index");
        }

        return $this->render("pages/admin/minutes/edit.html.twig", [
            "form" => $form->createView()
        ]);

    }    

    #[Route('/admin/minute/{id}/delete', name: 'admin_minute_delete', methods:['DELETE'])]
    public function delete(MinutesRepas $minute, Request $request, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete_minute_'.$minute->getId(), $request->request->get('csrf_token'))) 
        {
            $em->remove($minute);
            $em->flush();

            $this->addFlash('success', "Les minutes ont bien été supprimée.");

        }
        
        return $this->redirectToRoute('admin_minute_index');
    }

}
