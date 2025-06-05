<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Topic.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Topic;
use Convobis\Util\Database;

class TopicRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve all non-archived topics for a given client.
     * @param int $clientId
     * @return Topic[]
     */
    public function findAllByClient(int $clientId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM topics WHERE clientId = :clientId AND isArchived = 0 ORDER BY createdAt DESC"
        );
        $stmt->bindValue(':clientId', $clientId, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($r) => Topic::fromArray($r), $rows);
    }

    /**
     * Find a topic by its ID.
     * @param int $id
     * @return Topic|null
     */
    public function findById(int $id): ?Topic
    {
        $stmt = $this->db->prepare("SELECT * FROM topics WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return Topic::fromArray($row);
        }
        return null;
    }

    /**
     * Save or update a topic.
     * @param Topic $topic
     * @return Topic
     */
    public function save(Topic $topic): Topic
    {
        if ($topic->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO topics (clientId, name, description, createdAt, isArchived) VALUES (:clientId, :name, :description, :createdAt, :archived)"
            );
            $stmt->bindValue(':clientId', $topic->clientId, \PDO::PARAM_INT);
            $stmt->bindValue(':name', $topic->name, \PDO::PARAM_STR);
            $stmt->bindValue(':description', $topic->description, \PDO::PARAM_STR);
            $stmt->bindValue(':createdAt', $topic->createdAt, \PDO::PARAM_STR);
            $stmt->bindValue(':archived', $topic->isArchived ? 1 : 0, \PDO::PARAM_INT);
            $stmt->execute();
            $topic->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE topics SET name = :name, description = :description, isArchived = :archived WHERE id = :id"
            );
            $stmt->bindValue(':name', $topic->name, \PDO::PARAM_STR);
            $stmt->bindValue(':description', $topic->description, \PDO::PARAM_STR);
            $stmt->bindValue(':archived', $topic->isArchived ? 1 : 0, \PDO::PARAM_INT);
            $stmt->bindValue(':id', $topic->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $topic;
    }

    /**
     * Mark a topic as archived.
     * @param int $id
     */
    public function archive(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE topics SET isArchived = 1 WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Delete a topic permanently.
     * @param int $id
     */
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM topics WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
