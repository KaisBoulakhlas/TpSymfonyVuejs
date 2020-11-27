<?php


namespace App\EventListener;


use App\Entity\Lesson;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class LessonListener
{
    private SluggerInterface $slugger ;

    /**
     * LessonListener constructor.
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) : void
    {
        $entity = $args->getEntity();
        if(true === property_exists($entity, 'slug') && $entity instanceof Lesson){
            $entity->setSlug($this->slugger->slug(strtolower($entity->getTitle())));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args) : void
    {
        $entity = $args->getEntity();

        if(true === property_exists($entity, 'slug') && $entity instanceof Lesson){
            $entity->setSlug($this->slugger->slug(strtolower($entity->getTitle())));
        }
    }
}