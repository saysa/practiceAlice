<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\TokenRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/registration", name="app_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setRoles(['ROLE_USER']);
            $token = new Token($user);
            $manager->persist($token);
            $manager->flush();

            $mailer->send($user, $token);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/valid/token/{value}", name="valid_token")
     */
    public function validToken(Request $request, TokenRepository $token_repository, EntityManagerInterface $manager, GuardAuthenticatorHandler $authenticator_handler, LoginFormAuthenticator $authenticator)
    {
        $token = $token_repository->findOneBy(['value' => $request->attributes->get('value')]);

        if (is_null($token)) {
            return $this->redirectToRoute('app_registration');
        }

        $user = $token->getUser();
        $user->setEnable(true);
        $manager->flush($user);

        return $authenticator_handler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main'
        );
    }
}
