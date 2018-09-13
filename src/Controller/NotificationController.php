<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 * Class NotificationController
 * @package App\Controller
 */
class NotificationController extends Controller
{
    private $notificationRepository;

    /**
     * NotificationController constructor.
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @Route("/unread-count", name="notification_unread")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function unreadCount()
    {
        return new JsonResponse([
            'count' => $this->notificationRepository->findUnseenByUser($this->getUser())
        ]);
    }
}
