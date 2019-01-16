<?php

namespace AppBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FOSUserListener implements EventSubscriberInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_FAILURE => 'onRegistrationFailure',
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onRegistrationInitialize'
        ];
    }

    public function onRegistrationFailure(FormEvent $event)
    {
        $url = $this->router->generate('user_login_register');
        $event->setResponse(new RedirectResponse($url));
    }

    public function onRegistrationInitialize(UserEvent $event)
    {
    }


}