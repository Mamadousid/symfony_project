<?php

namespace App\Controller\Visitor\Registration;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Flex\Response as FlexResponse;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{   
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'visitor_registration_register', methods:['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {    

        if ($this->getUser()) 
        {
            return $this->redirectToRoute('visitor_wellcome_index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // encode the plain password
            $passwordHashed = $userPasswordHasher->hashPassword($user,$form->get('password')->getData());

            $user->setPassword($passwordHashed);
            

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('visitor_registration_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mams-18@hotmail.fr', 'Sidibe Mamadou'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de compte lors de l\'inscription du site Toque\'OR')
                    ->htmlTemplate('emails/confirmation_email.html.twig')
            );

           

            return $this->redirectToRoute('visitor_registration_waiting_for_email_confirmation');
        }

        return $this->render('pages/visitor/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/waiting-for-email-confirmation', name: 'visitor_registration_waiting_for_email_confirmation', methods:['GET'])]
    public function waitingForEmailConfirmation(): Response
    {
        return $this->render("pages/visitor/registration/waiting_for_email_confirmation.html.twig");
    }
    

    #[Route('/verify/email', name: 'visitor_registration_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) 
        {
            return $this->redirectToRoute('visitor_registration_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) 
        {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try 
        {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } 
        catch (VerifyEmailExceptionInterface $exception) 
        {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre email a bien été vérifié. Veuillez vous connecter!');

        return $this->redirectToRoute('visitor_authentication_login');
    }
}
