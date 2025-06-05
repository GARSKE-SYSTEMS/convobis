<?php

namespace Convobis\Service;

require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../model/Client.php';
require_once __DIR__ . '/../model/Contact.php';
require_once __DIR__ . '/../model/Address.php';
require_once __DIR__ . '/../model/Note.php';
require_once __DIR__ . '/../model/Tag.php';
require_once __DIR__ . '/../model/Reference.php';
require_once __DIR__ . '/../model/Topic.php';
require_once __DIR__ . '/../repository/ContactRepository.php';
require_once __DIR__ . '/../repository/AddressRepository.php';
require_once __DIR__ . '/../repository/NoteRepository.php';
require_once __DIR__ . '/../repository/TagRepository.php';
require_once __DIR__ . '/../repository/ReferenceRepository.php';

use Convobis\Model\Client;
use Convobis\Model\Contact;
use Convobis\Model\Address;
use Convobis\Model\Note;
use Convobis\Model\Tag;
use Convobis\Model\Reference;
use Convobis\Model\Topic;
use Convobis\Repository\ClientRepository;
use Convobis\Repository\ContactRepository;
use Convobis\Repository\AddressRepository;
use Convobis\Repository\NoteRepository;
use Convobis\Repository\TagRepository;
use Convobis\Repository\ReferenceRepository;

class ClientService {
    private ClientRepository $repo;

    public function __construct() {
        $this->repo = new ClientRepository();
    }

    public function create(array $data): Client {
        $client = Client::fromArray($data);
        return $this->repo->save($client);
    }

    public function addContact(int $clientId, array $contactData): Client {
        $repo = new ContactRepository();
        $contact = Contact::fromArray($contactData);
        $contact->clientId = $clientId;
        $repo->save($contact);
        return $this->findById($clientId);
    }

    public function removeContact(int $clientId, int $contactId): Client {
        $repo = new ContactRepository();
        $repo->delete($contactId);
        return $this->findById($clientId);
    }

    public function addAddress(int $clientId, array $data): Client {
        $repo = new AddressRepository();
        $address = Address::fromArray($data);
        $address->clientId = $clientId;
        $repo->save($address);
        return $this->findById($clientId);
    }

    public function removeAddress(int $clientId, int $addressId): Client {
        $repo = new AddressRepository();
        $repo->delete($addressId);
        return $this->findById($clientId);
    }

    public function addNote(int $clientId, array $data): Client {
        $repo = new NoteRepository();
        $note = Note::fromArray($data);
        $note->clientId = $clientId;
        $repo->save($note);
        return $this->findById($clientId);
    }

    public function removeNote(int $clientId, int $noteId): Client {
        $repo = new NoteRepository();
        $repo->delete($noteId);
        return $this->findById($clientId);
    }

    public function addTag(int $clientId, array $data): Client {
        $repo = new TagRepository();
        $tag = Tag::fromArray($data);
        $tag->clientId = $clientId;
        $repo->save($tag);
        return $this->findById($clientId);
    }

    public function removeTag(int $clientId, int $tagId): Client {
        $repo = new TagRepository();
        $repo->delete($tagId);
        return $this->findById($clientId);
    }

    public function addReference(int $clientId, array $data): Client {
        $repo = new ReferenceRepository();
        $reference = Reference::fromArray($data);
        $reference->clientId = $clientId;
        $repo->save($reference);
        return $this->findById($clientId);
    }

    public function removeReference(int $clientId, int $refId): Client {
        $repo = new ReferenceRepository();
        $repo->delete($refId);
        return $this->findById($clientId);
    }

    /**
     * Update an existing client.
     * @param int $clientId
     * @param array $data
     * @return Client
     */
    public function update(int $clientId, array $data): Client
    {
        $client = $this->repo->findById($clientId);
        if (! $client) {
            throw new \RuntimeException("Client not found");
        }
        $client->name = $data['name'] ?? $client->name;
        // preserve createdAt
        return $this->repo->save($client);
    }

    /**
     * Delete a client by ID.
     * @param int $clientId
     */
    public function delete(int $clientId): void
    {
        $this->repo->delete($clientId);
    }

    /**
     * Fetch all clients.
     * @return array<Client>
     */
    public function findAll(): array
    {
        return $this->repo->findAll();
    }

    /**
     * Fetch a single client by ID.
     * @param int $id
     * @return Client|null
     */
    public function findById(int $id): ?Client
    {
        return $this->repo->findById($id);
    }

    // ...similar methods for addAddress, addNote, addTag, addReference, addTopic
}
