<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Message.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Message;
use Convobis\Util\Database;

class MessageRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Fetch all messages for a given topic.
     * @param int $topicId
     * @return Message[]
     */
    public function findByTopic(int $topicId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM messages WHERE topicId = :topicId ORDER BY createdAt ASC"
        );
        $stmt->bindValue(':topicId', $topicId, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($r) => Message::fromArray($r), $rows);
    }

    /**
     * Save a new message.
     * @param Message $message
     * @return Message
     */
    public function save(Message $message): Message
    {
        $stmt = $this->db->prepare(
            "INSERT INTO messages (topicId, authorId, authorType, content, createdAt) VALUES (:topicId, :authorId, :authorType, :content, :createdAt)"
        );
        $stmt->bindValue(':topicId', $message->topicId, \PDO::PARAM_INT);
        $stmt->bindValue(':authorId', $message->authorId, \PDO::PARAM_INT);
        $stmt->bindValue(':authorType', $message->authorType, \PDO::PARAM_STR);
        $stmt->bindValue(':content', $message->content, \PDO::PARAM_STR);
        $stmt->bindValue(':createdAt', $message->createdAt, \PDO::PARAM_STR);
        $stmt->execute();
        $message->id = (int)$this->db->lastInsertId();
        return $message;
    }

    /**
     * Delete a message by ID.
     * @param int $id
     */
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Update message fields (replyToId and isPinned)
     * @param Message $message
     * @return Message
     */
    public function update(Message $message): Message
    {
        $stmt = $this->db->prepare(
            "UPDATE messages SET replyToId = :replyToId, isPinned = :isPinned WHERE id = :id"
        );
        $stmt->bindValue(':replyToId', $message->replyToId, \PDO::PARAM_INT);
        $stmt->bindValue(':isPinned', $message->isPinned, \PDO::PARAM_INT);
        $stmt->bindValue(':id', $message->id, \PDO::PARAM_INT);
        $stmt->execute();
        return $message;
    }

    /**
     * Fetch all pinned messages for a topic.
     */
    public function findPinnedByTopic(int $topicId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM messages WHERE topicId = :topicId AND isPinned = 1 ORDER BY createdAt ASC"
        );
        $stmt->bindValue(':topicId', $topicId, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Message::fromArray($r), $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Fetch a single message by ID.
     * @param int $id
     * @return Message|null
     */
    public function findById(int $id): ?Message
    {
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return Message::fromArray($row);
        }
        return null;
    }
}
