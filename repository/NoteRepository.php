<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Note.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Note;
use Convobis\Util\Database;

class NoteRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByClient(int $clientId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM notes WHERE clientId = :clientId");
        $stmt->bindValue(':clientId', $clientId, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Note::fromArray($r), $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function save(Note $note): Note
    {
        if ($note->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO notes (clientId, content, createdAt) VALUES (:clientId, :content, :createdAt)"
            );
            $stmt->bindValue(':clientId', $note->clientId, \PDO::PARAM_INT);
            $stmt->bindValue(':content', $note->content, \PDO::PARAM_STR);
            $stmt->bindValue(':createdAt', $note->createdAt, \PDO::PARAM_STR);
            $stmt->execute();
            $note->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE notes SET content = :content WHERE id = :id"
            );
            $stmt->bindValue(':content', $note->content, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $note->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $note;
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
