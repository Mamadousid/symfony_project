<?php

namespace App\Controller\Admin\Contact;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * Affiche la liste de tous les contacts.
     *
     * @param ContactRepository $contactRepository Le repository des contacts
     * @return Response La réponse HTTP avec la vue des contacts
     */
    #[Route('/admin/contact/list', name: 'admin_contact_index', methods:['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        // Récupère tous les contacts de la base de données
        $contacts = $contactRepository->findAll();
        
        // Rend la vue 'index.html.twig' en passant les contacts comme variable
        return $this->render('pages/admin/contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * Supprime un contact spécifique.
     *
     * @param Contact $contact Le contact à supprimer
     * @param Request $request La requête HTTP
     * @param EntityManagerInterface $em L'Entity Manager pour gérer les entités
     * @return Response La réponse HTTP redirigeant vers la liste des contacts
     */
    #[Route('/admin/contact/{id<\d+>}/delete', name: 'admin_contact_delete', methods:['DELETE'])]
    public function delete(Contact $contact, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifie la validité du token CSRF pour la suppression
        if ($this->isCsrfTokenValid('delete_contact_'.$contact->getId(), $request->request->get('csrf_token'))) 
        {
            // Supprime le contact de la base de données
            $em->remove($contact);
            $em->flush();

            // Ajoute un message flash pour indiquer que la suppression a réussi
            $this->addFlash('success', "Ce contact a bien été supprimé.");
        }
        
        // Redirige vers la liste des contacts après la suppression
        return $this->redirectToRoute('admin_contact_index');
    }
}
