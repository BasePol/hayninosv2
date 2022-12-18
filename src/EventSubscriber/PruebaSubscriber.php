<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Security;


class PruebaSubscriber implements EventSubscriberInterface
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }




    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event)
    {
        //var_dump($event);exit;
        $evento = $event->getEntityInstance();
        if (!$comentario instanceof Evento) {
            return;
        }
        $user = $this->security->getUser();
        // We always should have a User object in EA
        if (!$user instanceof User) {
            throw new \LogicException('Currently logged in user is not an instance of User?!');
        }
        //var_dump($user);exit;
        $comentario->setUser($user);

    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
        ];
    }
}
