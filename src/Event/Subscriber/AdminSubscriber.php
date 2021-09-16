<?php

namespace App\Event\Subscriber;

use App\Entity\Annulation;
use App\Entity\Entree;
use App\Entity\Mutation;
use App\Entity\Rapport;
use App\Entity\User;
use App\Event\Subscriber\Traits\AnnulationSubscriberTrait;
use App\Event\Subscriber\Traits\EntreeSubscriberTrait;
use App\Event\Subscriber\Traits\MutationSubscriberTrait;
use App\Event\Subscriber\Traits\RapportSubscriberTrait;
use App\Event\Subscriber\Traits\SortieSubscriberTrait;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdminSubscriber implements EventSubscriberInterface
{
    use EntreeSubscriberTrait,
    MutationSubscriberTrait,
    SortieSubscriberTrait,
    RapportSubscriberTrait,
    AnnulationSubscriberTrait;

    /** @var TokenStorageInterface $token */
    private $token;

    /** @var UserPasswordHasherInterface $hasher */
    private $hasher;

    /** @var User|null $user */
    private $user;

    public function __construct(
        TokenStorageInterface $token,
        UserPasswordHasherInterface $hasher 
    )
    {
        $this->user = is_null($token->getToken()) ? null : $token->getToken()->getUser();
        $this->hasher = $hasher;
    }
    
    public function onPersist(BeforeEntityPersistedEvent $event)
    {
        $subject = $event->getEntityInstance();

        $class = get_class($subject);
        switch($class){                
            case Entree::class:
                $this->persistEntree($subject,$this->hasher);
                break;
                
                
            case Mutation::class:
                $this->persistMutation($subject);
                break;
                
                
            case Sortie::class:
                $this->persistSortie($subject);
                break;
                
                
            case Annulation::class:
                $this->persistAnnulation($subject);
                break;
                
                
            case Rapport::class:
                $this->persistRapport($subject,$this->user);
                break;
        }
    }

    public function onDelete(BeforeEntityDeletedEvent $event)
    {
        $subject = $event->getEntityInstance();

        $class = get_class($subject);
        switch($class){                
            case Entree::class:
                $this->removeEntree($subject);
                break;
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['onPersist'],
            //BeforeEntityUpdatedEvent::class => ['onUpdate'],
            BeforeEntityDeletedEvent::class => ['onDelete'],

            //WorkerMessageFailedEvent::class => ['onMessageFailed']
        ];
    }
}