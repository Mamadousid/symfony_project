<?php

namespace App\Controller\User\Home;

use App\Form\BookingFormType;
use App\Service\SendEmailService;
use App\Form\EditBookingUserFormType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/user/bookings', name: 'user_bookings_index', methods:['GET'])]
    public function bookings(BookingRepository $bookingRepository): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté

        $futureBookings = $bookingRepository->findFutureBookingsByUser($user);
        $pastBookings = $bookingRepository->findPastBookingsByUser($user);

        return $this->render('pages/user/bookings/index.html.twig', [
            'futureBookings' => $futureBookings,
            'pastBookings' => $pastBookings,
        ]);
    }

    #[Route('/user/booking/edit/{id}', name: 'user_booking_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        BookingRepository $bookingRepository,
        SendEmailService $sendEmailService,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        $booking = $bookingRepository->find($id);

        // Vérifiez si la réservation appartient à l'utilisateur
        if (!$booking || $booking->getUser() !== $user) {
            throw $this->createNotFoundException('Réservation non trouvée ou non autorisée.');
        }

        $form = $this->createForm(EditBookingUserFormType::class, $booking);
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

            $this->addFlash('success', 'Réservation mise à jour avec succès.');

            return $this->redirectToRoute('user_bookings_index');
        }

        return $this->render('pages/user/bookings/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/booking/delete/{id}', name: 'user_booking_delete', methods: ['POST'])]
    public function delete(
        int $id,
        BookingRepository $bookingRepository,
        
        EntityManagerInterface $em
    ): RedirectResponse {
        $user = $this->getUser();
        $booking = $bookingRepository->find($id);

        // Vérifiez si la réservation appartient à l'utilisateur
        if (!$booking || $booking->getUser() !== $user) {
            throw $this->createNotFoundException('Réservation non trouvée ou non autorisée.');
        }

        $em->remove($booking);
        $em->flush();

       


        $this->addFlash('success', 'Réservation annulée avec succès.');

        return $this->redirectToRoute('user_bookings_index');
    }
}
