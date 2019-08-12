<?php


namespace App\Service;

use App\Entity\Token;
use App\Entity\User;
use Twig\Environment;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(User $user, Token $token)
    {
        $message = (new \Swift_Message('Confirm registration'))
            ->setFrom('noreply@book.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'email/registration.html.twig',
                    [
                        'token' => $token->getValue(),
                    ]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}