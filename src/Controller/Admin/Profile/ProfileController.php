<?php

namespace App\Controller\Admin\Profile;

use App\Form\EditUserPasswordFormType;
use App\Form\EditUserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfileController extends AbstractController
{
    #[Route('/admin/profile', name: 'admin_profile_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/profile/index.html.twig');
    }

    #[Route('/admin/profile/edit', name: 'admin_profile_edit', methods:['GET', 'POST'])]
    public function editProfile(Request $request, EntityManagerInterface $em): Response
    {
        $admin = $this->getUser();
        dump($admin);
    
        // Créer le formulaire sans validation du mot de passe
        $form = $this->createForm(EditUserProfileFormType::class, $admin);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            // Vérifier si l'utilisateur souhaite modifier son mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
    
            if (!empty($plainPassword)) {
                $form = $this->createForm(EditUserProfileFormType::class, $admin, [
                    'validation_groups' => ['Default', 'password_change'],
                    'is_password_change' => true,
                ]);
                $form->handleRequest($request);
    
                // Valider le mot de passe si nécessaire
                if ($form->isValid()) {
                    $admin->setPassword($plainPassword);  // Utilisez un encodeur de mot de passe ici
                    $em->flush();
                    $this->addFlash('success', "Le profil a bien été modifié");
    
                    return $this->redirectToRoute("admin_profile_index");
                }
            } else {
                if ($form->isValid()) {
                    $em->flush();
                    $this->addFlash('success', "Le profil a bien été modifié");
    
                    return $this->redirectToRoute("admin_profile_index");
                }
            }
    
            $this->addFlash('danger', "Le formulaire contient des erreurs");
            dump($form->getErrors(true));
        }
    
        return $this->render('pages/admin/profile/edit_profile.html.twig', [
            "form" => $form->createView()
        ]);
    }
    

    #[Route('/admin/profile/edit/password', name: 'admin_profile_edit_password', methods:['GET', 'POST'])]
    public function editPassword(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em, #[CurrentUser] $admin): Response
    {
        $form = $this->createForm(EditUserPasswordFormType::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $plainPassword = $form->get('plainPassword')->getData();

            $passwordHashed = $hasher->hashPassword($admin, $plainPassword);

            $admin->setPassword($passwordHashed);

            $em->flush();

            $this->addFlash('success', 'Le mot de passe a bien été modifié.');

            return $this->redirectToRoute('admin_profile_index');
        }

        return $this->render('pages/admin/profile/edit_password.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/profile/delete', name: 'admin_profile_delete', methods:['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete_profile', $request->request->get('csrf_token'))) 
        {
            $admin = $this->getUser();

            $this->addFlash('success', "{$admin->getFirstName()} {$admin->getLastName()} a bien été supprimée.");

            // Dissocier les produits associés à l'utilisateur
            $products = $admin->getProducts();
            foreach ($products as $product) 
            {
                $product->setUser(null);
            }

            $em->remove($admin);
            $em->flush();

            // Déconnecter l'utilisateur après suppression
            $this->get('security.token_storage')->setToken(null);
        }
        
        return $this->redirectToRoute('admin_profile_index');
    }
}
