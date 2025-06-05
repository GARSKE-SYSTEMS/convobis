<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Contact.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Contact;
use Convobis\Util\Database;

class ContactRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByClient(int $clientId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM contacts WHERE clientId = :clientId");
        $stmt->bindValue(':clientId', $clientId, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Contact::fromArray($r), $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function save(Contact $contact): Contact
    {
        if ($contact->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO contacts (clientId, type, value) VALUES (:clientId, :type, :value)"
            );
            $stmt->bindValue(':clientId', $contact->clientId, \PDO::PARAM_INT);
            $stmt->bindValue(':type', $contact->type, \PDO::PARAM_STR);
            $stmt->bindValue(':value', $contact->value, \PDO::PARAM_STR);
            $stmt->execute();
            $contact->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE contacts SET type = :type, value = :value WHERE id = :id"
            );
            $stmt->bindValue(':type', $contact->type, \PDO::PARAM_STR);
            $stmt->bindValue(':value', $contact->value, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $contact->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $contact;
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM contacts WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
