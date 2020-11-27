<?php


namespace App\EventListener;


use App\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CommentListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) : void
    {
        $entity = $args->getEntity();
        if(true === property_exists($entity, 'createdAt') && $entity instanceof Comment){
            $entity->setCreatedAt(new \DateTime());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args) : void
    {
        $entity = $args->getEntity();

        if(true === property_exists($entity, 'createdAt') && $entity instanceof Comment){
            $entity->setCreatedAt(new \DateTime());
        }
    }
}