<?php

namespace App\Controller\Visitor\Booking;

use App\Entity\Booking;
use App\Form\BookingFormType;
use App\Service\SendEmailService;
use App\Service\SendSmsService; // Assurez-vous que ce service est correctement configuré
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    #[Route('/booking', name: 'visitor_booking_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        SendEmailService $sendEmailService,
        SendSmsService $sendSmsService // Ajoutez cette ligne si vous avez un service SMS
    ): Response
    {
        $booking = new Booking();

        $form = $this->createForm(BookingFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Assigner l'utilisateur connecté à la réservation
            $user = $this->getUser();
            if ($user) {
                $booking->setUser($user);
            } else {
                $this->addFlash("error", "Vous devez être connecté pour effectuer une réservation.");
                return $this->redirectToRoute('login_route'); // Remplacez par le nom de votre route de connexion
            }

            try {
                $em->persist($booking);
                $em->flush();

                // Envoi de l'email
                $sendEmailService->send([
                    "sender_email" => "toqueor-restaurant@gmail.com",
                    "sender_name" => "Restaurant Toque'Or",
                    "recipient_email" => $booking->getEmail(),
                    "subject" => "Confirmation de votre réservation!",
                    "html_template" => "emails/booking.html.twig",
                    "context" => [
                        "booking_first_name" => $booking->getFirstName(),
                        "booking_last_name" => $booking->getLastName(),
                        "booking_email" => $booking->getEmail(),
                        "booking_date_booking" => $booking->getDateBooking()->format('Y-m-d'),
                        "booking_timemeal" => $booking->getTimeMeal()->getHeure(),
                        "booking_minutes" => $booking->getMinutes()->getMinute(),
                        "booking_guest" => $booking->getGuest(),
                        "booking_typetable" => $booking->getTypetable()->getName(),
                        "booking_message" => $booking->getMessage(),
                    ]
                ]);

                // Envoi du SMS de confirmation
                $phoneNumber = $booking->getPhone(); // Assurez-vous que cette méthode existe
                if ($phoneNumber) {
                    $message = sprintf(
                        "Bonjour %s %s, votre réservation est confirmée pour le %s à %s. Nous sommes ravis de vous accueillir et nous vous remercions de votre confiance.",
                        $booking->getFirstName(),
                        $booking->getLastName(),
                        $booking->getDateBooking()->format('Y-m-d'),
                        $booking->getTimeMeal()->getHeure()
                    );
                    $sendSmsService->sendSms($phoneNumber, $message);
                }

                $this->addFlash("success", "Votre réservation a bien été envoyée. Nous vous recontacterons dans les plus brefs délais.");
                return $this->redirectToRoute('visitor_booking_create');
            } catch (\Exception $e) {
                $this->addFlash("error", "Une erreur est survenue lors de l'enregistrement de la réservation.");
            }
        }

        return $this->render('pages/visitor/booking/create.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
