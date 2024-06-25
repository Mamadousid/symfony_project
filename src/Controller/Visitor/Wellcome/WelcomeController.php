<?php

namespace App\Controller\Visitor\Wellcome;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WelcomeController extends AbstractController
{
    #[Route('/', name: 'visitor_wellcome_index', methods:['GET'] )]
    public function index(): Response
    {
        return $this->render('pages/visitor/wellcome/index.html.twig');
    }
}
