<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\User;
use Convobis\Util\Database;

class UserRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return User::fromArray($row);
        }
        return null;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return User::fromArray($row);
        }
        return null;
    }

    public function findByName(string $name): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
        $stmt->execute();
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return User::fromArray($row);
        }
        return null;
    }

    public function save(User $user): User
    {
        if ($user->id === 0) {
            $stmt = $this->db->prepare(
                "INSERT INTO users (email, password_hash, name) VALUES (:email, :hash, :name)"
            );
            $stmt->bindValue(':email', $user->email, \PDO::PARAM_STR);
            $stmt->bindValue(':hash', $user->passwordHash, \PDO::PARAM_STR);
            $stmt->bindValue(':name', $user->name, \PDO::PARAM_STR);
            $stmt->execute();
            $user->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE users SET email = :email, password_hash = :hash, name = :name WHERE id = :id"
            );
            $stmt->bindValue(':email', $user->email, \PDO::PARAM_STR);
            $stmt->bindValue(':hash', $user->passwordHash, \PDO::PARAM_STR);
            $stmt->bindValue(':name', $user->name, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $user->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $user;
    }
}
