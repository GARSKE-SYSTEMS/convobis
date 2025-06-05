<?php

namespace Convobis\Repository;

require_once __DIR__ . '/../model/Address.php';
require_once __DIR__ . '/../util/Database.php';

use Convobis\Model\Address;
use Convobis\Util\Database;

class AddressRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByClient(int $clientId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM addresses WHERE clientId = :clientId");
        $stmt->bindValue(':clientId', $clientId, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Address::fromArray($r), $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function save(Address $address): Address
    {
        if ($address->id === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO addresses (clientId, street, city, state, zip, country) VALUES (:clientId, :street, :city, :state, :zip, :country)"
            );
            $stmt->bindValue(':clientId', $address->clientId, \PDO::PARAM_INT);
            $stmt->bindValue(':street', $address->street, \PDO::PARAM_STR);
            $stmt->bindValue(':city', $address->city, \PDO::PARAM_STR);
            $stmt->bindValue(':state', $address->state, \PDO::PARAM_STR);
            $stmt->bindValue(':zip', $address->zip, \PDO::PARAM_STR);
            $stmt->bindValue(':country', $address->country, \PDO::PARAM_STR);
            $stmt->execute();
            $address->id = (int) $this->db->lastInsertId();
        } else {
            $stmt = $this->db->prepare(
                "UPDATE addresses SET street = :street, city = :city, state = :state, zip = :zip, country = :country WHERE id = :id"
            );
            $stmt->bindValue(':street', $address->street, \PDO::PARAM_STR);
            $stmt->bindValue(':city', $address->city, \PDO::PARAM_STR);
            $stmt->bindValue(':state', $address->state, \PDO::PARAM_STR);
            $stmt->bindValue(':zip', $address->zip, \PDO::PARAM_STR);
            $stmt->bindValue(':country', $address->country, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $address->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $address;
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM addresses WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
