<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Reference.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Reference;
use Convobis\Util\Database;

class ReferenceRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByClient(int $clientId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM references WHERE clientId = :clientId");
        $stmt->bindValue(':clientId', $clientId, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Reference::fromArray($r), $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function save(Reference $ref): Reference
    {
        if ($ref->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO references (clientId, title, url) VALUES (:clientId, :title, :url)"
            );
            $stmt->bindValue(':clientId', $ref->clientId, \PDO::PARAM_INT);
            $stmt->bindValue(':title', $ref->title, \PDO::PARAM_STR);
            $stmt->bindValue(':url', $ref->url, \PDO::PARAM_STR);
            $stmt->execute();
            $ref->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE references SET title = :title, url = :url WHERE id = :id"
            );
            $stmt->bindValue(':title', $ref->title, \PDO::PARAM_STR);
            $stmt->bindValue(':url', $ref->url, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $ref->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $ref;
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM references WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
