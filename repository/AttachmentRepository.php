<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Attachment.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Attachment;
use Convobis\Util\Database;

class AttachmentRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(Attachment $att): Attachment
    {
        $stmt = $this->db->prepare(
            "INSERT INTO attachments (messageId, filename, filepath, createdAt) VALUES (:messageId, :filename, :filepath, :createdAt)"
        );
        $stmt->bindValue(':messageId', $att->messageId, \PDO::PARAM_INT);
        $stmt->bindValue(':filename', $att->filename, \PDO::PARAM_STR);
        $stmt->bindValue(':filepath', $att->filepath, \PDO::PARAM_STR);
        $stmt->bindValue(':createdAt', $att->createdAt, \PDO::PARAM_STR);
        $stmt->execute();
        $att->id = (int)$this->db->lastInsertId();
        return $att;
    }

    public function findByMessage(int $messageId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM attachments WHERE messageId = :messageId"
        );
        $stmt->bindValue(':messageId', $messageId, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($r) => Attachment::fromArray($r), $rows);
    }
}
