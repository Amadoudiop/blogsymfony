<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\EventListener\LastLoginListener as FOSLastLoginListener;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;

class LastLoginListener extends FOSLastLoginListener //implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    protected $userManager;

    /**
     * LastLoginListener constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onImplicitLogin',
            //SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'checkLogin',
        );
    }

    /**
     * @param UserEvent $event
     */
    public function onImplicitLogin(UserEvent $event)
    {
        $user = $event->getUser();
        $user->setLastLogin(new \DateTime());
        $this->userManager->updateUser($user);
    }
/*
    /**
     * @param InteractiveLoginEvent $event
     */
/*
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user->getValidation() == 1){

            if ($user instanceof UserInterface) {
                $user->setLastLogin(new \DateTime());
                $this->userManager->updateUser($user);
            }
        }else{

        }
    } */

    /**
     * @param InteractiveLoginEvent $event
     */
    public function checkLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user->getValidation() == 1){
            if ($user instanceof UserInterface) {
                $user->setLastLogin(new \DateTime());
                $this->userManager->updateUser($user);
            }
        }else{
            $authenticated = $event->getAuthenticationToken()->isAuthenticated();
            if( $authenticated== true){
                $event->getAuthenticationToken();
                $this->tokenStorage->setToken(null);
            }
        }
    }
}
