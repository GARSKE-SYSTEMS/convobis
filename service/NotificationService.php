<?php

namespace Convobis\Service;

require_once __DIR__ . '/../repository/NotificationRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../util/MailHelper.php';

use Convobis\Repository\NotificationRepository;
use Convobis\Repository\UserRepository;
use Convobis\Util\MailHelper;
use Convobis\Model\Notification;
use Convobis\Model\Message;

class NotificationService
{
    private NotificationRepository $repo;
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->repo = new NotificationRepository();
        $this->userRepo = new UserRepository();
    }

    /**
     * Event handler for message creation. Parses @mentions and sends notifications.
     * @param Message $message
     */
    public function onMessageCreated(Message $message): void
    {
        // parse mentions @username
        preg_match_all('/@([\w]+)/', $message->content, $matches);
        foreach (array_unique($matches[1]) as $username) {
            $user = $this->userRepo->findByName($username);
            if ($user) {
                $notification = new Notification(
                    null,
                    $user->id,
                    'mention',
                    null,
                    $message->id,
                    0,
                    date('Y-m-d H:i:s')
                );
                $this->repo->save($notification);
                // send email
                $subject = 'You were mentioned in a message';
                $body = "Hello {$user->name},\n\nYou were mentioned in a message (ID: {$message->id}).\n";
                MailHelper::send($user->email, $subject, $body);
            }
        }
    }

    /**
     * Fetch unread notifications for the current user
     */
    public function getUnread(int $userId): array
    {
        return $this->repo->findUnreadByUser($userId);
    }

    /**
     * Mark notification as read
     */
    public function markRead(int $notificationId): void
    {
        $this->repo->markRead($notificationId);
    }
}