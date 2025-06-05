<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Reminder.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Reminder;
use Convobis\Util\Database;

class ReminderRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(Reminder $r): Reminder
    {
        if ($r->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO reminders (userId, messageId, remindAt, isSent) VALUES (:userId, :messageId, :remindAt, 0)"
            );
            $stmt->bindValue(':userId', $r->userId, \PDO::PARAM_INT);
            $stmt->bindValue(':messageId', $r->messageId, \PDO::PARAM_INT);
            $stmt->bindValue(':remindAt', $r->remindAt, \PDO::PARAM_STR);
            $stmt->execute();
            $r->id = (int)$this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE reminders SET remindAt = :remindAt, isSent = :isSent, sentAt = :sentAt WHERE id = :id"
            );
            $stmt->bindValue(':remindAt', $r->remindAt, \PDO::PARAM_STR);
            $stmt->bindValue(':isSent', $r->isSent, \PDO::PARAM_INT);
            $stmt->bindValue(':sentAt', $r->sentAt, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $r->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $r;
    }

    public function findDue(): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.email as userEmail FROM reminders r JOIN users u ON r.userId = u.id WHERE r.isSent = 0 AND r.remindAt <= :now"
        );
        $now = date('Y-m-d H:i:s');
        $stmt->bindValue(':now', $now, \PDO::PARAM_STR);
        $stmt->execute();
        return array_map(fn($r) => Reminder::fromArray($r) + ['userEmail' => $r['userEmail']], $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function markSent(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE reminders SET isSent = 1, sentAt = :sentAt WHERE id = :id");
        $stmt->bindValue(':sentAt', date('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
