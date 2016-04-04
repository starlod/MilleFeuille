<?php

namespace AppBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Post;

class PostsCounter
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Post) {
            foreach ($entity->getCategories() as $category) {
                $c = $entityManager->getRepository('AppBundle:Category')->find($category->getId());
                echo $category->getId() . ' ' . count($c->getPosts()) . PHP_EOL;
                // $category->setCountPosts(count($category->getPosts()));
                // $entityManager->persist($category);
                // $entityManager->flush();
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
    }
}