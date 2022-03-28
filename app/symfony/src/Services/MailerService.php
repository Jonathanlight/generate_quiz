<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;
use Twig\Environment;

class MailerService
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @var MessageService
     */
    private MessageService $messageService;

    /**
     * MailerService constructor.
     *
     * @param MailerInterface $mailer
     * @param Environment   $twig
     */
    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        MessageService $messageService
    )
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->messageService = $messageService;
    }

    /**
     * @param string $subject
     * @param string $mailFrom
     * @param string $mailTo
     * @param string $template
     * @param array $parameters
     */
    public function send(string $subject, string $mailFrom, string $mailTo, string $template, array $parameters): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from($mailFrom)
                ->to($mailTo)
                ->subject($subject)
                ->htmlTemplate($template)
                ->context($parameters)
            ;

            $this->mailer->send($email);

        } catch (TransportExceptionInterface $exception) {
            $this->messageService->addError($exception->getMessage());
        }
    }
}