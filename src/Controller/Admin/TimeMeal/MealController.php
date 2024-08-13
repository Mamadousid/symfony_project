<?php

namespace App\Controller\Admin\TimeMeal;

use App\Entity\TimeMeal;
use App\Form\TimeMealFormType;
use App\Repository\TimeMealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MealController extends AbstractController
{
    #[Route('/admin/timemeal/list', name: 'admin_timemeal_index')]
    public function index(TimeMealRepository $timeMealRepository): Response
    {
        $timemeals = $timeMealRepository->findAll();
        return $this->render('pages/admin/timemeal/index.html.twig', [
            "timemeals" => $timemeals
        ]);
    }

    #[Route('/admin/timeal/create', name: 'admin_timemeal_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response 
    {
        $timemeal = new TimeMeal();

        $form = $this->createForm(TimeMealFormType::class, $timemeal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($timemeal);
            $em->flush();

            $this->addFlash("success", "L'horaire de repas a bien été ajoutée.");

            return $this->redirectToRoute('admin_timemeal_index');
        }

        return $this->render('pages/admin/timemeal/create.html.twig', [
            "form" => $form->createView()
        ]);
    }    

    #[Route('/admin/timemeal/{id}/edit', name: 'admin_timemeal_edit', methods:['GET', 'PUT'])]
    public function edit(TimeMeal $timemeal, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TimeMealFormType::class, $timemeal, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($timemeal);
            $em->flush();

            $this->addFlash('success', "L'heure a bien été modifiée");

            return $this->redirectToRoute("admin_timemeal_index");
        }

        return $this->render("pages/admin/timemeal/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/timemeal/{id}/delete', name: 'admin_timemeal_delete', methods:['DELETE'])]
    public function delete(TimeMeal $timemeal, Request $request, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete_timemeal_'.$timemeal->getId(), $request->request->get('csrf_token'))) 
        {
            $em->remove($timemeal);
            $em->flush();

            $this->addFlash('success', "L'heure a bien été supprimée.");

        }
        
        return $this->redirectToRoute('admin_timemeal_index');
    }
}
