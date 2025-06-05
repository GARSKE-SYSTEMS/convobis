<?php

namespace Convobis\Service;

require_once __DIR__ . '/../repository/NotificationRepository.php';
require_once __DIR__ . '/../repository/MessageRepository.php';
require_once __DIR__ . '/../repository/ReminderRepository.php';
require_once __DIR__ . '/../util/MailHelper.php';

use Convobis\Repository\ReminderRepository;
use Convobis\Repository\MessageRepository;
use Convobis\Util\MailHelper;

class ReminderService
{
    private ReminderRepository $reminderRepo;
    private MessageRepository $msgRepo;

    public function __construct()
    {
        $this->reminderRepo = new ReminderRepository();
        $this->msgRepo = new MessageRepository();
    }

    /**
     * Schedule a reminder for a message
     */
    public function schedule(int $userId, int $messageId, string $remindAt): void
    {
        $this->reminderRepo->save(new \Convobis\Model\Reminder(null, $userId, $messageId, $remindAt));
    }

    /**
     * Process due reminders (to be called by cron)
     */
    public function processDue(): void
    {
        $due = $this->reminderRepo->findDue();
        foreach ($due as $reminder) {
            $message = $this->msgRepo->findById($reminder->messageId);
            if ($message) {
                // send email
                MailHelper::send($reminder->userEmail, 'Reminder: Message ' . $message->id, $message->content);
            }
            $this->reminderRepo->markSent($reminder->id);
        }
    }
}
