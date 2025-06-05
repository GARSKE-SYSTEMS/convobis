<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Notification.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Notification;
use Convobis\Util\Database;

class NotificationRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(Notification $n): Notification
    {
        if ($n->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO notifications (userId, type, referenceId, messageId, isRead, createdAt) VALUES (:userId, :type, :referenceId, :messageId, :isRead, :createdAt)"
            );
            $stmt->bindValue(':userId', $n->userId, \PDO::PARAM_INT);
            $stmt->bindValue(':type', $n->type, \PDO::PARAM_STR);
            $stmt->bindValue(':referenceId', $n->referenceId, \PDO::PARAM_INT);
            $stmt->bindValue(':messageId', $n->messageId, \PDO::PARAM_INT);
            $stmt->bindValue(':isRead', $n->isRead, \PDO::PARAM_INT);
            $stmt->bindValue(':createdAt', $n->createdAt, \PDO::PARAM_STR);
            $stmt->execute();
            $n->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare("UPDATE notifications SET isRead = :isRead WHERE id = :id");
            $stmt->bindValue(':isRead', $n->isRead, \PDO::PARAM_INT);
            $stmt->bindValue(':id', $n->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $n;
    }

    public function findUnreadByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE userId = :userId AND isRead = 0 ORDER BY createdAt DESC");
        $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Notification::fromArray($r), $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findAllByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE userId = :userId ORDER BY createdAt DESC");
        $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Notification::fromArray($r), $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function markRead(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE notifications SET isRead = 1 WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function markAllRead(int $userId): void
    {
        $stmt = $this->db->prepare("UPDATE notifications SET isRead = 1 WHERE userId = :userId");
        $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
