<?php

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\Security\Core\Security;


class EasyAdminSubscriber implements EventSubscriberInterface
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }




    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event)
    {
        $comentario = $event->getEntityInstance();
        if (!$comentario instanceof Comentario) {
            return;
        }
        $user = $this->security->getUser();
        // We always should have a User object in EA
        if (!$user instanceof User) {
            throw new \LogicException('Currently logged in user is not an instance of User?!');
        }
        $comentario->setUser($user);

    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
        ];
    }
}
