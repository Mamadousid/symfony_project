<?php
namespace App\Controller\Visitor\Authentication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'visitor_authentication_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifie si l'utilisateur est déjà authentifié
        if ($this->getUser()) 
        {
            return $this->redirectToRoute('visitor_wellcome_index');
        }

        // Récupère l'erreur de connexion, s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier nom d'utilisateur (ou email) saisi par l'utilisateur dans le formulaire de connexion
        $lastUsername = $authenticationUtils->getLastUsername();

        // 'last_username' : pour préremplir le champ du formulaire si l'utilisateur a déjà saisi quelque chose
        // 'error' : pour afficher un message d'erreur si la connexion a échoué
        return $this->render('pages/visitor/authentication/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    // Déclare une route pour la déconnexion avec l'URL '/logout'
    // et le nom de la route 'app_logout'
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Lance une exception logique pour indiquer que cette méthode ne devrait jamais être appelée directement
        // Symfony intercepte cette route automatiquement et gère la déconnexion de l'utilisateur
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

