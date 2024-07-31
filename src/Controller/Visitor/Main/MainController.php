<?php

namespace App\Controller\Visitor\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'visitor_main_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/main/index.html.twig');
    }
}
