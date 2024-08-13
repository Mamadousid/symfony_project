<?php

namespace App\Controller\Admin\Booking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    #[Route('/admin/booking', name: 'admin_booking_index')]
    public function index(): Response
    {
        return $this->render('pages/admin/booking/index.html.twig');
    }
}
