<?php

namespace App\Controller\Visitor\Event;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/event', name: 'visitor_event_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/event/index.html.twig');
    }
}
