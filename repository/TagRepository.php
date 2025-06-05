<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Tag.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Tag;
use Convobis\Util\Database;

class TagRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByClient(int $clientId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM tags WHERE clientId = :clientId");
        $stmt->bindValue(':clientId', $clientId, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Tag::fromArray($r), $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function save(Tag $tag): Tag
    {
        if ($tag->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO tags (clientId, name) VALUES (:clientId, :name)"
            );
            $stmt->bindValue(':clientId', $tag->clientId, \PDO::PARAM_INT);
            $stmt->bindValue(':name', $tag->name, \PDO::PARAM_STR);
            $stmt->execute();
            $tag->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE tags SET name = :name WHERE id = :id"
            );
            $stmt->bindValue(':name', $tag->name, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $tag->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $tag;
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM tags WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
