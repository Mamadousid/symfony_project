<?php

namespace App\Controller\Admin\horaire;

use App\Entity\Horaire;
use App\Form\HoraireFormType;
use App\Repository\HoraireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HoraireController extends AbstractController
{
    #[Route('admin/horaire/list', name: 'admin_horaire_index', methods:['GET'])]
    public function index(HoraireRepository $horaireRepository): Response
    {
        $horaires = $horaireRepository->findAll();
        return $this->render('pages/admin/horaire/index.html.twig', [
            "horaires" => $horaires
        ]);
    }

    #[Route('/admin/horaire/create', name: 'admin_horaire_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response 
    {
        $horaire = new Horaire();

        $form = $this->createForm(HoraireFormType::class, $horaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($horaire);
            $em->flush();

            $this->addFlash("success", "L'horaire a bien été ajoutée.");

            return $this->redirectToRoute('admin_horaire_index');
        }

        return $this->render('pages/admin/horaire/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/horaire/{id}/edit', name: 'admin_horaire_edit', methods:['GET', 'PUT'])]
    public function edit(Horaire $horaire, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(HoraireFormType::class, $horaire, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($horaire);
            $em->flush();

            $this->addFlash('success', "L'horaire a bien été modifiée");

            return $this->redirectToRoute("admin_horaire_index");
        }

        return $this->render("pages/admin/horaire/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/horaire/{id}/delete', name: 'admin_horaire_delete', methods:['DELETE'])]
    public function delete(Horaire $horaire, Request $request, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete_horaire_'.$horaire->getId(), $request->request->get('csrf_token'))) 
        {
            $em->remove($horaire);
            $em->flush();

            $this->addFlash('success', "L'horaire a bien été supprimée.");

        }
        
        return $this->redirectToRoute('admin_horaire_index');
    }
}
