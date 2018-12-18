<?php

namespace AppBundle\Service;

class CreateMail
{
    protected $mailer;
    protected $mailerSender;
    protected $twig;

    public function __construct($mailer, $mailerSender, $twig){
        $this->mailer = $mailer;
        $this->mailerSender = $mailerSender;
        $this->twig = $twig;
    }
    public function createMail($subject, $To, $type)
    {

        $mail = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setSubject($subject)
            ->setFrom($this->mailerSender)
            ->setTo($To)
            ->addPart(
                $this->twig
                    ->render(
                        'Mail/' . $type . '.html.twig'
                    ),
                'text/html'
            );
        $this->mailer->send($mail);

        return $mail;
    }
}