<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Comment;
use App\Notification\CommentCreatedNotification;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Notifier\NotifierInterface;

class CommentDoctrineSubscriber implements EventSubscriberInterface
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $comment = $args->getEntity();
        if (!$comment instanceof Comment) {
            return;
        }
        $this->notifier->send(new CommentCreatedNotification($comment), ...$this->notifier->getAdminRecipients());
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
        ];
    }
}
