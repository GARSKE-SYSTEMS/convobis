<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Client.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Client;
use Convobis\Util\Database;

class ClientRepository
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM clients");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($r) => Client::fromArray($r), $rows);
    }

    public function findById(int $id): ?Client
    {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return Client::fromArray($row);
        }
        return null;
    }

    public function findByName(string $name): array
    {
        // loose match by default; wrap param in %%
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE name LIKE :name");
        $stmt->bindValue(':name', "%{$name}%", \PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($r) => Client::fromArray($r), $rows);
    }

    public function save(Client $client): Client
    {
        if ($client->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO clients (name, createdAt) VALUES (:name, :createdAt)"
            );
            $stmt->bindValue(':name', $client->name, \PDO::PARAM_STR);
            $stmt->bindValue(':createdAt', $client->createdAt, \PDO::PARAM_STR);
            $stmt->execute();
            $client->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE clients SET name = :name, createdAt = :createdAt WHERE id = :id"
            );
            $stmt->bindValue(':name', $client->name, \PDO::PARAM_STR);
            $stmt->bindValue(':createdAt', $client->createdAt, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $client->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $client;
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM clients WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}