<?php

namespace App\Controller\Admin\Booking;

use App\Entity\Booking;
use App\Form\BookingFormType;
use App\Service\SendEmailService;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    #[Route('/admin/booking/list', name: 'admin_booking_index', methods:['GET'])]
    public function index(BookingRepository $bookingRepository): Response
    {
        $bookings = $bookingRepository->findAll();
        return $this->render('pages/admin/booking/index.html.twig', [
            "bookings" => $bookings
        ]);
    }

    #[Route('/admin/booking/{id}/edit', name: 'admin_booking_edit', methods:['GET', 'PUT'])]
    public function edit(Booking $booking, Request $request, EntityManagerInterface $em , SendEmailService $sendEmailService): Response
    {
        $form = $this->createForm(BookingFormType::class, $booking, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($booking);
            $em->flush();

            $sendEmailService->send([
                "sender_email" => "toqueor-restaurant@gmail.com",
                "sender_name" => "Restaurant Toque'Or",
                "recipient_email" => $booking->getEmail(),
                "subject" => "Restaurant Toque'Or :Modification de votre réservation!",
                "html_template" => "emails/edit_booking.html.twig",
                "context"   => [
                    "booking_first_name"   => $booking->getFirstName(),
                    "booking_last_name"    => $booking->getLastName(),
                    "booking_email"        => $booking->getEmail(),
                    "booking_date_booking" => $booking->getDateBooking()->format('Y-m-d'), // Conversion en chaîne de caractères
                    "booking_timemeal"     => $booking->getTimeMeal()->getHeure(), // Assurez-vous que 'getHeure' renvoie une chaîne
                    "booking_minutes"      => $booking->getMinutes()->getMinute(), // Assurez-vous que 'getMinute' renvoie une chaîne
                    "booking_guest"        => $booking->getGuest(),
                    "booking_typetable"    => $booking->getTypetable()->getName(), // Assurez-vous que 'getName' renvoie une chaîne
                    "booking_message"      => $booking->getMessage(),
                ]
            ]);

            $this->addFlash('success', 'La réservation a bien été modifiée. Un email de confirmation a été envoyé au client.');

            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render("pages/admin/booking/edit.html.twig", [
            "form" => $form->createView()
        ]);

    }

    #[Route('/admin/booking/{id}/delete', name: 'admin_booking_delete', methods:['DELETE'])]
    public function delete(Booking $booking, Request $request, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete_booking_'.$booking->getId(), $request->request->get('csrf_token'))) 
        {
            $em->remove($booking);
            $em->flush();

            $this->addFlash('success', "La réservation a bien été supprimée.");

        }
        
        return $this->redirectToRoute('admin_booking_index');
    }
}
