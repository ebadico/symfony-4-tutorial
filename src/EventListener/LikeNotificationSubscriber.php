<?php

namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;

class LikeNotificationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
          Events::onFlush
        ];
    }

    /**
     * has to match the event name (Events::onFlush)
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        //keeps track of all the changes made to entity
        $uow = $em->getUnitOfWork();
        /** @var PersistentCollection $collectionUpdate */
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {
            if (!$collectionUpdate->getOwner() instanceof MicroPost) {
                continue;
            }

            if ('likedBy' !== $collectionUpdate->getMapping()['fieldName']) {
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff();

            if (!count($insertDiff)) {
                return;
            }
            /** @var MicroPost $microPost */
            $microPost = $collectionUpdate->getOwner();

            /** @var LikeNotification $notification */
            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);

            $notification->setLikedBy(reset($insertDiff));

            $em->persist($notification);

            $uow->computeChangeSet(
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );
        }
    }
}
